<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Date filter
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Convert to Carbon instances
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Get seller's products
        $productIds = Product::where('vendor_id', $user->id)->pluck('id');
        
        // Orders query for this seller
        $ordersQuery = Order::whereHas('items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->whereBetween('created_at', [$start, $end]);
        
        // Total Orders
        $totalOrders = (clone $ordersQuery)->count();
        $todayOrders = Order::whereHas('items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->whereDate('created_at', today())->count();
        
        $yesterdayOrders = Order::whereHas('items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->whereDate('created_at', today()->subDay())->count();
        
        // Product Sales
        $productSales = DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->whereIn('order_id', function($query) use ($start, $end) {
                $query->select('id')
                    ->from('orders')
                    ->whereBetween('created_at', [$start, $end]);
            })
            ->sum('quantity');
        
        // Product Wishlist Count
        $productWishlist = Wishlist::whereIn('product_id', $productIds)->count();
        
        // Product Stock
        $totalStock = Product::where('vendor_id', $user->id)->sum('stock_quantity');
        $lowStock = Product::where('vendor_id', $user->id)
            ->where('stock_quantity', '<=', 10)
            ->count();
        
        // Returns & Refunds
        $totalReturns = ReturnRequest::whereHas('order.items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->whereBetween('created_at', [$start, $end])->count();
        
        $todayReturns = ReturnRequest::whereHas('order.items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->whereDate('created_at', today())->count();
        
        // Cancelled Orders
        $totalCancelled = (clone $ordersQuery)->where('status', 'cancelled')->count();
        $todayCancelled = Order::whereHas('items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->where('status', 'cancelled')->whereDate('created_at', today())->count();
        
        // Exchange Orders (assuming exchange is a return type)
        $totalExchange = ReturnRequest::whereHas('order.items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->where('type', 'exchange')
        ->whereBetween('created_at', [$start, $end])
        ->count();
        
        $todayExchange = ReturnRequest::whereHas('order.items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->where('type', 'exchange')->whereDate('created_at', today())->count();
        
        // Orders by Date (for chart)
        $ordersByDate = Order::whereHas('items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })
        ->whereBetween('created_at', [$start, $end])
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        // Orders by Status (for pie chart)
        $ordersByStatus = Order::whereHas('items', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })
        ->whereBetween('created_at', [$start, $end])
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();
        
        // Top Selling Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('order_items.product_id', $productIds)
            ->whereIn('order_items.order_id', function($query) use ($start, $end) {
                $query->select('id')
                    ->from('orders')
                    ->whereBetween('created_at', [$start, $end]);
            })
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
        
        return view('retailer.reports.index', compact(
            'totalOrders',
            'todayOrders',
            'yesterdayOrders',
            'productSales',
            'productWishlist',
            'totalStock',
            'lowStock',
            'totalReturns',
            'todayReturns',
            'totalCancelled',
            'todayCancelled',
            'totalExchange',
            'todayExchange',
            'ordersByDate',
            'ordersByStatus',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }
}
