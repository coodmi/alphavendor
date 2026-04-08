<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $totalCustomers = User::where('is_admin', false)->count();
        $categories = Category::withCount('products')->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'totalCustomers',
            'categories',
            'recentOrders'
        ));
    }

    public function showLogin()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();

                return redirect()->route('admin.dashboard')->with('success', 'Welcome to Admin Panel!');
            } else {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'You do not have admin privileges.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
