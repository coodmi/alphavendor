<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\RoleApplication;
use App\Models\Notification;

class EmployeeDashboardController extends Controller
{
    /**
     * Display the employee dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get employee-specific statistics
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'pending_applications' => RoleApplication::where('status', 'pending')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
            'recent_notifications' => Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        // Get recent activities for employee oversight
        $recentOrders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $recentApplications = RoleApplication::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentProducts = Product::with(['vendor', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('dashboards.employee', compact('stats', 'recentOrders', 'recentApplications', 'recentProducts'));
    }

    /**
     * Display analytics for employees
     */
    public function analytics(Request $request)
    {
        if (!auth()->user()->hasAnyPermission(['analytics.view', 'analytics.export'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view analytics.');
        }

        $from = $request->filled('from')
            ? \Carbon\Carbon::parse($request->from)->startOfDay()
            : now()->startOfDay();

        $to = $request->filled('to')
            ? \Carbon\Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        $today     = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        $retailerIds   = User::where('role', 'retailer')->pluck('id');
        $wholesalerIds = User::where('role', 'wholesaler')->pluck('id');
        $importerIds   = User::where('role', 'exporter')->pluck('id');

        $ordersInRange = fn($ids) => Order::whereIn('vendor_id', $ids)->whereBetween('created_at', [$from, $to]);
        $statusInRange = fn($status) => Order::whereBetween('updated_at', [$from, $to])->where('status', $status);

        $rangeStats = [
            'retailer_orders'    => $ordersInRange($retailerIds)->count(),
            'wholesaler_orders'  => $ordersInRange($wholesalerIds)->count(),
            'importer_orders'    => $ordersInRange($importerIds)->count(),
            'total_orders'       => Order::whereBetween('created_at', [$from, $to])->count(),
            'new_users'          => User::where('role', 'user')->whereBetween('created_at', [$from, $to])->count(),
            'new_retailers'      => User::where('role', 'retailer')->whereBetween('created_at', [$from, $to])->count(),
            'new_wholesalers'    => User::where('role', 'wholesaler')->whereBetween('created_at', [$from, $to])->count(),
            'new_importers'      => User::where('role', 'exporter')->whereBetween('created_at', [$from, $to])->count(),
            'returns'            => $statusInRange('returned')->count(),
            'refunds'            => $statusInRange('refunded')->count(),
            'cancelled'          => $statusInRange('cancelled')->count(),
            'exchange'           => $statusInRange('exchange')->count(),
            'retailer_revenue'   => $ordersInRange($retailerIds)->whereIn('status', ['delivered','completed'])->sum('total'),
            'wholesaler_revenue' => $ordersInRange($wholesalerIds)->whereIn('status', ['delivered','completed'])->sum('total'),
            'importer_revenue'   => $ordersInRange($importerIds)->whereIn('status', ['delivered','completed'])->sum('total'),
        ];

        $todayStats = [
            'retailer_orders'   => Order::whereIn('vendor_id', $retailerIds)->whereDate('created_at', $today)->count(),
            'wholesaler_orders' => Order::whereIn('vendor_id', $wholesalerIds)->whereDate('created_at', $today)->count(),
            'importer_orders'   => Order::whereIn('vendor_id', $importerIds)->whereDate('created_at', $today)->count(),
            'new_users'         => User::where('role', 'user')->whereDate('created_at', $today)->count(),
            'new_retailers'     => User::where('role', 'retailer')->whereDate('created_at', $today)->count(),
            'new_wholesalers'   => User::where('role', 'wholesaler')->whereDate('created_at', $today)->count(),
            'new_importers'     => User::where('role', 'exporter')->whereDate('created_at', $today)->count(),
            'returns'           => Order::whereDate('updated_at', $today)->where('status', 'returned')->count(),
            'refunds'           => Order::whereDate('updated_at', $today)->where('status', 'refunded')->count(),
            'cancelled'         => Order::whereDate('updated_at', $today)->where('status', 'cancelled')->count(),
            'exchange'          => Order::whereDate('updated_at', $today)->where('status', 'exchange')->count(),
        ];

        $yesterdayStats = [
            'retailer_orders'   => Order::whereIn('vendor_id', $retailerIds)->whereDate('created_at', $yesterday)->count(),
            'wholesaler_orders' => Order::whereIn('vendor_id', $wholesalerIds)->whereDate('created_at', $yesterday)->count(),
            'importer_orders'   => Order::whereIn('vendor_id', $importerIds)->whereDate('created_at', $yesterday)->count(),
            'new_users'         => User::where('role', 'user')->whereDate('created_at', $yesterday)->count(),
            'new_retailers'     => User::where('role', 'retailer')->whereDate('created_at', $yesterday)->count(),
            'new_wholesalers'   => User::where('role', 'wholesaler')->whereDate('created_at', $yesterday)->count(),
            'new_importers'     => User::where('role', 'exporter')->whereDate('created_at', $yesterday)->count(),
            'returns'           => Order::whereDate('updated_at', $yesterday)->where('status', 'returned')->count(),
            'refunds'           => Order::whereDate('updated_at', $yesterday)->where('status', 'refunded')->count(),
            'cancelled'         => Order::whereDate('updated_at', $yesterday)->where('status', 'cancelled')->count(),
            'exchange'          => Order::whereDate('updated_at', $yesterday)->where('status', 'exchange')->count(),
        ];

        $allTime = [
            'total_orders'      => Order::count(),
            'total_returns'     => Order::where('status', 'returned')->count(),
            'total_refunds'     => Order::where('status', 'refunded')->count(),
            'total_cancelled'   => Order::where('status', 'cancelled')->count(),
            'total_exchange'    => Order::where('status', 'exchange')->count(),
            'total_retailers'   => User::where('role', 'retailer')->count(),
            'total_wholesalers' => User::where('role', 'wholesaler')->count(),
            'total_importers'   => User::where('role', 'exporter')->count(),
            'total_users'       => User::where('role', 'user')->count(),
        ];

        $diffDays = min((int) $from->diffInDays($to) + 1, 60);
        $chartLabels = $chartRetailer = $chartWholesaler = $chartImporter = [];

        for ($i = $diffDays - 1; $i >= 0; $i--) {
            $day = $to->copy()->subDays($i)->startOfDay();
            $chartLabels[]     = $day->format('M d');
            $chartRetailer[]   = Order::whereIn('vendor_id', $retailerIds)->whereDate('created_at', $day)->count();
            $chartWholesaler[] = Order::whereIn('vendor_id', $wholesalerIds)->whereDate('created_at', $day)->count();
            $chartImporter[]   = Order::whereIn('vendor_id', $importerIds)->whereDate('created_at', $day)->count();
        }

        return view('admin.analytics', compact(
            'rangeStats', 'todayStats', 'yesterdayStats', 'allTime',
            'chartLabels', 'chartRetailer', 'chartWholesaler', 'chartImporter',
            'from', 'to'
        ));
    }

    /**
     * Display products management for employees
     */
    public function products()
    {
        if (!auth()->user()->hasAnyPermission(['products.view', 'products.add', 'products.edit', 'products.delete', 'products.approve'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view products.');
        }

        $products = Product::with(['vendor', 'category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employee.products.index', compact('products'));
    }

    /**
     * Display orders management for employees
     */
    public function orders()
    {
        if (!auth()->user()->hasAnyPermission(['orders.view', 'orders.update_status', 'orders.cancel', 'orders.approve'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view orders.');
        }

        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employee.orders.index', compact('orders'));
    }

    /**
     * Display order details for employees
     */
    public function showOrder(Order $order)
    {
        if (!auth()->user()->hasAnyPermission(['orders.view', 'orders.update_status', 'orders.cancel', 'orders.approve'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view orders.');
        }

        $order->load(['user', 'items.product']);

        return view('employee.orders.show', compact('order'));
    }

    /**
     * Update order status (employee can manage orders)
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        if (!auth()->user()->hasPermission('orders.update_status')) {
            return redirect()->back()->with('error', 'You do not have permission to update order status.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        if ($order->user) {
            Notification::create([
                'user_id' => $order->user->id,
                'title'   => 'Order Status Updated',
                'message' => "Your order #{$order->id} status has been updated to " . ucfirst($request->status),
                'type'    => 'info'
            ]);
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Display role applications for employee review
     */
    public function applications()
    {
        if (!auth()->user()->hasAnyPermission(['user_permissions.view', 'user_permissions.edit'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view applications.');
        }

        $applications = RoleApplication::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employee.applications.index', compact('applications'));
    }

    /**
     * Show application details
     */
    public function showApplication(RoleApplication $application)
    {
        if (!auth()->user()->hasAnyPermission(['user_permissions.view', 'user_permissions.edit'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view applications.');
        }

        $application->load('user');

        return view('employee.applications.show', compact('application'));
    }

    /**
     * Approve role application
     */
    public function approveApplication(RoleApplication $application)
    {
        if (!auth()->user()->hasPermission('user_permissions.edit')) {
            return redirect()->back()->with('error', 'You do not have permission to approve applications.');
        }

        $application->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $application->user->update(['role' => $application->requested_role]);

        Notification::create([
            'user_id' => $application->user_id,
            'title'   => 'Role Application Approved',
            'message' => "Your application for {$application->requested_role} role has been approved!",
            'type'    => 'success'
        ]);

        return redirect()->back()->with('success', 'Application approved successfully.');
    }

    /**
     * Reject role application
     */
    public function rejectApplication(Request $request, RoleApplication $application)
    {
        if (!auth()->user()->hasPermission('user_permissions.edit')) {
            return redirect()->back()->with('error', 'You do not have permission to reject applications.');
        }

        $request->validate(['rejection_reason' => 'nullable|string|max:500']);

        $application->update([
            'status'           => 'rejected',
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::create([
            'user_id' => $application->user_id,
            'title'   => 'Role Application Rejected',
            'message' => "Your application for {$application->requested_role} role has been rejected." .
                         ($request->rejection_reason ? " Reason: {$request->rejection_reason}" : ''),
            'type'    => 'error'
        ]);

        return redirect()->back()->with('success', 'Application rejected.');
    }

    /**
     * Display users management for employees
     */
    public function users()
    {
        if (!auth()->user()->hasAnyPermission(['users.view', 'users.edit', 'users.block', 'users.add'])) {
            return redirect()->route('employee.dashboard')->with('error', 'You do not have permission to view users.');
        }

        $users = User::where('role', '!=', 'admin')
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employee.users.index', compact('users'));
    }

    /**
     * Update user status
     */
    public function updateUserStatus(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission('users.block')) {
            return redirect()->back()->with('error', 'You do not have permission to update user status.');
        }

        if ($user->role === 'admin' || ($user->role === 'employee' && $user->id !== auth()->id())) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate(['status' => 'required|in:active,inactive,pending']);

        $user->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}