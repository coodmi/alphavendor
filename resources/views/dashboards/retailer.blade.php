@extends('layouts.dashboard')

@section('title', 'Retailer Dashboard')
@section('page-title', 'Retailer Dashboard')

@section('sidebar-menu')
    @include('dashboards.partials.retailer-sidebar')
@endsection

@section('content')
<!-- Account Approval Alert Banner -->
@if(auth()->user()->status === 'pending')
<div style="background: linear-gradient(135deg, #1a6b73 0%, #d97706 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="font-size: 48px;">⏳</div>
        <div style="flex: 1;">
            <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Account Pending Approval</h3>
            <p style="margin: 0; opacity: 0.95;">Your account is waiting for admin approval. You cannot add products until your account is approved. Please wait for admin to review your application.</p>
        </div>
    </div>
</div>
@elseif(auth()->user()->status === 'suspended')
<div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="font-size: 48px;">🚫</div>
        <div style="flex: 1;">
            <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Account Suspended</h3>
            <p style="margin: 0; opacity: 0.95;">Your account has been suspended. Please contact admin for more information.</p>
        </div>
    </div>
</div>
@elseif(auth()->user()->status === 'inactive')
<div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <div style="display: flex; align-items: center; gap: 16px;">
        <div style="font-size: 48px;">⚠️</div>
        <div style="flex: 1;">
            <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Account Inactive</h3>
            <p style="margin: 0; opacity: 0.95;">Your account is currently inactive. Please contact admin to activate your account.</p>
        </div>
    </div>
</div>
@endif

<!-- Verification Alert Banner -->
@if(auth()->user()->needsVerification())
    @if(auth()->user()->verification_status === 'unverified')
    <div style="background: linear-gradient(135deg, #1a6b73 0%, #d97706 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 48px;">⚠️</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Account Verification Required</h3>
                <p style="margin: 0 0 12px 0; opacity: 0.95;">Your account is not verified. Please upload the required documents to start selling products.</p>
                <a href="{{ route('verification.index') }}" style="background: white; color: #d97706; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                    <i class="fas fa-upload"></i> Upload Documents Now
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>
        </div>
    </div>
    @elseif(auth()->user()->verification_status === 'pending')
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 48px;">⏳</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Verification Under Review</h3>
                <p style="margin: 0; opacity: 0.95;">Your documents have been submitted and are currently being reviewed by our team. You will be notified once your account is verified.</p>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>
        </div>
    </div>
    @elseif(auth()->user()->verification_status === 'rejected')
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 48px;">❌</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Verification Rejected</h3>
                <p style="margin: 0 0 8px 0; opacity: 0.95;">Your verification was rejected. Please review the feedback and resubmit your documents.</p>
                @if(auth()->user()->rejection_reason)
                <p style="margin: 0 0 12px 0; padding: 12px; background: rgba(255,255,255,0.2); border-radius: 8px; font-size: 14px;">
                    <strong>Reason:</strong> {{ auth()->user()->rejection_reason }}
                </p>
                @endif
                <a href="{{ route('verification.index') }}" style="background: white; color: #dc2626; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                    <i class="fas fa-redo"></i> Resubmit Documents
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>
        </div>
    </div>
    @endif
@endif

