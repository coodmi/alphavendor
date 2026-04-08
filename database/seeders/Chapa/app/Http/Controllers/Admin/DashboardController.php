<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current date ranges
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Basic Statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'out_of_stock' => Product::where('stock', false)->count(),
            'low_stock' => Product::where('stock', true)->count(), // You can add stock quantity later
        ];

        // Revenue Analytics (Last 30 days)
        $revenueData = $this->getRevenueAnalytics();
        
        // Orders Analytics (Last 30 days)
        $ordersData = $this->getOrdersAnalytics();
        
        // Top Selling Products
        $topProducts = $this->getTopSellingProducts();
        
        // Category Performance
        $categoryPerformance = $this->getCategoryPerformance();
        
        // Recent Orders
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(10)
            ->get();
        
        // Monthly Growth
        $monthlyGrowth = $this->getMonthlyGrowth();
        
        // Customer Analytics
        $customerAnalytics = $this->getCustomerAnalytics();
        
        // Product Status Distribution
        $productStatus = [
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'in_stock' => Product::where('stock', true)->count(),
            'out_of_stock' => Product::where('stock', false)->count(),
        ];

        return view('admin.dashboard.index', compact(
            'stats',
            'revenueData',
            'ordersData',
            'topProducts',
            'categoryPerformance',
            'recentOrders',
            'monthlyGrowth',
            'customerAnalytics',
            'productStatus'
        ));
    }

    private function getRevenueAnalytics()
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
            
            $data[] = [
                'date' => $date->format('M d'),
                'revenue' => (float) $revenue,
                'formatted_revenue' => '৳' . number_format($revenue, 0),
            ];
        }
        return $data;
    }

    private function getOrdersAnalytics()
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $orders = Order::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date->format('M d'),
                'orders' => $orders,
            ];
        }
        return $data;
    }

    private function getTopSellingProducts()
    {
        try {
            return Product::select('products.id', 'products.title', 'products.image', 'products.price')
                ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
                ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', function($join) {
                    $join->on('order_items.order_id', '=', 'orders.id')
                         ->where('orders.status', '=', 'completed');
                })
                ->groupBy('products.id', 'products.title', 'products.image', 'products.price')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getCategoryPerformance()
    {
        try {
            return Category::select('categories.id', 'categories.name')
                ->selectRaw('COUNT(DISTINCT products.id) as product_count')
                ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
                ->selectRaw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
                ->leftJoin('products', 'categories.id', '=', 'products.category_id')
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', function($join) {
                    $join->on('order_items.order_id', '=', 'orders.id')
                         ->where('orders.status', '=', 'completed');
                })
                ->whereNull('categories.deleted_at')
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total_revenue', 'desc')
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getMonthlyGrowth()
    {
        $thisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        $lastMonth = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
            
        $growth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
        
        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'growth_percentage' => round($growth, 1),
            'is_positive' => $growth >= 0,
        ];
    }

    private function getCustomerAnalytics()
    {
        try {
            $totalCustomers = User::where('is_admin', false)->count();
            $newCustomersThisMonth = User::where('is_admin', false)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
                
            // Get returning customers (users with more than 1 order)
            $returningCustomers = DB::table('users')
                ->join('orders', 'users.id', '=', 'orders.user_id')
                ->where('users.is_admin', false)
                ->whereNull('users.deleted_at')
                ->groupBy('users.id')
                ->havingRaw('COUNT(orders.id) > 1')
                ->count();

            return [
                'total' => $totalCustomers,
                'new_this_month' => $newCustomersThisMonth,
                'returning' => $returningCustomers,
                'retention_rate' => $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100, 1) : 0,
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'new_this_month' => 0,
                'returning' => 0,
                'retention_rate' => 0,
            ];
        }
    }
}