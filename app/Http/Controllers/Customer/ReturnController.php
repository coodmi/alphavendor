<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /** Order statuses where customers may open a return / exchange request. */
    public const RETURNABLE_ORDER_STATUSES = ['shipped', 'delivered'];

    public function index()
    {
        $returns = ReturnRequest::where('user_id', auth()->id())
            ->with(['order', 'product', 'vendor'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => ReturnRequest::where('user_id', auth()->id())->count(),
            'pending' => ReturnRequest::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'approved' => ReturnRequest::where('user_id', auth()->id())->where('status', 'approved')->count(),
            'completed' => ReturnRequest::where('user_id', auth()->id())->whereIn('status', ['refunded', 'completed'])->count(),
        ];

        return view('customer.returns.index', compact('returns', 'stats'));
    }

    public function create(Request $request)
    {
        $orderItemId = $request->get('order_item_id');

        if (! $orderItemId) {
            return redirect()->route('orders.my-orders')->with('error', 'Please select an order item to request a return or exchange.');
        }

        $orderItem = OrderItem::with(['order', 'product'])
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($orderItemId);

        if (! in_array($orderItem->order->status, self::RETURNABLE_ORDER_STATUSES, true)) {
            return redirect()->route('orders.show', $orderItem->order_id)
                ->with('error', 'Return or exchange is only available after the order has been shipped.');
        }

        $existingReturn = ReturnRequest::where('order_item_id', $orderItemId)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->first();

        if ($existingReturn) {
            return redirect()->route('customer.returns.show', $existingReturn)
                ->with('error', 'A return request already exists for this item.');
        }

        $exchangeProducts = $this->exchangeProductOptions($orderItem);

        return view('customer.returns.create', compact('orderItem', 'exchangeProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'type' => 'required|in:return,refund,exchange',
            'reason' => 'required|in:defective,wrong_item,not_as_described,damaged,size_issue,quality_issue,changed_mind,other',
            'reason_details' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'customer_notes' => 'nullable|string|max:500',
            'images.*' => 'nullable|image|max:2048',
            'exchange_product_id' => 'nullable|exists:products,id',
        ]);

        $orderItem = OrderItem::with(['order', 'product'])
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($request->order_item_id);

        if (! in_array($orderItem->order->status, self::RETURNABLE_ORDER_STATUSES, true)) {
            return back()->withInput()->with('error', 'This order is not eligible for return or exchange yet.');
        }

        if ($request->quantity > $orderItem->quantity) {
            return back()->withInput()->with('error', 'Return quantity cannot exceed ordered quantity.');
        }

        if (! $orderItem->product) {
            return back()->withInput()->with('error', 'Product not found for this order item.');
        }

        $vendorId = $orderItem->product->vendor_id ?? $orderItem->order->vendor_id;
        if (! $vendorId) {
            return back()->withInput()->with('error', 'Unable to process return: seller information is missing.');
        }

        if ($request->type === 'exchange' && $request->exchange_product_id) {
            $validExchange = Product::where('id', $request->exchange_product_id)
                ->where('vendor_id', $vendorId)
                ->where('id', '!=', $orderItem->product_id)
                ->exists();

            if (! $validExchange) {
                return back()->withInput()->with('error', 'Please select a valid product for exchange from the same seller.');
            }
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('returns', 'public');
            }
        }

        try {
            $return = DB::transaction(function () use ($request, $orderItem, $vendorId, $images) {
                return ReturnRequest::create([
                    'order_id' => $orderItem->order_id,
                    'order_item_id' => $orderItem->id,
                    'user_id' => auth()->id(),
                    'vendor_id' => $vendorId,
                    'product_id' => $orderItem->product_id,
                    'type' => $request->type,
                    'reason' => $request->reason,
                    'reason_details' => $request->reason_details,
                    'quantity' => $request->quantity,
                    'amount' => $orderItem->price * $request->quantity,
                    'customer_notes' => $request->customer_notes,
                    'images' => $images ?: null,
                    'exchange_product_id' => $request->type === 'exchange' ? ($request->exchange_product_id ?: null) : null,
                    'status' => 'pending',
                ]);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not submit your request. Please try again.');
        }

        $this->notifyReturnCreated($return, $orderItem, $request->type);

        return redirect()
            ->route('customer.returns.show', $return)
            ->with('success', 'Your return/exchange request was submitted successfully.');
    }

    public function show(ReturnRequest $return)
    {
        if ($return->user_id !== auth()->id()) {
            abort(403);
        }

        $return->load(['order', 'orderItem', 'product', 'vendor', 'exchangeProduct']);

        return view('customer.returns.show', compact('return'));
    }

    public function cancel(ReturnRequest $return)
    {
        if ($return->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($return->status, ['pending', 'approved'], true)) {
            return back()->with('error', 'Cannot cancel this return request.');
        }

        $return->update(['status' => 'cancelled']);

        return back()->with('success', 'Return request cancelled successfully.');
    }

    protected function exchangeProductOptions(OrderItem $orderItem): \Illuminate\Support\Collection
    {
        $vendorId = $orderItem->product?->vendor_id ?? $orderItem->order->vendor_id;

        if (! $vendorId) {
            return collect();
        }

        return Product::query()
            ->where('vendor_id', $vendorId)
            ->where('id', '!=', $orderItem->product_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'price']);
    }

    protected function notifyReturnCreated(ReturnRequest $return, OrderItem $orderItem, string $type): void
    {
        $orderNumber = $orderItem->order->order_number;
        $customerName = auth()->user()->name;

        User::whereIn('role', ['admin', 'employee'])->pluck('id')->each(function ($uid) use ($return, $type, $orderNumber, $customerName) {
            Notification::create([
                'user_id' => $uid,
                'type' => 'warning',
                'title' => 'New Return/Exchange Request',
                'message' => "{$customerName} submitted a {$type} request for order #{$orderNumber}.",
                'data' => ['url' => '/admin/returns/' . $return->id],
            ]);
        });

        if ($return->vendor_id) {
            Notification::create([
                'user_id' => $return->vendor_id,
                'type' => 'warning',
                'title' => 'New Return/Exchange Request',
                'message' => "A customer submitted a {$type} request for order #{$orderNumber}.",
                'data' => ['url' => '/vendor/returns/' . $return->id],
            ]);
        }
    }
}
