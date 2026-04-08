<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
            ->shopOrders(); // Only show shop orders

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_email', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment verification filter
        if ($request->filled('payment_verified')) {
            $verified = $request->payment_verified === 'verified';
            $query->where('is_payment_verified', $verified);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        // Calculate statistics for shop orders only
        $stats = [
            'total_orders' => Order::shopOrders()->count(),
            'pending_orders' => Order::shopOrders()->where('status', 'pending')->count(),
            'processing_orders' => Order::shopOrders()->where('status', 'processing')->count(),
            'completed_orders' => Order::shopOrders()->where('status', 'completed')->count(),
            'total_revenue' => Order::shopOrders()->where('status', 'completed')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Verify payment.
     */
    public function verifyPayment(Order $order)
    {
        $order->update(['is_payment_verified' => true]);

        return redirect()->back()->with('success', 'Payment verified successfully!');
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }
}
