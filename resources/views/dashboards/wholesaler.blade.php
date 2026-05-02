@extends('layouts.dashboard')

@section('title', 'Wholesaler Dashboard')
@section('page-title', 'Wholesaler Dashboard')

@section('sidebar-menu')
    @include('dashboards.partials.wholesaler-sidebar')
@endsection

@section('content')
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

<div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; margin-bottom: 30px; align-items: start;">
    <div>
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="color: #7f8c8d;">Manage your wholesale operations from here.</p>
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
            <div class="stat-icon">📦</div>
            <div class="stat-info">
                <h3>{{ $totalProducts }}</h3>
                <p>Bulk Products</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>{{ $totalOrders }}</h3>
                <p>Wholesale Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->total_earned) }}</h3>
                <p>Total Earnings</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💵</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->balance) }}</h3>
                <p>Available Balance</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-section">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="#" class="btn btn-primary">Add Bulk Product</a>
                <a href="#" class="btn btn-success">View Orders</a>
                <a href="#" class="btn btn-info">Manage Inventory</a>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Recent Activity</h2>
            <p>No recent activity yet. Start by adding your first wholesale product!</p>
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
