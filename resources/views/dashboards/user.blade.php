@extends('layouts.dashboard')

@section('title', 'User Dashboard')
@section('page-title', 'User Dashboard')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('user.dashboard') }}" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Shopping</div>
        <a href="{{ route('shop') }}" class="menu-item">
            <i class="fas fa-shopping-bag"></i>
            <span>Browse Products</span>
        </a>
        <a href="{{ route('orders.my-orders') }}" class="menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>My Orders</span>
            @if($totalOrders > 0)
                <span class="badge">{{ $totalOrders }}</span>
            @endif
        </a>
        <a href="{{ route('wishlist.index') }}" class="menu-item">
            <i class="fas fa-heart"></i>
            <span>Wishlist</span>
            @if($wishlistCount > 0)
                <span class="badge">{{ $wishlistCount }}</span>
            @endif
        </a>
        <a href="{{ route('customer.returns.index') }}" class="menu-item">
            <i class="fas fa-undo"></i>
            <span>Returns & Refunds</span>
            @if($pendingReturns > 0)
                <span class="badge">{{ $pendingReturns }}</span>
            @endif
        </a>
        <a href="{{ route('cart.index') }}" class="menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Shopping Cart</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Account</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>My Profile</span>
        </a>
        <a href="{{ route('vendor.tickets.index') }}" class="menu-item">
            <i class="fas fa-ticket-alt"></i>
            <span>Support Tickets</span>
        </a>
    </div>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; margin-bottom: 30px; align-items: start;">
    <div>
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="color: #7f8c8d;">Manage your orders, returns, and account settings.</p>
    </div>

    <!-- Profile Card -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 250px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile"
                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #3498db;">
            @else
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 style="margin: 0 0 5px 0; color: #2c3e50; font-size: 16px;">{{ Auth::user()->name }}</h3>
                <p style="margin: 0; color: #7f8c8d; font-size: 13px;">{{ ucfirst(Auth::user()->role) }}</p>
                <a href="{{ route('profile.show') }}" style="font-size: 12px; color: #3498db; text-decoration: none;">Edit Profile</a>
            </div>
        </div>
    </div>
</div>



<!-- Statistics Cards -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalOrders }}</h3>
            <p>Total Orders</p>
            <a href="{{ route('orders.my-orders') }}" style="font-size: 12px; color: #667eea; text-decoration: none;">View All →</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-heart"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $wishlistCount }}</h3>
            <p>Wishlist Items</p>
            <a href="{{ route('wishlist.index') }}" style="font-size: 12px; color: #f5576c; text-decoration: none;">View All →</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="fas fa-undo"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $pendingReturns }}</h3>
            <p>Pending Returns</p>
            <a href="{{ route('customer.returns.index') }}" style="font-size: 12px; color: #fa709a; text-decoration: none;">View All →</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <h3>${{ number_format($totalSpent, 2) }}</h3>
            <p>Total Spent</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-section" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h2 style="margin-bottom: 20px; color: #2c3e50; font-size: 20px;">
        <i class="fas fa-bolt" style="color: #0d5c63;"></i> Quick Actions
    </h2>
    <div class="action-buttons">
        <a href="{{ route('shop') }}" class="btn btn-primary">
            <i class="fas fa-shopping-bag"></i> Browse Products
        </a>
        <a href="{{ route('orders.my-orders') }}" class="btn btn-success">
            <i class="fas fa-list"></i> View Orders
        </a>
        <a href="{{ route('wishlist.index') }}" class="btn btn-danger">
            <i class="fas fa-heart"></i> My Wishlist
        </a>
        <a href="{{ route('customer.returns.index') }}" class="btn btn-warning">
            <i class="fas fa-undo"></i> Returns & Refunds
        </a>
        <a href="{{ route('cart.index') }}" class="btn btn-info">
            <i class="fas fa-shopping-cart"></i> Shopping Cart
        </a>
    </div>
</div>

