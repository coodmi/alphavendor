<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CodOrderController extends Controller
{
    /**
     * Display Cash on Delivery orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->where('payment_method', 'cod')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => Order::where('payment_method', 'cod')->count(),
            'pending' => Order::where('payment_method', 'cod')->where('status', 'pending')->count(),
            'processing' => Order::where('payment_method', 'cod')->where('status', 'processing')->count(),
            'delivered' => Order::where('payment_method', 'cod')->where('status', 'delivered')->count(),
            'paid' => Order::where('payment_method', 'cod')->where('payment_status', 'paid')->count(),
            'unpaid' => Order::where('payment_method', 'cod')->where('payment_status', 'pending')->count(),
            'total_amount' => Order::where('payment_method', 'cod')->sum('total'),
        ];

        return view('admin.cod-orders.index', compact('orders', 'stats'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Mark payment as received
     */
    public function markPaid($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_method !== 'cod') {
            return redirect()->back()->with('error', 'This is not a COD order!');
        }

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payment marked as received!');
    }
}
