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
        <a href="#" class="menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>My Orders</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-heart"></i>
            <span>Wishlist</span>
        </a>
    </div>

    @if(!$pendingApplication)
    <div class="menu-section">
        <div class="menu-section-title">Become a Vendor</div>
        <a href="{{ route('role.apply') }}" class="menu-item">
            <i class="fas fa-rocket"></i>
            <span>Apply for Role</span>
        </a>
    </div>
    @endif

    <div class="menu-section">
        <div class="menu-section-title">Account</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
    <p style="color: #7f8c8d;">Your personal shopping dashboard.</p>
</div>

    @if($pendingApplication)
        <div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <strong>Application Pending:</strong> Your application for <strong>{{ ucfirst($pendingApplication->requested_role) }}</strong> role is under review.
            Submitted on {{ $pendingApplication->created_at->format('M d, Y') }}
        </div>
    @endif

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>0</h3>
                <p>Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">❤️</div>
            <div class="stat-info">
                <h3>0</h3>
                <p>Wishlist</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>$0</h3>
                <p>Total Spent</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        @if(!$pendingApplication)
            <div class="dashboard-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h2 style="color: white;">Become a Vendor!</h2>
                <p>Want to sell on our platform? Apply for a vendor role:</p>
                <div class="vendor-options">
                    <div class="vendor-option">
                        <h3>🏪 Retailer</h3>
                        <p>Sell individual products directly to consumers</p>
                    </div>
                    <div class="vendor-option">
                        <h3>📦 Wholesaler</h3>
                        <p>Sell products in bulk to other businesses</p>
                    </div>
                    <div class="vendor-option">
                        <h3>🌍 Exporter</h3>
                        <p>Sell products internationally across borders</p>
                    </div>
                </div>
                <a href="{{ route('role.apply') }}" class="btn btn-light" style="margin-top: 20px; background: white; color: #667eea;">Apply Now</a>
            </div>
        @endif

        <div class="dashboard-section">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="{{ route('shop') }}" class="btn btn-primary">Browse Products</a>
                <a href="#" class="btn btn-success">View Orders</a>
                <a href="#" class="btn btn-info">My Wishlist</a>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Recent Orders</h2>
            <p>You haven't placed any orders yet. Start shopping now!</p>
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
}

.stat-icon {
    font-size: 40px;
}

.stat-info h3 {
    font-size: 32px;
    margin: 0;
    color: #007bff;
}

.stat-info p {
    margin: 5px 0 0;
    color: #666;
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

.vendor-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.vendor-option {
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.vendor-option h3 {
    margin-bottom: 10px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
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
</style>
@endsection
