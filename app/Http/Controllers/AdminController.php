<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleApplication;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
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
        $vendors = User::whereIn('role', ['retailer', 'wholesaler', 'exporter'])->get();

        return view('dashboards.admin', compact('stats', 'recentApplications', 'users', 'applications', 'categories', 'products', 'vendors'));
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
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
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
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
