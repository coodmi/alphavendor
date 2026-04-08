<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\Wishlist;

class UserDashboardController extends Controller
{
    /**
     * Show user dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $pendingApplication = $user->pendingRoleApplication;

        // Get user statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total');
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get pending returns
        $pendingReturns = ReturnRequest::whereHas('orderItem.order', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        // Get wishlist count
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        // Handle order tracking
        $trackingOrder = null;
        if ($request->has('order_number')) {
            $trackingOrder = Order::where('order_number', $request->order_number)
                ->where('user_id', $user->id)
                ->with(['items.product'])
                ->first();
            
            if (!$trackingOrder) {
                session()->flash('error', 'Order not found or you do not have permission to view it.');
            }
        }

        return view('dashboards.user', compact(
            'pendingApplication',
            'totalOrders',
            'totalSpent',
            'recentOrders',
            'pendingReturns',
            'wishlistCount',
            'trackingOrder'
        ));
    }
}
