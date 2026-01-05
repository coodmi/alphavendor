@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item active">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Management</div>
        <a href="{{ route('admin.users') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="{{ route('admin.applications') }}" class="menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
            @if($stats['pending_applications'] > 0)
                <span class="badge">{{ $stats['pending_applications'] }}</span>
            @endif
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Settings</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; margin-bottom: 30px; align-items: start;">
    <div>
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="color: #7f8c8d;">Here's what's happening with your platform today.</p>
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

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total Users</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏪</div>
            <div class="stat-info">
                <h3>{{ $stats['retailers'] }}</h3>
                <p>Retailers</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>{{ $stats['wholesalers'] }}</h3>
                <p>Wholesalers</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🌍</div>
            <div class="stat-info">
                <h3>{{ $stats['exporters'] }}</h3>
                <p>Exporters</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <h3>{{ $stats['pending_applications'] }}</h3>
                <p>Pending Applications</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-section">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="{{ route('admin.users') }}" class="btn btn-primary">Manage Users</a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add New User</a>
                <a href="{{ route('admin.applications') }}" class="btn btn-warning">View Applications</a>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Recent Role Applications</h2>
            @if($recentApplications->count() > 0)
                <div class="applications-list">
                    @foreach($recentApplications as $application)
                        <div class="application-item">
                            <div class="application-info">
                                <strong>{{ $application->user->name }}</strong>
                                <span class="badge badge-{{ $application->requested_role }}">{{ ucfirst($application->requested_role) }}</span>
                            </div>
                            <div class="application-date">
                                {{ $application->created_at->diffForHumans() }}
                            </div>
                            <div class="application-actions">
                                <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No pending applications.</p>
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

.btn-warning {
    background: #ffc107;
    color: #333;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.applications-list {
    display: grid;
    gap: 15px;
}

.application-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.application-info {
    flex: 1;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    margin-left: 10px;
}

.badge-retailer {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-wholesaler {
    background: #f3e5f5;
    color: #7b1fa2;
}

.badge-exporter {
    background: #e8f5e9;
    color: #388e3c;
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

.btn-warning {
    background: #ffc107;
    color: #333;
}

.btn-warning:hover {
    background: #e0a800;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.applications-list {
    display: grid;
    gap: 15px;
}

.application-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.application-info {
    flex: 1;
}

</style>
@endsection