<div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; margin-bottom: 30px; align-items: start;" class="welcome-header-grid">
    <div>
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="color: #7f8c8d;">Manage your retail business efficiently.</p>
    </div>

    <!-- Profile Card -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 250px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            @if(Auth::user()->profile_image)
                @php $img = Auth::user()->profile_image; @endphp
                <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" alt="Profile"
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

    <div class="dashboard-stats">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>{{ $totalProducts }}</h3>
                <p>Total Products</p>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>{{ $totalOrders }}</h3>
                <p>Total Orders</p>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <h3>{{ $pendingOrders }}</h3>
                <p>Pending Orders</p>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->total_earned) }}</h3>
                <p>Total Earnings</p>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="stat-icon">💵</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->balance) }}</h3>
                <p>Available Balance</p>
            </div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
            <div class="stat-icon">⏸️</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->pending_balance) }}</h3>
                <p>Pending Balance</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-section">
            <h2 style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-bolt" style="color: #f39c12;"></i> Quick Actions
            </h2>
            <div class="action-buttons">
                <a href="{{ route('retailer.products') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add Product
                </a>
                <a href="{{ route('vendor.orders') }}" class="btn btn-success">
                    <i class="fas fa-shopping-cart"></i> View Orders
                </a>
                <a href="{{ route('wallet.index') }}" class="btn btn-warning">
                    <i class="fas fa-wallet"></i> View Wallet
                </a>
                <a href="{{ route('withdrawals.create') }}" class="btn btn-info">
                    <i class="fas fa-money-bill-wave"></i> Request Withdrawal
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <!-- Order Statistics -->
            <div class="dashboard-section">
                <h2 style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-pie" style="color: #3498db;"></i> Order Status
                </h2>
                <div style="display: grid; gap: 15px; margin-top: 20px;">
                    @php
                        $statusStats = [
                            ['label' => 'Pending', 'count' => $ordersByStatus['pending'] ?? 0, 'color' => '#f39c12', 'icon' => '⏳'],
                            ['label' => 'Processing', 'count' => $ordersByStatus['processing'] ?? 0, 'color' => '#3498db', 'icon' => '⚙️'],
                            ['label' => 'Shipped', 'count' => $ordersByStatus['shipped'] ?? 0, 'color' => '#9b59b6', 'icon' => '🚚'],
                            ['label' => 'Delivered', 'count' => $ordersByStatus['delivered'] ?? 0, 'color' => '#27ae60', 'icon' => '✅'],
                            ['label' => 'Cancelled', 'count' => $ordersByStatus['cancelled'] ?? 0, 'color' => '#e74c3c', 'icon' => '❌'],
                        ];
                    @endphp
                    @foreach($statusStats as $stat)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid {{ $stat['color'] }};">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 20px;">{{ $stat['icon'] }}</span>
                                <strong>{{ $stat['label'] }}</strong>
                            </span>
                            <span style="background: {{ $stat['color'] }}; color: white; padding: 4px 12px; border-radius: 20px; font-weight: bold;">
                                {{ $stat['count'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sales Overview -->
            <div class="dashboard-section">
                <h2 style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-line" style="color: #27ae60;"></i> Sales Overview
                </h2>
                <div style="display: grid; gap: 15px; margin-top: 20px;">
                    <div style="padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; color: white;">
                        <div style="font-size: 14px; opacity: 0.9;">This Month Sales</div>
                        <div style="font-size: 28px; font-weight: bold; margin-top: 5px;"> {{ currency($thisMonthSales) }}</div>
                    </div>
                    <div style="padding: 15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; color: white;">
                        <div style="font-size: 14px; opacity: 0.9;">Last Month Sales</div>
                        <div style="font-size: 28px; font-weight: bold; margin-top: 5px;"> {{ currency($lastMonthSales) }}</div>
                    </div>
                    <div style="padding: 15px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 10px; color: white;">
                        <div style="font-size: 14px; opacity: 0.9;">Average Order Value</div>
                        <div style="font-size: 28px; font-weight: bold; margin-top: 5px;"> {{ currency($avgOrderValue) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="dashboard-section" style="margin-top: 20px;">
            <h2 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <i class="fas fa-shopping-bag" style="color: #e74c3c;"></i> Recent Orders
            </h2>
            @if($recentOrders->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; text-align: left;">
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Order #</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Customer</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Items</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Total</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Your Earning</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Status</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 12px;">
                                        <a href="{{ route('retailer.orders.show', $order->id) }}" style="color: #3498db; text-decoration: none; font-weight: 600;">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td style="padding: 12px;">{{ $order->user->name }}</td>
                                    <td style="padding: 12px;">{{ $order->items->count() }} items</td>
                                    <td style="padding: 12px; font-weight: 600;"> {{ currency($order->total) }}</td>
                                    <td style="padding: 12px; color: #27ae60; font-weight: 600;"> {{ currency($order->vendor_earning) }}</td>
                                    <td style="padding: 12px;">
                                        @php
                                            $statusColors = [
                                                'pending' => '#f39c12',
                                                'processing' => '#3498db',
                                                'shipped' => '#9b59b6',
                                                'delivered' => '#27ae60',
                                                'cancelled' => '#e74c3c'
                                            ];
                                        @endphp
                                        <span style="background: {{ $statusColors[$order->status] ?? '#95a5a6' }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; color: #7f8c8d;">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 15px; text-align: center;">
                    <a href="{{ route('vendor.orders') }}" style="color: #3498db; text-decoration: none; font-weight: 600;">
                        View All Orders <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                    <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>No orders yet. Start by adding products to your store!</p>
                    <a href="{{ route('retailer.products') }}" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus-circle"></i> Add Your First Product
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #333;
    margin-bottom: 10px;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    color: white;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.stat-icon {
    font-size: 40px;
}

.stat-info h3 {
    font-size: 32px;
    margin: 0;
    color: white;
    font-weight: 700;
}

.stat-info p {
    margin: 5px 0 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
}

.dashboard-content {
    display: grid;
    gap: 30px;
}

.dashboard-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-section h2 {
    margin-bottom: 20px;
    color: #333;
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

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}

.btn-warning {
    background: #f39c12;
    color: white;
}

.btn-warning:hover {
    background: #e67e22;
}
</style>
@endsection
