<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
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
        
        if (!$orderItemId) {
            return redirect()->route('orders.my-orders')->with('error', 'Invalid order item');
        }

        $orderItem = OrderItem::with(['order', 'product'])
            ->whereHas('order', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($orderItemId);

        // Check if already has a return request
        $existingReturn = ReturnRequest::where('order_item_id', $orderItemId)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->first();

        if ($existingReturn) {
            return redirect()->route('customer.returns.show', $existingReturn)
                ->with('error', 'A return request already exists for this item');
        }

        return view('customer.returns.create', compact('orderItem'));
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
            'exchange_product_id' => 'required_if:type,exchange|exists:products,id',
        ]);

        $orderItem = OrderItem::with(['order', 'product'])
            ->whereHas('order', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($request->order_item_id);

        // Check quantity
        if ($request->quantity > $orderItem->quantity) {
            return back()->with('error', 'Return quantity cannot exceed ordered quantity');
        }

        // Ensure product exists
        if (! $orderItem->product) {
            return back()->with('error', 'Product not found for this order item.');
        }

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('returns', 'public');
                $images[] = $path;
            }
        }

        $return = ReturnRequest::create([
            'order_id'             => $orderItem->order_id,
            'order_item_id'        => $orderItem->id,
            'user_id'              => auth()->id(),
            'vendor_id'            => $orderItem->product->vendor_id,
            'product_id'           => $orderItem->product_id,
            'type'                 => $request->type,
            'reason'               => $request->reason,
            'reason_details'       => $request->reason_details,
            'quantity'             => $request->quantity,
            'amount'               => $orderItem->price * $request->quantity,
            'customer_notes'       => $request->customer_notes,
            'images'               => $images,
            'exchange_product_id'  => $request->exchange_product_id ?: null,
            'status'               => 'pending',
        ]);

        // Notify admin & vendor
        $notifyUsers = \App\Models\User::whereIn('role', ['admin', 'employee'])->pluck('id');
        foreach ($notifyUsers as $uid) {
            \App\Models\Notification::create([
                'user_id' => $uid,
                'type'    => 'warning',
                'title'   => 'New Return/Exchange Request',
                'message' => auth()->user()->name . ' submitted a ' . $request->type . ' request for order #' . $orderItem->order->order_number . '.',
                'data'    => json_encode(['url' => '/admin/returns/' . $return->id]),
            ]);
        }
        // Notify vendor
        \App\Models\Notification::create([
            'user_id' => $orderItem->product->vendor_id,
            'type'    => 'warning',
            'title'   => 'New Return/Exchange Request',
            'message' => 'A customer submitted a ' . $request->type . ' request for order #' . $orderItem->order->order_number . '.',
            'data'    => json_encode(['url' => '/vendor/returns/' . $return->id]),
        ]);

        return redirect()->route('customer.returns.show', $return)
            ->with('success', 'Return request submitted successfully');
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

        if (!in_array($return->status, ['pending', 'approved'])) {
            return back()->with('error', 'Cannot cancel this return request');
        }

        $return->update(['status' => 'cancelled']);

        return back()->with('success', 'Return request cancelled successfully');
    }
}
