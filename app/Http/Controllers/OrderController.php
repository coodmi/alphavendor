<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\VendorWallet;
use App\Models\Transaction;
use App\Models\CommissionSetting;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        return view('orders.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'nullable|string',
            'shipping_zip' => 'required|string',
            'shipping_country' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();

        try {
            // Group cart items by vendor
            $ordersByVendor = [];

            foreach ($cart as $item) {
                $vendorId = $item['vendor_id'];

                if (!isset($ordersByVendor[$vendorId])) {
                    $ordersByVendor[$vendorId] = [];
                }

                $ordersByVendor[$vendorId][] = $item;
            }

            // Create separate order for each vendor
            foreach ($ordersByVendor as $vendorId => $items) {
                $subtotal = 0;
                $commissionTotal = 0;

                // Calculate totals
                foreach ($items as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;

                    // Get commission rate
                    $commissionRate = CommissionSetting::getCommissionRate($item['id'], $item['category_id']);
                    $commissionAmount = ($itemTotal * $commissionRate) / 100;
                    $commissionTotal += $commissionAmount;
                }

                $vendorEarning = $subtotal - $commissionTotal;
                $commissionRate = $subtotal > 0 ? ($commissionTotal / $subtotal) * 100 : 0;

                // Create order
                $order = Order::create([
                    'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendorId,
                    'subtotal' => $subtotal,
                    'commission_amount' => $commissionTotal,
                    'commission_rate' => $commissionRate,
                    'vendor_earning' => $vendorEarning,
                    'total' => $subtotal,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'payment_method' => $validated['payment_method'],
                    'shipping_address' => $validated['shipping_address'],
                    'shipping_city' => $validated['shipping_city'],
                    'shipping_state' => $validated['shipping_state'] ?? '',
                    'shipping_zip' => $validated['shipping_zip'],
                    'shipping_country' => $validated['shipping_country'],
                    'phone' => $validated['phone'],
                    'notes' => $validated['notes'] ?? ''
                ]);

                // Create order items
                foreach ($items as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    $commissionRate = CommissionSetting::getCommissionRate($item['id'], $item['category_id']);
                    $commissionAmount = ($itemTotal * $commissionRate) / 100;
                    $vendorEarning = $itemTotal - $commissionAmount;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'commission_rate' => $commissionRate,
                        'commission_amount' => $commissionAmount,
                        'vendor_earning' => $vendorEarning,
                        'subtotal' => $itemTotal
                    ]);
                }

                // Update vendor wallet - add to pending balance
                $wallet = VendorWallet::firstOrCreate(
                    ['vendor_id' => $vendorId],
                    ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
                );

                $wallet->increment('pending_balance', $vendorEarning);

                // Create transaction record
                Transaction::create([
                    'vendor_id' => $vendorId,
                    'order_id' => $order->id,
                    'transaction_number' => 'TXN-' . time() . '-' . rand(1000, 9999),
                    'type' => 'sale',
                    'amount' => $vendorEarning,
                    'status' => 'pending',
                    'description' => 'Order #' . $order->order_number
                ]);
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('orders.success')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function success()
    {
        return view('orders.success');
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['vendor', 'items'])
            ->latest()
            ->paginate(16);

        return view('orders.my-orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['vendor', 'items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    // Vendor methods
    public function vendorOrders()
    {
        $orders = Order::where('vendor_id', Auth::id())
            ->with(['user', 'items'])
            ->latest()
            ->paginate(16);

        return view('orders.vendor-orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('vendor_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded'
        ]);

        $order->update(['status' => $validated['status']]);

        // If order is delivered, move from pending to available balance
        if ($validated['status'] === 'delivered') {
            $wallet = VendorWallet::where('vendor_id', $order->vendor_id)->first();

            if ($wallet) {
                $wallet->decrement('pending_balance', $order->vendor_earning);
                $wallet->increment('balance', $order->vendor_earning);
                $wallet->increment('total_earned', $order->vendor_earning);

                // Update transaction status
                Transaction::where('order_id', $order->id)
                    ->where('vendor_id', $order->vendor_id)
                    ->update(['status' => 'completed']);
            }
        }

        return redirect()->back()->with('success', 'Order status updated!');
    }
}
