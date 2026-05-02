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
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show analytics and reports page
     */
    public function analytics(Request $request)
    {
        // ── Date range ──────────────────────────────────────────────────────
        $from = $request->filled('from')
            ? \Carbon\Carbon::parse($request->from)->startOfDay()
            : now()->startOfDay();

        $to = $request->filled('to')
            ? \Carbon\Carbon::parse($request->to)->endOfDay()
            : now()->endOfDay();

        $today     = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // ── Vendor ID buckets ────────────────────────────────────────────────
        $retailerIds   = User::where('role', 'retailer')->pluck('id');
        $wholesalerIds = User::where('role', 'wholesaler')->pluck('id');
        $importerIds   = User::where('role', 'exporter')->pluck('id');

        // ── Helper: orders in date range ─────────────────────────────────────
        $ordersInRange = fn($ids) => Order::whereIn('vendor_id', $ids)
            ->whereBetween('created_at', [$from, $to]);

        $statusInRange = fn($status) => Order::whereBetween('updated_at', [$from, $to])
            ->where('status', $status);

        // ── SELECTED DATE RANGE STATS ────────────────────────────────────────
        $rangeStats = [
            // Orders by type
            'retailer_orders'   => $ordersInRange($retailerIds)->count(),
            'wholesaler_orders' => $ordersInRange($wholesalerIds)->count(),
            'importer_orders'   => $ordersInRange($importerIds)->count(),
            'total_orders'      => Order::whereBetween('created_at', [$from, $to])->count(),

            // New users
            'new_users'         => User::where('role', 'user')->whereBetween('created_at', [$from, $to])->count(),
            'new_retailers'     => User::where('role', 'retailer')->whereBetween('created_at', [$from, $to])->count(),
            'new_wholesalers'   => User::where('role', 'wholesaler')->whereBetween('created_at', [$from, $to])->count(),
            'new_importers'     => User::where('role', 'exporter')->whereBetween('created_at', [$from, $to])->count(),

            // Order statuses
            'returns'           => $statusInRange('returned')->count(),
            'refunds'           => $statusInRange('refunded')->count(),
            'cancelled'         => $statusInRange('cancelled')->count(),
            'exchange'          => $statusInRange('exchange')->count(),

            // Revenue
            'retailer_revenue'   => $ordersInRange($retailerIds)->whereIn('status', ['delivered','completed'])->sum('total'),
            'wholesaler_revenue' => $ordersInRange($wholesalerIds)->whereIn('status', ['delivered','completed'])->sum('total'),
            'importer_revenue'   => $ordersInRange($importerIds)->whereIn('status', ['delivered','completed'])->sum('total'),
        ];

        // ── TODAY ────────────────────────────────────────────────────────────
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

        // ── YESTERDAY ────────────────────────────────────────────────────────
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

        // ── ALL TIME TOTALS ──────────────────────────────────────────────────
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

        // ── CHART: daily orders for selected range (max 60 days) ─────────────
        $diffDays = min((int) $from->diffInDays($to) + 1, 60);
        $chartLabels = [];
        $chartRetailer = [];
        $chartWholesaler = [];
        $chartImporter = [];

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
        $retailPageContent = RetailPageContent::first() ?? new RetailPageContent();

        // Transactions for the transactions section
        $transactions = \App\Models\Transaction::with(['vendor', 'order'])
            ->latest()
            ->take(50)
            ->get();
        $transactionStats = [
            'total_revenue'  => \App\Models\Transaction::where('status', 'completed')->sum('amount'),
            'completed'      => \App\Models\Transaction::where('status', 'completed')->count(),
            'pending'        => \App\Models\Transaction::where('status', 'pending')->count(),
            'cancelled'      => \App\Models\Transaction::where('status', 'cancelled')->count(),
        ];

        // Fetch about page content
        $aboutPageContent = AboutPageContent::first() ?? new AboutPageContent();

        // Fetch contact page content
        $contactPageContent = ContactPageContent::getContent();

        // Fetch home page content
        $homePageContent = HomePageContent::first() ?? new HomePageContent();

        // Fetch wholesale page content
        $wholesalePageContent = WholesalePageContent::first() ?? new WholesalePageContent();

        // Fetch import page content
        $importPageContent = ImportPageContent::first() ?? new ImportPageContent();

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
            'importPageContent',
            'transactions',
            'transactionStats'
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
            'role' => 'required|in:user,retailer,wholesaler,exporter,employee,admin',
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
            'role' => 'required|in:user,retailer,wholesaler,exporter,employee,admin',
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
     * Reset user password (admin function)
     */
    public function resetUserPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully!'
        ]);
    }


    /**
     * Quick update for admin dashboard — change role and/or reset password for a user.
     */
    public function quickUpdateUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|in:user,retailer,wholesaler,exporter,employee,admin',
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
            'status' => 'required|in:pending_advance_payment,advance_paid,order_confirmed,pending,processing,shipped,delivered,cancelled,refunded,exchange,returned'
        ]);

        $previousStatus = $order->status;
        $order->update($validated);

        // Credit vendor wallet when order is marked delivered
        if ($validated['status'] === 'delivered' && $previousStatus !== 'delivered') {
            $wallet = \App\Models\VendorWallet::firstOrCreate(
                ['vendor_id' => $order->vendor_id],
                ['balance' => 0, 'pending_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
            );

            // Only move from pending if it was previously added there
            if ($wallet->pending_balance >= $order->vendor_earning) {
                $wallet->decrement('pending_balance', $order->vendor_earning);
            }
            $wallet->increment('balance', $order->vendor_earning);
            $wallet->increment('total_earned', $order->vendor_earning);

            \App\Models\Transaction::where('order_id', $order->id)
                ->where('vendor_id', $order->vendor_id)
                ->update(['status' => 'completed']);
        }

        // If cancelled, reverse the pending balance
        if ($validated['status'] === 'cancelled' && $previousStatus !== 'cancelled') {
            $wallet = \App\Models\VendorWallet::where('vendor_id', $order->vendor_id)->first();
            if ($wallet && $wallet->pending_balance >= $order->vendor_earning) {
                $wallet->decrement('pending_balance', $order->vendor_earning);
            }
            \App\Models\Transaction::where('order_id', $order->id)
                ->where('vendor_id', $order->vendor_id)
                ->update(['status' => 'cancelled']);
        }

        \App\Services\NotificationService::orderStatusChanged($order->load('user'));

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid,pending,refunded'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return redirect()->back()->with('success', 'Payment status updated successfully!');
    }

    /**
     * Show user permissions management
     */
    public function userPermissions()
    {
        $users = User::with('roleApplications')->paginate(20);
        $roles = ['user', 'retailer', 'wholesaler', 'exporter', 'importer', 'admin'];
        
        return view('admin.user-permissions.index', compact('users', 'roles'));
    }

    /**
     * Edit user permissions
     */
    public function editUserPermissions(User $user)
    {
        $roles = ['user', 'retailer', 'wholesaler', 'exporter', 'employee', 'importer', 'admin'];
        $permissions = [
            'can_create_products' => 'Create Products',
            'can_edit_products'   => 'Edit Products',
            'can_delete_products' => 'Delete Products',
            'can_manage_orders'   => 'Manage Orders',
            'can_view_analytics'  => 'View Analytics',
            'can_manage_users'    => 'Manage Users',
            'can_access_admin'    => 'Access Admin Panel',
        ];

        return view('admin.user-permissions.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update user permissions
     */
    public function updateUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'role'          => 'required|in:user,retailer,wholesaler,exporter,employee,importer,admin',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $user->role        = $validated['role'];
        // If permissions_submitted is set, save whatever was checked (could be empty array)
        $user->permissions = $request->has('permissions_submitted')
            ? ($validated['permissions'] ?? [])
            : ($user->permissions ?? []);
        $user->save();

        $permCount = count($user->permissions);
        $msg = "Role updated to " . ucfirst($validated['role']);
        if ($permCount > 0) {
            $msg .= " with {$permCount} permission(s)";
        }

        return redirect()->route('admin.user-permissions')
            ->with('success', "{$user->name}: {$msg}.");
    }

    /**
     * Show role settings management
     */
    public function roleSettings()
        {
            // System roles (cannot be edited or deleted)
            $systemRoles = [
                'user' => [
                    'name' => 'User',
                    'description' => 'Regular user with basic access',
                    'permissions' => ['view_products', 'place_orders'],
                    'is_system' => true
                ],
                'retailer' => [
                    'name' => 'Retailer',
                    'description' => 'Can sell products to end customers',
                    'permissions' => ['create_products', 'manage_orders', 'view_analytics'],
                    'is_system' => true
                ],
                'wholesaler' => [
                    'name' => 'Wholesaler',
                    'description' => 'Can sell products in bulk',
                    'permissions' => ['create_products', 'manage_orders', 'view_analytics', 'bulk_pricing'],
                    'is_system' => true
                ],
                'exporter' => [
                    'name' => 'Exporter',
                    'description' => 'Can export products internationally',
                    'permissions' => ['create_products', 'manage_orders', 'view_analytics', 'export_docs'],
                    'is_system' => true
                ],
                'importer' => [
                    'name' => 'Importer',
                    'description' => 'Can import products from other countries',
                    'permissions' => ['create_products', 'manage_orders', 'view_analytics', 'import_docs'],
                    'is_system' => true
                ],
                'admin' => [
                    'name' => 'Administrator',
                    'description' => 'Full system access',
                    'permissions' => ['all_permissions'],
                    'is_system' => true
                ]
            ];

            // Get custom employee roles from database
            $employeeRoles = \App\Models\EmployeeRole::ordered()->get();

            return view('admin.role-settings.index', compact('systemRoles', 'employeeRoles'));
        }

    /**
     * Update role settings
     */
    public function updateRoleSettings(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'string'
        ]);

        // In a real application, you would store this in a database
        // For now, we'll just return success
        return redirect()->route('admin.role-settings')->with('success', 'Role settings updated successfully!');
    }

    /**
     * Store a new employee role (alias for storeEmployeeRole, used by role-settings.create route)
     */
    public function createRole(Request $request)
    {
        return $this->storeEmployeeRole($request);
    }

    /**
     * Delete a role (alias for deleteEmployeeRole, used by role-settings.delete route)
     */
    public function deleteRole(\App\Models\EmployeeRole $role)
    {
        return $this->deleteEmployeeRole($role);
    }

    /**
     * Store a new employee role
     */
    public function storeEmployeeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:employee_roles,name',
            'description' => 'nullable|string|max:500',
            'access_level' => 'required|in:basic,extended,full',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $slug = \Str::slug($validated['name']);

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (\App\Models\EmployeeRole::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $employeeRole = \App\Models\EmployeeRole::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? '',
            'access_level' => $validated['access_level'],
            'permissions' => $validated['permissions'] ?? [],
            'is_active' => true,
            'sort_order' => \App\Models\EmployeeRole::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.role-settings')->with('success', 'Employee role created successfully!');
    }

    /**
     * Update an employee role
     */
    public function updateEmployeeRole(Request $request, \App\Models\EmployeeRole $employeeRole)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:employee_roles,name,' . $employeeRole->id,
            'description' => 'nullable|string|max:500',
            'access_level' => 'required|in:basic,extended,full',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $slug = \Str::slug($validated['name']);

        // Ensure slug is unique (excluding current role)
        $originalSlug = $slug;
        $counter = 1;
        while (\App\Models\EmployeeRole::where('slug', $slug)->where('id', '!=', $employeeRole->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $employeeRole->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? '',
            'access_level' => $validated['access_level'],
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('admin.role-settings')->with('success', 'Employee role updated successfully!');
    }

    /**
     * Delete an employee role
     */
    public function deleteEmployeeRole(\App\Models\EmployeeRole $employeeRole)
    {
        // Check if any employees are using this role
        $employeeCount = User::where('employee_role_id', $employeeRole->id)->count();

        if ($employeeCount > 0) {
            return redirect()->route('admin.role-settings')->with('error', "Cannot delete role. {$employeeCount} employee(s) are currently assigned to this role.");
        }

        $employeeRole->delete();

        return redirect()->route('admin.role-settings')->with('success', 'Employee role deleted successfully!');
    }

    /**
     * Toggle employee role active status
     */
    public function toggleEmployeeRole(\App\Models\EmployeeRole $employeeRole)
    {
        $employeeRole->update([
            'is_active' => !$employeeRole->is_active
        ]);

        $status = $employeeRole->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.role-settings')->with('success', "Employee role {$status} successfully!");
    }


    /**
     * Show user activity logs - real data from DB
     */
    public function userActivity(Request $request)
    {
        $actionFilter = $request->get('action', '');
        $dateFilter   = $request->get('date', '');
        $searchFilter = $request->get('search', '');

        // Build real activity from orders, notifications, and users
        $activities = collect();

        // Orders → "Order Placed"
        $orders = \App\Models\Order::with('user')
            ->latest()
            ->take(100)
            ->get()
            ->map(fn($o) => [
                'id'          => 'order_' . $o->id,
                'user_id'     => $o->user_id,
                'user_name'   => $o->user?->name ?? 'Unknown',
                'user_email'  => $o->user?->email ?? '',
                'action'      => 'order_placed',
                'action_label'=> 'Order Placed',
                'description' => 'Placed order #' . ($o->order_number ?? $o->id) . ' — ৳' . number_format($o->total_amount ?? 0, 2),
                'ip_address'  => '—',
                'created_at'  => $o->created_at,
            ]);

        // New user registrations
        $newUsers = User::latest()->take(50)->get()
            ->map(fn($u) => [
                'id'          => 'user_' . $u->id,
                'user_id'     => $u->id,
                'user_name'   => $u->name,
                'user_email'  => $u->email,
                'action'      => 'user_registered',
                'action_label'=> 'User Registered',
                'description' => 'New ' . ucfirst($u->role) . ' account registered',
                'ip_address'  => '—',
                'created_at'  => $u->created_at,
            ]);

        // Products created
        $products = \App\Models\Product::with('vendor')->latest()->take(50)->get()
            ->map(fn($p) => [
                'id'          => 'product_' . $p->id,
                'user_id'     => $p->vendor_id,
                'user_name'   => $p->vendor?->name ?? 'Unknown',
                'user_email'  => $p->vendor?->email ?? '',
                'action'      => 'product_created',
                'action_label'=> 'Product Created',
                'description' => 'Created product: ' . $p->name,
                'ip_address'  => '—',
                'created_at'  => $p->created_at,
            ]);

        $activities = $activities
            ->merge($orders)
            ->merge($newUsers)
            ->merge($products)
            ->sortByDesc('created_at');

        // Apply action filter
        if ($actionFilter) {
            $activities = $activities->filter(fn($a) => $a['action'] === $actionFilter);
        }

        // Apply date filter
        if ($dateFilter) {
            $activities = $activities->filter(fn($a) =>
                $a['created_at'] && $a['created_at']->format('Y-m-d') === $dateFilter
            );
        }

        // Apply search filter
        if ($searchFilter) {
            $q = strtolower($searchFilter);
            $activities = $activities->filter(fn($a) =>
                str_contains(strtolower($a['user_name']), $q) ||
                str_contains(strtolower($a['description']), $q) ||
                str_contains(strtolower($a['user_email']), $q)
            );
        }

        $activities = $activities->values();

        $stats = [
            'total'        => $activities->count(),
            'last_24h'     => $activities->filter(fn($a) => $a['created_at'] >= now()->subDay())->count(),
            'active_users' => $activities->unique('user_id')->count(),
        ];

        return view('admin.user-activity.index', compact('activities', 'stats', 'actionFilter', 'dateFilter', 'searchFilter'));
    }

    /**
     * Show user activity details
     */
    public function userActivityDetails(User $user)
    {
        // Sample activity data for the specific user
        $activities = collect([
            [
                'action' => 'Login',
                'description' => 'User logged into the system',
                'ip_address' => '192.168.1.1',
                'created_at' => now()->subMinutes(30)
            ],
            [
                'action' => 'Profile Updated',
                'description' => 'Updated profile information',
                'ip_address' => '192.168.1.1',
                'created_at' => now()->subHours(1)
            ]
        ]);

        return view('admin.user-activity.details', compact('user', 'activities'));
    }

    /**
     * Clear activity logs
     */
    public function clearActivityLogs()
    {
        // In a real application, you would clear the activity logs from database
        return redirect()->route('admin.user-activity')->with('success', 'Activity logs cleared successfully!');
    }

    /**
     * Show all employees
     */
    public function employees()
        {
            $employees = User::whereIn('role', ['employee', 'manager', 'supervisor'])
                ->with('employeeRole')
                ->withCount(['orders', 'products'])
                ->latest()
                ->paginate(20);

            return view('admin.employees.index', compact('employees'));
        }

    /**
     * Show create employee form
     */
    public function createEmployee()
        {
            $employeeRoles = \App\Models\EmployeeRole::active()->ordered()->get();
            return view('admin.employees.create', compact('employeeRoles'));
        }

    /**
     * Store new employee
     */
    public function storeEmployee(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'employee_role_id' => 'required|exists:employee_roles,id',
                'status' => 'required|in:active,inactive',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Get the employee role to set the base role
            $employeeRole = \App\Models\EmployeeRole::findOrFail($validated['employee_role_id']);

            // Determine base role based on access level
            $baseRole = match($employeeRole->access_level) {
                'basic' => 'employee',
                'extended' => 'manager',
                'full' => 'supervisor',
                default => 'employee',
            };

            $employee = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $baseRole,
                'employee_role_id' => $validated['employee_role_id'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('admin.employees')->with('success', 'Employee created successfully with role: ' . $employeeRole->name);
        }

    /**
     * Show edit employee form
     */
    public function editEmployee(User $user)
        {
            if (!in_array($user->role, ['employee', 'manager', 'supervisor'])) {
                return redirect()->route('admin.employees')->with('error', 'User is not a staff member!');
            }

            $employeeRoles = \App\Models\EmployeeRole::active()->ordered()->get();
            return view('admin.employees.edit', compact('user', 'employeeRoles'));
        }

    /**
     * Update employee
     */
    public function updateEmployee(Request $request, User $user)
        {
            if (!in_array($user->role, ['employee', 'manager', 'supervisor'])) {
                return redirect()->route('admin.employees')->with('error', 'User is not a staff member!');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8|confirmed',
                'employee_role_id' => 'required|exists:employee_roles,id',
                'status' => 'required|in:active,inactive',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Get the employee role to set the base role
            $employeeRole = \App\Models\EmployeeRole::findOrFail($validated['employee_role_id']);

            // Determine base role based on access level
            $baseRole = match($employeeRole->access_level) {
                'basic' => 'employee',
                'extended' => 'manager',
                'full' => 'supervisor',
                default => 'employee',
            };

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => $baseRole,
                'employee_role_id' => $validated['employee_role_id'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            return redirect()->route('admin.employees')->with('success', 'Employee updated successfully!');
        }

    /**
     * Delete employee
     */
    public function deleteEmployee(User $user)
        {
            if (!in_array($user->role, ['employee', 'manager', 'supervisor'])) {
                return redirect()->route('admin.employees')->with('error', 'User is not a staff member!');
            }

            $user->delete();

            return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully!');
        }

    /**
     * Show employee permissions
     */
    public function employeePermissions()
        {
            $employees = User::whereIn('role', ['employee', 'manager', 'supervisor'])
                ->orderBy('name')
                ->get();

            return view('admin.employees.permissions', compact('employees'));
        }

    /**
     * Update employee permissions
     */
    public function updateEmployeePermissions(Request $request, User $user)
        {
            if (!in_array($user->role, ['employee', 'manager', 'supervisor'])) {
                return response()->json(['error' => 'User is not a staff member!'], 400);
            }

            $validated = $request->validate([
                'permissions' => 'required|array',
                'permissions.*' => 'string',
            ]);

            // Store permissions in user's metadata or a separate permissions table
            $user->update([
                'permissions' => json_encode($validated['permissions'])
            ]);

            return response()->json(['success' => true, 'message' => 'Permissions updated successfully!']);
        }

    /**
     * Show all vendors
     */
    public function vendors()
    {
        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
            ->with('vendorBadge')
            ->withCount(['products', 'orders'])
            ->with(['products' => function($query) {
                $query->latest()->take(5);
            }])
            ->latest()
            ->paginate(20);

        $badges = \App\Models\VendorBadge::active()->ordered()->get();

        $stats = [
            'total_vendors' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])->count(),
            'active_vendors' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])->where('status', 'active')->count(),
            'retailers' => User::where('role', 'retailer')->count(),
            'wholesalers' => User::where('role', 'wholesaler')->count(),
            'exporters' => User::where('role', 'exporter')->count(),
            'importers' => User::where('role', 'importer')->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'stats', 'badges'));
    }

    /**
     * Show create vendor form
     */
    public function createVendor()
    {
        $badges = \App\Models\VendorBadge::active()->ordered()->get();
        return view('admin.vendors.create', compact('badges'));
    }

    /**
     * Store a new vendor
     */
    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email',
            'mobile_number'     => 'nullable|string|max:20',
            'password'          => 'required|string|min:8|confirmed',
            'role'              => 'required|in:retailer,wholesaler,exporter',
            'status'            => 'required|in:active,inactive,pending,suspended',
            'vendor_badge_id'   => 'nullable|exists:vendor_badges,id',
            'notes'             => 'nullable|string|max:1000',
            'verification_status' => 'nullable|in:unverified,pending,verified,rejected',
        ]);

        $vendor = User::create([
            'name'                => $validated['name'],
            'email'               => $validated['email'],
            'mobile_number'       => $validated['mobile_number'] ?? null,
            'password'            => Hash::make($validated['password']),
            'role'                => $validated['role'],
            'status'              => $validated['status'],
            'vendor_badge_id'     => $validated['vendor_badge_id'] ?? null,
            'notes'               => $validated['notes'] ?? null,
            'verification_status' => $validated['verification_status'] ?? 'verified',
        ]);

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Vendor "' . $vendor->name . '" created successfully!');
    }

    /**
     * Show vendor details
     */
    public function showVendor(User $user)
    {
        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return redirect()->route('admin.vendors')->with('error', 'User is not a vendor!');
        }

        $user->load(['products', 'orders']);

        return view('admin.vendors.show', compact('user'));
    }

    /**
     * Update vendor status
     */
    public function updateVendorStatus(Request $request, User $user)
    {
        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return response()->json(['error' => 'User is not a vendor!'], 400);
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update(['status' => $validated['status']]);

        return response()->json(['success' => true, 'message' => 'Vendor status updated successfully!']);
    }

    /**
     * Update vendor commission
     */
    public function updateVendorCommission(Request $request, User $user)
    {
        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return response()->json(['error' => 'User is not a vendor!'], 400);
        }

        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $user->update(['commission_rate' => $validated['commission_rate']]);

        return response()->json(['success' => true, 'message' => 'Commission rate updated successfully!']);
    }

    /**
     * Update vendor badge
     */
    public function updateVendorBadge(Request $request, User $user)
    {
        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return response()->json(['error' => 'User is not a vendor!'], 400);
        }

        $validated = $request->validate([
            'vendor_badge_id' => 'nullable|exists:vendor_badges,id',
        ]);

        $user->update(['vendor_badge_id' => $validated['vendor_badge_id']]);

        return response()->json(['success' => true, 'message' => 'Vendor badge updated successfully!']);
    }

    /**
     * Show vendor applications (combines role applications and verification)
     */
    public function vendorApplications()
    {
        // Get all vendors with pending applications or verification
        $applications = User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
            ->where(function($query) {
                $query->where('verification_status', 'pending')
                      ->orWhereHas('roleApplications', function($q) {
                          $q->where('status', 'pending');
                      });
            })
            ->with(['roleApplications' => function($query) {
                $query->latest();
            }, 'verificationDocuments'])
            ->withCount('verificationDocuments')
            ->latest()
            ->paginate(20);

        $stats = [
            'pending_applications' => \App\Models\RoleApplication::pending()->count(),
            'pending_verifications' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where('verification_status', 'pending')->count(),
            'total_pending' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where(function($query) {
                    $query->where('verification_status', 'pending')
                          ->orWhereHas('roleApplications', function($q) {
                              $q->where('status', 'pending');
                          });
                })->count(),
        ];

        return view('admin.vendor-applications.index', compact('applications', 'stats'));
    }

    /**
     * Show vendor application details
     */
    public function showVendorApplication(User $user)
    {
        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return redirect()->route('admin.vendor-applications')->with('error', 'User is not a vendor!');
        }

        $user->load(['roleApplications' => function($query) {
            $query->latest();
        }, 'verificationDocuments']);

        return view('admin.vendor-applications.show', compact('user'));
    }

    /**
     * Approve vendor application
     */
    public function approveVendorApplication(Request $request, User $user)
    {
        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return redirect()->route('admin.vendor-applications')->with('error', 'User is not a vendor!');
        }

        // Update verification status
        $user->update([
            'verification_status' => 'verified',
            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
            'status' => 'active',
        ]);

        // Update role application if exists
        $roleApplication = $user->roleApplications()->where('status', 'pending')->first();
        if ($roleApplication) {
            $roleApplication->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        }

        return redirect()->route('admin.vendor-applications')->with('success', 'Vendor application approved successfully!');
    }

    /**
     * Reject vendor application
     */
    public function rejectVendorApplication(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if (!in_array($user->role, ['retailer', 'wholesaler', 'exporter', 'importer'])) {
            return redirect()->route('admin.vendor-applications')->with('error', 'User is not a vendor!');
        }

        // Update verification status
        $user->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
            'rejection_reason' => $validated['reason'],
        ]);

        // Update role application if exists
        $roleApplication = $user->roleApplications()->where('status', 'pending')->first();
        if ($roleApplication) {
            $roleApplication->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'admin_notes' => $validated['reason'],
            ]);
        }

        return redirect()->route('admin.vendor-applications')->with('success', 'Vendor application rejected.');
    }


    /**
     * Show all coupons
     */
    public function coupons()
    {
        $coupons = Coupon::latest()->paginate(20);

        $stats = [
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::where('is_active', true)->count(),
            'expired_coupons' => Coupon::where('end_date', '<', now())->count(),
            'total_usage' => 0, // Will be implemented later with order integration
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Store new coupon
     */
    public function storeCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        // Normalize dates: start = beginning of day, end = end of day
        if (!empty($validated['start_date'])) {
            $validated['start_date'] = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();
        }
        if (!empty($validated['end_date'])) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['end_date'])->endOfDay();
        }

        Coupon::create($validated);

        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully!');
    }

    /**
     * Update coupon
     */
    public function updateCoupon(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        // Normalize dates: start = beginning of day, end = end of day
        if (!empty($validated['start_date'])) {
            $validated['start_date'] = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();
        }
        if (!empty($validated['end_date'])) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['end_date'])->endOfDay();
        }

        $coupon->update($validated);

        return redirect()->route('admin.coupons')->with('success', 'Coupon updated successfully!');
    }

    /**
     * Delete coupon
     */
    public function deleteCoupon(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons')->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Toggle coupon status
     */
    public function toggleCoupon(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
            'message' => 'Coupon status updated successfully!'
        ]);
    }

    /**
     * Bulk update vendor status
     */
    public function bulkUpdateVendorStatus(Request $request)
    {
        $request->validate([
            'vendor_ids' => 'required|array',
            'vendor_ids.*' => 'exists:users,id',
            'status' => 'required|in:pending,active,inactive,suspended'
        ]);

        $updated = User::whereIn('id', $request->vendor_ids)
            ->whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
            ->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'message' => "{$updated} vendor(s) updated successfully!"
        ]);
    }

    /**
     * Bulk delete vendors
     */
    public function bulkDeleteVendors(Request $request)
    {
        $request->validate([
            'vendor_ids' => 'required|array',
            'vendor_ids.*' => 'exists:users,id'
        ]);

        // Prevent deleting admin users
        $vendors = User::whereIn('id', $request->vendor_ids)
            ->whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
            ->get();

        if ($vendors->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid vendors found to delete'
            ], 400);
        }

        $deleted = 0;
        foreach ($vendors as $vendor) {
            // Delete vendor's products
            $vendor->products()->delete();
            
            // Delete vendor
            $vendor->delete();
            $deleted++;
        }

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
            'message' => "{$deleted} vendor(s) deleted successfully!"
        ]);
    }
}




