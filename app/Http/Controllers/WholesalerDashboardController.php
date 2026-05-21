<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\VendorWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WholesalerDashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();

        $wallet = VendorWallet::firstOrCreate(
            ['vendor_id' => $vendorId],
            ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        $totalProducts = Product::where('vendor_id', $vendorId)->count();
        $totalOrders = Order::where('vendor_id', $vendorId)->count();
        $pendingOrders = Order::where('vendor_id', $vendorId)->where('status', 'pending')->count();

        $ordersByStatus = Order::where('vendor_id', $vendorId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

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

        $recentOrders = Order::where('vendor_id', $vendorId)
            ->with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.wholesaler', compact(
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

    public function orders()
    {
        $orders = Order::where('vendor_id', Auth::id())
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('wholesaler.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order = Order::where('id', $order->id)
            ->where('vendor_id', Auth::id())
            ->with(['user', 'items.product'])
            ->first();

        if (! $order) {
            return redirect()->route('wholesaler.orders')
                ->with('error', 'Order not found or you do not have permission to view it.');
        }

        return view('wholesaler.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if ((int) $order->vendor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:shipped',
        ]);

        if (! in_array($order->status, ['order_confirmed', 'processing', 'advance_paid'])) {
            return redirect()->back()->with('error', 'You can only mark orders as Shipped when they are confirmed or processing.');
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
    }
}
