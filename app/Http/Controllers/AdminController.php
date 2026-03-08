<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleApplication;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\RetailPageContent;
use App\Models\AboutPageContent;
use App\Models\ContactPageContent;
use App\Models\HomePageContent;
use App\Models\WholesalePageContent;
use App\Models\ImportPageContent;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show analytics and reports page
     */
    public function analytics()
    {
        $totalUsers = \App\Models\User::count();
        $totalOrders = \App\Models\Order::count();
        $totalSales = \App\Models\Order::where('status', 'delivered')->sum('total');
        $activeVendors = \App\Models\User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])->where('status', 'active')->count();
        $recentOrders = \App\Models\Order::with('user')->latest()->take(10)->get();
        return view('admin.analytics', compact('totalUsers', 'totalOrders', 'totalSales', 'activeVendors', 'recentOrders'));
    }
    /**
     * Show admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'retailers' => User::where('role', 'retailer')->count(),
            'wholesalers' => User::where('role', 'wholesaler')->count(),
            'exporters' => User::where('role', 'exporter')->count(),
            'pending_applications' => RoleApplication::pending()->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
            'total_sales' => Order::where('status', 'delivered')->sum('total'),
            'total_orders' => Order::count(),
        ];

        $recentApplications = RoleApplication::with('user')
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        $users = User::latest()->get();
        $applications = RoleApplication::with('user')->latest()->get();

        // Fetch categories and products for dashboard
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        $products = Product::with(['category', 'vendor'])->latest()->get();
        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])->get();
        $retailers = User::where('role', 'retailer')->orderBy('name')->get();
        $wholesalers = User::where('role', 'wholesaler')->orderBy('name')->get();
        $importers = User::where('role', 'importer')->orderBy('name')->get();
        // Recent orders to display on admin dashboard (shows immediately after any user places an order)
        $orders = Order::with(['user', 'vendor', 'items.product'])->latest()->paginate(20);
        $brands = Brand::withCount('products')->orderBy('sort_order')->get();

        // Fetch retail page content
        $retailPageContent = RetailPageContent::getAllContent();

        // Fetch about page content
        $aboutPageContent = AboutPageContent::getAllContent();

        // Fetch contact page content
        $contactPageContent = ContactPageContent::getAllContent();

        // Fetch home page content
        $homePageContent = HomePageContent::getAllContent();

        // Fetch wholesale page content
        $wholesalePageContent = WholesalePageContent::getAllContent();

        // Fetch import page content
        $importPageContent = ImportPageContent::getAllContent();

        return view('dashboards.admin', compact(
            'stats',
            'recentApplications',
            'users',
            'applications',
            'categories',
            'products',
            'vendors',
            'orders',
            'retailers',
            'wholesalers',
            'importers',
            'brands',
            'retailPageContent',
            'aboutPageContent',
            'contactPageContent',
            'homePageContent',
            'wholesalePageContent',
            'importPageContent'
        ));
    }

    /**
     * Show all users
     */
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,retailer,wholesaler,exporter,admin',
            'dashboard_modules' => 'nullable|array',
            'dashboard_modules.*' => 'in:orders,wishlist,profile,notifications,chat,wallet,coupons',
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'dashboard_modules' => $validated['dashboard_modules'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,retailer,wholesaler,exporter,admin',
            'dashboard_modules' => 'nullable|array',
            'dashboard_modules.*' => 'in:orders,wishlist,profile,notifications,chat,wallet,coupons',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Quick update for admin dashboard — change role and/or reset password for a user.
     */
    public function quickUpdateUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|in:user,retailer,wholesaler,exporter,admin',
            'password' => 'nullable|string|min:8|confirmed',
            'dashboard_modules' => 'nullable|array',
            'dashboard_modules.*' => 'in:orders,wishlist,profile,notifications,chat,wallet,coupons',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (!empty($validated['role'])) {
            $user->role = $validated['role'];
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        if (array_key_exists('dashboard_modules', $validated)) {
            $user->dashboard_modules = $validated['dashboard_modules'] ?? null;
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Show all orders
     */
    public function orders()
    {
        $query = Order::with(['user', 'vendor', 'items.product'])->latest();

        // Server-side filters (optional) — allow filtering by vendor role or specific vendor id
        if (request()->filled('vendor_role')) {
            $role = request('vendor_role');
            $query->whereHas('vendor', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }
        if (request()->filled('vendor_id')) {
            $query->where('vendor_id', request('vendor_id'));
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function showOrder(Order $order)
    {
        $order->load(['user', 'vendor', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
