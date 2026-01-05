@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="javascript:void(0)" onclick="showSection('dashboard')" class="menu-item active">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-boxes"></i>
            <span>Wholesale + Product</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('customers')" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Customers</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Vendor Management</div>
        <a href="#" class="menu-item">
            <i class="fas fa-wallet"></i>
            <span>Wallet Requests</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-store"></i>
            <span>Sellers</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('applications')" class="menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
            @if($stats['pending_applications'] > 0)
                <span class="badge">{{ $stats['pending_applications'] }}</span>
            @endif
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-award"></i>
            <span>Badges</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-percent"></i>
            <span>Commission</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Operations</div>
        <a href="#" class="menu-item">
            <i class="fas fa-truck"></i>
            <span>Delivery Mens</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-shipping-fast"></i>
            <span>Shipping</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-undo"></i>
            <span>Refund</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-id-card"></i>
            <span>KYC Verification</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-shield-alt"></i>
            <span>Fraud Detection</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Finance</div>
        <a href="#" class="menu-item">
            <i class="fas fa-credit-card"></i>
            <span>Payment Gateway</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-money-bill-wave"></i>
            <span>Advance Payments</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-cash-register"></i>
            <span>Offline Payment</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-file-invoice"></i>
            <span>Invoices</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Content</div>
        <a href="#" class="menu-item">
            <i class="fas fa-images"></i>
            <span>Media Library</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Pages</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-blog"></i>
            <span>Blog</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-bullhorn"></i>
            <span>Marketing</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Communication</div>
        <a href="#" class="menu-item">
            <i class="fas fa-headset"></i>
            <span>Support</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-comments"></i>
            <span>Chat Messenger</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-sms"></i>
            <span>OTP System</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">System</div>
        <a href="#" class="menu-item">
            <i class="fas fa-store-alt"></i>
            <span>Store Front</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-cog"></i>
            <span>System Setup</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-user-shield"></i>
            <span>Manage Staffs</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-mobile-alt"></i>
            <span>Mobile App</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-puzzle-piece"></i>
            <span>Addons</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-sync-alt"></i>
            <span>System Update</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Account</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<!-- Dashboard Section -->
<div id="dashboard-section" class="content-section">
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
</div>

<!-- Customers Section -->
<div id="customers-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Customers Management</h2>
        <p style="color: #7f8c8d;">Manage all platform users and customers</p>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50;">All Users</h3>
            <a href="{{ route('admin.users.create') }}" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 5px; text-decoration: none;">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Name</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Email</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Role</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Joined</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">{{ $user->name }}</td>
                        <td style="padding: 12px;">{{ $user->email }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: {{ $user->status == 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ $user->status == 'active' ? '#155724' : '#721c24' }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <a href="{{ route('admin.users.edit', $user) }}" style="padding: 6px 12px; background: #3498db; color: white; border-radius: 4px; text-decoration: none; font-size: 13px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Applications Section -->
<div id="applications-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Vendor Applications</h2>
        <p style="color: #7f8c8d;">Review and manage vendor role applications</p>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">All Applications</h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Applicant</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Requested Role</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Business Name</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Applied Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div>
                                <strong>{{ $application->user->name }}</strong><br>
                                <small style="color: #7f8c8d;">{{ $application->user->email }}</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #f3e5f5; color: #7b1fa2; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($application->requested_role) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $application->business_name }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: {{ $application->status == 'pending' ? '#fff3cd' : ($application->status == 'approved' ? '#d4edda' : '#f8d7da') }}; color: {{ $application->status == 'pending' ? '#856404' : ($application->status == 'approved' ? '#155724' : '#721c24') }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $application->created_at->format('M d, Y') }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <a href="{{ route('admin.applications.show', $application) }}" style="padding: 6px 12px; background: #3498db; color: white; border-radius: 4px; text-decoration: none; font-size: 13px;">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showSection(section) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(el => {
        el.style.display = 'none';
    });
    
    // Remove active class from all menu items
    document.querySelectorAll('.menu-item').forEach(el => {
        el.classList.remove('active');
    });
    
    // Show selected section
    if (section === 'dashboard') {
        document.getElementById('dashboard-section').style.display = 'block';
        document.querySelector('a[href="{{ route('admin.dashboard') }}"]').classList.add('active');
    } else if (section === 'customers') {
        document.getElementById('customers-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    } else if (section === 'applications') {
        document.getElementById('applications-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    }
}
</script>

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
