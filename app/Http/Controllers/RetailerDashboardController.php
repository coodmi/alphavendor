<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\VendorWallet;

class RetailerDashboardController extends Controller
{
    /**
     * Show retailer dashboard
     */
    public function index()
    {
        $vendorId = Auth::id();

        // Get or create wallet
        $wallet = VendorWallet::firstOrCreate(
            ['vendor_id' => $vendorId],
            ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Get statistics
        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $totalOrders = Order::where('vendor_id', $vendorId)->count();
        $pendingOrders = Order::where('vendor_id', $vendorId)->where('status', 'pending')->count();

        // Get orders by status
        $ordersByStatus = Order::where('vendor_id', $vendorId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Sales statistics
        $thisMonthSales = Order::where('vendor_id', $vendorId)
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('vendor_earning');

        $lastMonthSales = Order::where('vendor_id', $vendorId)
            ->whereMonth('created_at', date('m', strtotime('-1 month')))
            ->whereYear('created_at', date('Y', strtotime('-1 month')))
            ->sum('vendor_earning');

        $avgOrderValue = $totalOrders > 0
            ? Order::where('vendor_id', $vendorId)->avg('vendor_earning')
            : 0;

        // Recent orders
        $recentOrders = Order::where('vendor_id', $vendorId)
            ->with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.retailer', compact(
            'wallet',
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'recentOrders',
            'ordersByStatus',
            'thisMonthSales',
            'lastMonthSales',
            'avgOrderValue'
        ));
    }

    /**
     * Show all orders for the retailer
     */
    public function orders()
    {
        $vendorId = Auth::id();

        $orders = Order::where('vendor_id', $vendorId)
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('retailer.orders.index', compact('orders'));
    }

    /**
     * Show a specific order for the retailer
     */
    public function showOrder(Order $order)
    {
        $vendorId = Auth::id();

        // Make sure retailer can only view their own orders
        if ($order->vendor_id !== $vendorId) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'items.product']);

        return view('retailer.orders.show', compact('order'));
    }

    /**
     * Update order status — Retailer can ONLY mark as Shipped
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $vendorId = Auth::id();

        if ($order->vendor_id !== $vendorId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:shipped',
        ]);

        // Retailer can only ship from: order_confirmed or processing
        if (!in_array($order->status, ['order_confirmed', 'processing'])) {
            return redirect()->back()->with('error', 'You can only mark orders as Shipped when they are Order Confirmed or Processing.');
        }

        try {
            app(\App\Services\RetailOrderStatusService::class)
                ->transition($order, 'shipped', Auth::user());
        } catch (\App\Exceptions\UnauthorisedOrderTransitionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\App\Exceptions\InvalidOrderTransitionException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Order marked as Shipped!');
    }}
