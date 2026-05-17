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