<!-- Order Tracking Section -->
<div class="dashboard-section" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h2 style="margin-bottom: 20px; color: #2c3e50; font-size: 20px;">
        <i class="fas fa-map-marker-alt" style="color: #0d5c63;"></i> Track Your Order
    </h2>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
        <form action="{{ route('user.dashboard') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 14px;">Enter Order Number</label>
                <input type="text" name="order_number" placeholder="e.g., ORD-1234567890-1234" required
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; white-space: nowrap;">
                <i class="fas fa-search"></i> Track Order
            </button>
        </form>
    </div>

    @if(isset($trackingOrder))
        <div style="margin-top: 30px; padding: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                <div>
                    <h3 style="margin: 0 0 5px 0; font-size: 18px;">Order #{{ $trackingOrder->order_number }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Placed on {{ $trackingOrder->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div style="text-align: right;">
                    <span style="display: inline-block; padding: 6px 16px; background: rgba(255,255,255,0.2); border-radius: 20px; font-size: 13px; font-weight: 600;">
                        {{ ucfirst($trackingOrder->status) }}
                    </span>
                </div>
            </div>

            <!-- Order Progress Timeline -->
            <div style="position: relative; padding: 30px 0;">
                @php
                    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentIndex = array_search($trackingOrder->status, $statuses);
                    if ($currentIndex === false) $currentIndex = 0;
                @endphp

                <!-- Progress Line -->
                <div style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: rgba(255,255,255,0.3); transform: translateY(-50%);"></div>
                <div style="position: absolute; top: 50%; left: 0; height: 4px; background: white; transform: translateY(-50%); width: {{ ($currentIndex / (count($statuses) - 1)) * 100 }}%; transition: width 0.5s;"></div>

                <!-- Status Points -->
                <div style="display: flex; justify-content: space-between; position: relative;">
                    @foreach($statuses as $index => $status)
                        <div style="text-align: center; flex: 1;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;
                                {{ $index <= $currentIndex ? 'background: white; color: #667eea;' : 'background: rgba(255,255,255,0.3); color: white;' }}">
                                @if($index < $currentIndex)
                                    <i class="fas fa-check"></i>
                                @elseif($index == $currentIndex)
                                    <i class="fas fa-circle" style="font-size: 12px;"></i>
                                @else
                                    <i class="fas fa-circle" style="font-size: 8px; opacity: 0.5;"></i>
                                @endif
                            </div>
                            <p style="margin: 0; font-size: 12px; font-weight: 600; {{ $index <= $currentIndex ? 'opacity: 1;' : 'opacity: 0.6;' }}">
                                {{ ucfirst($status) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Details -->
            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div>
                        <p style="margin: 0 0 5px 0; opacity: 0.8; font-size: 12px;">Total Amount</p>
                        <p style="margin: 0; font-size: 20px; font-weight: 700;">${{ number_format($trackingOrder->total, 2) }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; opacity: 0.8; font-size: 12px;">Items</p>
                        <p style="margin: 0; font-size: 20px; font-weight: 700;">{{ $trackingOrder->items->count() }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; opacity: 0.8; font-size: 12px;">Payment Method</p>
                        <p style="margin: 0; font-size: 16px; font-weight: 600;">{{ strtoupper($trackingOrder->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <a href="{{ route('orders.show', $trackingOrder->id) }}" style="display: inline-block; padding: 10px 24px; background: white; color: #667eea; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
                    View Full Details <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Recent Orders -->
<div class="dashboard-section" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #2c3e50; font-size: 20px;">
            <i class="fas fa-clock" style="color: #0d5c63;"></i> Recent Orders
        </h2>
        @if($recentOrders->count() > 0)
            <a href="{{ route('orders.my-orders') }}" style="color: #667eea; text-decoration: none; font-size: 14px;">View All →</a>
        @endif
    </div>

    @if($recentOrders->count() > 0)
        <div class="orders-table">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #6c757d; font-weight: 600;">Order ID</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #6c757d; font-weight: 600;">Date</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #6c757d; font-weight: 600;">Items</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #6c757d; font-weight: 600;">Total</th>
                        <th style="padding: 12px; text-align: left; font-size: 13px; color: #6c757d; font-weight: 600;">Status</th>
                        <th style="padding: 12px; text-align: center; font-size: 13px; color: #6c757d; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; font-size: 14px; color: #2c3e50; font-weight: 600;">#{{ $order->order_number }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #6c757d;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="padding: 12px; font-size: 14px; color: #6c757d;">{{ $order->items->count() }} item(s)</td>
                            <td style="padding: 12px; font-size: 14px; color: #2c3e50; font-weight: 600;">${{ number_format($order->total, 2) }}</td>
                            <td style="padding: 12px;">
                                @php
                                    $statusColors = [
                                        'pending' => ['bg' => '#fff3cd', 'text' => '#856404'],
                                        'processing' => ['bg' => '#cfe2ff', 'text' => '#084298'],
                                        'shipped' => ['bg' => '#d1e7dd', 'text' => '#0f5132'],
                                        'delivered' => ['bg' => '#d1e7dd', 'text' => '#0a3622'],
                                        'cancelled' => ['bg' => '#f8d7da', 'text' => '#842029'],
                                    ];
                                    $color = $statusColors[$order->status] ?? ['bg' => '#e2e3e5', 'text' => '#41464b'];
                                @endphp
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-sm btn-primary" style="padding: 6px 12px; font-size: 12px; text-decoration: none; border-radius: 4px; background: #667eea; color: white; display: inline-block;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px 20px;">
            <i class="fas fa-shopping-bag" style="font-size: 48px; color: #dee2e6; margin-bottom: 15px;"></i>
            <p style="color: #6c757d; margin-bottom: 20px;">You haven't placed any orders yet. Start shopping now!</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Browse Products
            </a>
        </div>
    @endif
</div>

<style>
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-info h3 {
    font-size: 32px;
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-weight: 700;
}

.stat-info p {
    margin: 0;
    color: #7f8c8d;
    font-size: 14px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(250, 112, 154, 0.4);
}

.btn-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
}

.orders-table {
    overflow-x: auto;
}

@media (max-width: 768px) {
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
