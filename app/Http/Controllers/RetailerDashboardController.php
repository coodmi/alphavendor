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
}
