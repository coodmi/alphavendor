@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<!-- Success Message Toast -->
@if(session('success'))
<div id="successToast" style="position: fixed; top: 20px; right: 20px; background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
<script>
    setTimeout(function() {
        document.getElementById('successToast').style.display = 'none';
    }, 5000);
</script>
@endif

<div class="content-area">
    <!-- Dashboard Content -->
    <div id="dashboard-content" class="section-content active">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_orders'] ?? 0 }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_products'] ?? 0 }}</h3>
                    <p>Total Products</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3> {{ currency($stats['total_revenue'] ?? 0) }}</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <a href="{{ route('admin.categories') }}" class="action-btn">
                    <i class="fas fa-tags"></i>
                    Manage Categories
                </a>
                <a href="{{ route('admin.products') }}" class="action-btn">
                    <i class="fas fa-box"></i>
                    Manage Products
                </a>
                <a href="{{ route('admin.orders') }}" class="action-btn">
                    <i class="fas fa-shopping-cart"></i>
                    View Orders
                </a>
                <a href="{{ route('admin.users') }}" class="action-btn">
                    <i class="fas fa-users"></i>
                    Manage Users
                </a>
            </div>
        </div>

        <!-- Recent Applications -->
        @if(isset($recentApplications) && $recentApplications->count() > 0)
        <div class="recent-applications">
            <h3>Recent Applications</h3>
            <div class="applications-list">
                @foreach($recentApplications as $application)
                <div class="application-item">
                    <div class="application-info">
                        <h4>{{ $application->user->name }}</h4>
                        <p>{{ ucfirst($application->role) }} Application</p>
                        <span class="application-date">{{ $application->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="application-status">
                        <span class="status-badge status-{{ $application->status }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Categories Section -->
    <div id="categories-content" class="section-content" style="display: none;">
        <div class="section-header">
            <h2>Categories Management</h2>
            <p>Manage product categories</p>
        </div>
        <div class="redirect-message">
            <p>Redirecting to Categories Management...</p>
            <script>
                setTimeout(() => {
                    window.location.href = '{{ route('admin.categories') }}';
                }, 1000);
            </script>
        </div>
    </div>

    <!-- Products Section -->
    <div id="products-content" class="section-content" style="display: none;">
        <div class="section-header">
            <h2>Products Management</h2>
            <p>Manage all products</p>
        </div>
        <div class="redirect-message">
            <p>Redirecting to Products Management...</p>
            <script>
                setTimeout(() => {
                    window.location.href = '{{ route('admin.products') }}';
                }, 1000);
            </script>
        </div>
    </div>

    <!-- Orders Section -->
    <div id="orders-content" class="section-content" style="display: none;">
        <div class="section-header">
            <h2>Orders Management</h2>
            <p>View and manage orders</p>
        </div>
        <div class="redirect-message">
            <p>Redirecting to Orders Management...</p>
            <script>
                setTimeout(() => {
                    window.location.href = '{{ route('admin.orders') }}';
                }, 1000);
            </script>
        </div>
    </div>

    <!-- Vendors Section -->
    <div id="vendors-content" class="section-content" style="display: none;">
        <div class="section-header">
            <h2>Vendors Management</h2>
            <p>Manage all vendors</p>
        </div>
        <div class="redirect-message">
            <p>Redirecting to User Management...</p>
            <script>
                setTimeout(() => {
                    window.location.href = '{{ route('admin.users') }}';
                }, 1000);
            </script>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-info h3 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 5px 0;
}

.stat-info p {
    color: #7f8c8d;
    margin: 0;
    font-size: 14px;
}

.quick-actions {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.quick-actions h3 {
    margin: 0 0 20px 0;
    color: #2c3e50;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.recent-applications {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.recent-applications h3 {
    margin: 0 0 20px 0;
    color: #2c3e50;
}

.applications-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.application-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.application-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.application-info p {
    margin: 0 0 5px 0;
    color: #7f8c8d;
    font-size: 14px;
}

.application-date {
    font-size: 12px;
    color: #95a5a6;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.section-content {
    display: none;
}

.section-content.active {
    display: block;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h2 {
    color: #2c3e50;
    margin: 0 0 5px 0;
}

.section-header p {
    color: #7f8c8d;
    margin: 0;
}

.redirect-message {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.redirect-message p {
    color: #667eea;
    font-size: 16px;
    font-weight: 500;
}
</style>
@endsection