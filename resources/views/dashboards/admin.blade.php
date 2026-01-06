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
        <a href="javascript:void(0)" onclick="showSection('products')" class="menu-item">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('categories')" class="menu-item">
            <i class="fas fa-tags"></i>
            <span>Category</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('brands')" class="menu-item">
            <i class="fas fa-copyright"></i>
            <span>Brands</span>
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
<!-- Success Message Toast -->
@if(session('success'))
<div id="successToast" style="position: fixed; top: 20px; right: 20px; background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; display: flex; align-items: center; gap: 10px;">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
<script>
    setTimeout(() => {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.style.transition = 'opacity 0.3s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
</script>
@endif

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

<!-- Products Section -->
<div id="products-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Products Management</h2>
                <p style="color: #7f8c8d;">Manage all products in your store</p>
            </div>
            <button onclick="openAddProductModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Image</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Product</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Category</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Vendor</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Price</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Stock</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            @if($product->image)
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 60px; height: 60px; background: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box" style="color: #95a5a6;"></i>
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <strong>{{ $product->name }}</strong><br>
                            <small style="color: #7f8c8d;">SKU: {{ $product->sku }}</small>
                            @if($product->is_featured)
                                <span style="display: inline-block; padding: 2px 8px; background: #ffd700; color: #000; border-radius: 8px; font-size: 11px; margin-left: 5px;">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                {{ $product->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            {{ $product->vendor->name ?? 'N/A' }}
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #27ae60;">${{ number_format($product->price, 2) }}</strong>
                            @if($product->old_price)
                                <br><small style="text-decoration: line-through; color: #95a5a6;">${{ number_format($product->old_price, 2) }}</small>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: {{ $product->stock > 10 ? '#d4edda' : ($product->stock > 0 ? '#fff3cd' : '#f8d7da') }}; color: {{ $product->stock > 10 ? '#155724' : ($product->stock > 0 ? '#856404' : '#721c24') }}; border-radius: 12px; font-size: 12px;">
                                {{ $product->stock }} units
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            @if($product->status === 'active')
                                <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">Active</span>
                            @elseif($product->status === 'out_of_stock')
                                <span style="padding: 4px 12px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 12px;">Out of Stock</span>
                            @else
                                <span style="padding: 4px 12px; background: #d1ecf1; color: #0c5460; border-radius: 12px; font-size: 12px;">Inactive</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick='editProduct(@json($product))' style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="confirmDeleteProduct({{ $product->id }}, '{{ $product->name }}')" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No products found. Click "Add Product" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div id="categories-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Categories Management</h2>
                <p style="color: #7f8c8d;">Manage product categories</p>
            </div>
            <button onclick="openAddCategoryModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Image</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Name</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Products</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Sort Order</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            @if($category->image)
                                <img src="{{ str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="color: #95a5a6;"></i>
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <strong>{{ $category->name }}</strong><br>
                            <small style="color: #7f8c8d;">{{ $category->description ?? 'No description' }}</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                {{ $category->products_count }} Products
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: {{ $category->is_active ? '#d4edda' : '#f8d7da' }}; color: {{ $category->is_active ? '#155724' : '#721c24' }}; border-radius: 12px; font-size: 12px;">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $category->sort_order }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick='editCategory(@json($category))' style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="confirmDeleteCategory({{ $category->id }}, '{{ $category->name }}')" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-tags" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No categories found. Click "Add Category" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Brands Section -->
<div id="brands-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Brands Management</h2>
                <p style="color: #7f8c8d;">Manage product brands</p>
            </div>
            <button onclick="openAddBrandModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Add Brand
            </button>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Logo</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Brand Name</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Products</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Sort Order</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-copyright" style="color: #95a5a6;"></i>
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <strong>{{ $brand->name }}</strong><br>
                            <small style="color: #7f8c8d;">{{ $brand->description ? Str::limit($brand->description, 50) : 'No description' }}</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                {{ $brand->products_count ?? 0 }} Products
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: {{ $brand->is_active ? '#d4edda' : '#f8d7da' }}; color: {{ $brand->is_active ? '#155724' : '#721c24' }}; border-radius: 12px; font-size: 12px;">
                                {{ $brand->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $brand->sort_order }}</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick='editBrand(@json($brand))' style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="confirmDeleteBrand({{ $brand->id }}, '{{ $brand->name }}')" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-copyright" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No brands found. Click "Add Brand" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 25px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #2c3e50;" id="productModalTitle">Add Product</h3>
            <button onclick="closeProductModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="productForm" method="POST" enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            <input type="hidden" name="_method" id="productFormMethod" value="POST">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Product Name *</label>
                    <input type="text" name="name" id="productName" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SKU *</label>
                    <input type="text" name="sku" id="productSku" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Category *</label>
                    <select name="category_id" id="productCategory" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Vendor *</label>
                    <select name="vendor_id" id="productVendor" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Brand</label>
                    <select name="brand_id" id="productBrand" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Price ($) *</label>
                    <input type="number" name="price" id="productPrice" required step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Old Price ($)</label>
                    <input type="number" name="old_price" id="productOldPrice" step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Stock Quantity *</label>
                    <input type="number" name="stock" id="productStock" required min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Status *</label>
                    <select name="status" id="productStatus" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                <textarea name="description" id="productDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Product Image <span id="productImageRequiredLabel">*</span></label>
                <input type="file" name="image" id="productImage" accept="image/*" onchange="previewProductImage(event)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                <div id="productImagePreview" style="display: none; margin-top: 15px; position: relative;">
                    <img id="productPreviewImg" src="" alt="Preview" style="max-width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db;">
                    <button type="button" onclick="cancelProductImage()" style="position: absolute; top: 10px; right: 10px; width: 35px; height: 35px; border-radius: 50%; background: #e74c3c; color: white; border: none; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_featured" id="productFeatured" value="1" style="margin-right: 8px; width: 18px; height: 18px;">
                    <span style="color: #2c3e50;">Featured Product</span>
                </label>

                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Badge (Optional)</label>
                    <input type="text" name="badge" id="productBadge" placeholder="e.g., New, Sale, Hot" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeProductModal()" style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-save"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 25px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #2c3e50;" id="categoryModalTitle">Add Category</h3>
            <button onclick="closeCategoryModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="categoryForm" method="POST" enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            <input type="hidden" name="_method" id="categoryFormMethod" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Category Name *</label>
                <input type="text" name="name" id="categoryName" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                <textarea name="description" id="categoryDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Category Image</label>
                <input type="file" name="image" id="categoryImage" accept="image/*" onchange="previewCategoryImage(event)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                <div id="categoryImagePreview" style="display: none; margin-top: 15px; position: relative;">
                    <img id="categoryPreviewImg" src="" alt="Preview" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db;">
                    <button type="button" onclick="cancelCategoryImage()" style="position: absolute; top: 10px; right: 10px; width: 30px; height: 30px; border-radius: 50%; background: #e74c3c; color: white; border: none; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="categoryStatus" value="1" checked style="margin-right: 8px; width: 18px; height: 18px;">
                    <span style="color: #2c3e50;">Active Category</span>
                </label>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Sort Order</label>
                <input type="number" name="sort_order" id="categorySortOrder" value="0" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeCategoryModal()" style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-save"></i> Save Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Modal -->
<div id="brandModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 25px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #2c3e50;" id="brandModalTitle">Add Brand</h3>
            <button onclick="closeBrandModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="brandForm" method="POST" enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            <input type="hidden" name="_method" id="brandFormMethod" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Brand Name *</label>
                <input type="text" name="name" id="brandName" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                <textarea name="description" id="brandDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Brand Logo</label>
                <input type="file" name="logo" id="brandLogo" accept="image/*" onchange="previewBrandImage(event)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                <div id="brandImagePreview" style="display: none; margin-top: 15px; position: relative;">
                    <img id="brandPreviewImg" src="" alt="Preview" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db;">
                    <button type="button" onclick="cancelBrandImage()" style="position: absolute; top: 10px; right: 10px; width: 30px; height: 30px; border-radius: 50%; background: #e74c3c; color: white; border: none; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Sort Order</label>
                <input type="number" name="sort_order" id="brandSortOrder" value="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="brandStatus" value="1" checked style="margin-right: 8px; width: 18px; height: 18px;">
                    <span style="color: #2c3e50;">Active Brand</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <button type="button" onclick="closeBrandModal()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-save"></i> Save Brand
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center; animation: fadeIn 0.2s;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 440px; padding: 0; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.2); animation: slideDown 0.3s;">
        <!-- Icon Header -->
        <div style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); padding: 30px; border-radius: 12px 12px 0 0;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; backdrop-filter: blur(10px);">
                <i class="fas fa-trash-alt" style="font-size: 36px; color: white;"></i>
            </div>
        </div>

        <!-- Content -->
        <div style="padding: 30px;">
            <h3 style="margin: 0 0 12px 0; color: #2c3e50; font-size: 24px; font-weight: 600;" id="deleteModalTitle">Delete Item?</h3>
            <p style="color: #7f8c8d; margin-bottom: 8px; font-size: 15px;">Are you sure you want to delete</p>
            <p style="color: #2c3e50; margin-bottom: 25px; font-size: 16px; font-weight: 600;">"<span id="deleteItemName"></span>"?</p>
            <p style="color: #e74c3c; font-size: 13px; margin-bottom: 25px; padding: 10px; background: #fee; border-radius: 6px;">
                <i class="fas fa-exclamation-circle"></i> This action cannot be undone
            </p>

            <!-- Action Buttons -->
            <form id="deleteForm" method="POST" style="display: flex; gap: 12px; justify-content: center;">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" style="flex: 1; padding: 12px 24px; background: #ecf0f1; color: #2c3e50; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 500; transition: all 0.3s;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" style="flex: 1; padding: 12px 24px; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; font-weight: 500; transition: all 0.3s; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

#deleteModal button:hover {
    transform: translateY(-2px);
}

#deleteModal button[type="submit"]:hover {
    box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
}

#deleteModal button[type="button"]:hover {
    background: #d5dbdb;
}
</style>

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
        document.querySelector('a[onclick="showSection(\'dashboard\')"]').classList.add('active');
    } else if (section === 'customers') {
        document.getElementById('customers-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    } else if (section === 'applications') {
        document.getElementById('applications-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    } else if (section === 'products') {
        document.getElementById('products-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    } else if (section === 'categories') {
        document.getElementById('categories-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    } else if (section === 'brands') {
        document.getElementById('brands-section').style.display = 'block';
        event.target.closest('.menu-item').classList.add('active');
    }
}

// Product Modal Functions
let isEditModeProduct = false;

function openAddProductModal() {
    isEditModeProduct = false;
    document.getElementById('productModalTitle').textContent = 'Add Product';
    document.getElementById('productForm').action = '{{ route('admin.products.store') }}';
    document.getElementById('productFormMethod').value = 'POST';
    document.getElementById('productForm').reset();
    document.getElementById('productImagePreview').style.display = 'none';
    document.getElementById('productImageRequiredLabel').textContent = '*';
    document.getElementById('productImage').required = true;
    document.getElementById('productModal').style.display = 'flex';
}

function editProduct(product) {
    isEditModeProduct = true;
    document.getElementById('productModalTitle').textContent = 'Edit Product';
    document.getElementById('productForm').action = `/admin/products/${product.id}`;
    document.getElementById('productFormMethod').value = 'PUT';
    document.getElementById('productName').value = product.name;
    document.getElementById('productSku').value = product.sku;
    document.getElementById('productCategory').value = product.category_id;
    document.getElementById('productVendor').value = product.vendor_id;
    document.getElementById('productBrand').value = product.brand_id || '';
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productOldPrice').value = product.old_price || '';
    document.getElementById('productStock').value = product.stock;
    document.getElementById('productStatus').value = product.status;
    document.getElementById('productDescription').value = product.description || '';
    document.getElementById('productFeatured').checked = product.is_featured;
    document.getElementById('productBadge').value = product.badge || '';
    document.getElementById('productImageRequiredLabel').textContent = '';
    document.getElementById('productImage').required = false;

    if (product.image) {
        document.getElementById('productImagePreview').style.display = 'block';
        // Check if image is Unsplash URL or local storage path
        if (product.image.startsWith('http')) {
            document.getElementById('productPreviewImg').src = product.image;
        } else {
            document.getElementById('productPreviewImg').src = `/storage/${product.image}`;
        }
    } else {
        document.getElementById('productImagePreview').style.display = 'none';
    }

    document.getElementById('productModal').style.display = 'flex';
}

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

function previewProductImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2097152) {
            showToast('File size must be less than 2MB', 'error');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('productPreviewImg').src = e.target.result;
            document.getElementById('productImagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function cancelProductImage() {
    document.getElementById('productImage').value = '';
    if (!isEditModeProduct) {
        document.getElementById('productImagePreview').style.display = 'none';
    }
}

function confirmDeleteProduct(id, name) {
    document.getElementById('deleteModalTitle').textContent = 'Delete Product?';
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/products/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}

// Category Modal Functions
let isEditModeCategory = false;

function openAddCategoryModal() {
    isEditModeCategory = false;
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryForm').action = '{{ route('admin.categories.store') }}';
    document.getElementById('categoryFormMethod').value = 'POST';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryImagePreview').style.display = 'none';
    document.getElementById('categoryModal').style.display = 'flex';
}

function editCategory(category) {
    isEditModeCategory = true;
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryForm').action = `/admin/categories/${category.id}`;
    document.getElementById('categoryFormMethod').value = 'PUT';
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryDescription').value = category.description || '';
    document.getElementById('categoryStatus').checked = category.is_active;
    document.getElementById('categorySortOrder').value = category.sort_order;

    if (category.image) {
        document.getElementById('categoryImagePreview').style.display = 'block';
        // Check if image is Unsplash URL or local storage path
        if (category.image.startsWith('http')) {
            document.getElementById('categoryPreviewImg').src = category.image;
        } else {
            document.getElementById('categoryPreviewImg').src = `/storage/${category.image}`;
        }
    } else {
        document.getElementById('categoryImagePreview').style.display = 'none';
    }

    document.getElementById('categoryModal').style.display = 'flex';
}

function closeCategoryModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

function previewCategoryImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2097152) {
            showToast('File size must be less than 2MB', 'error');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('categoryPreviewImg').src = e.target.result;
            document.getElementById('categoryImagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function cancelCategoryImage() {
    document.getElementById('categoryImage').value = '';
    if (!isEditModeCategory) {
        document.getElementById('categoryImagePreview').style.display = 'none';
    }
}

function confirmDeleteCategory(id, name) {
    document.getElementById('deleteModalTitle').textContent = 'Delete Category?';
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/categories/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}

// Brand Functions
let isEditModeBrand = false;

function openAddBrandModal() {
    isEditModeBrand = false;
    document.getElementById('brandModalTitle').textContent = 'Add Brand';
    document.getElementById('brandForm').action = '{{ route('admin.brands.store') }}';
    document.getElementById('brandFormMethod').value = 'POST';
    document.getElementById('brandForm').reset();
    document.getElementById('brandStatus').checked = true;
    document.getElementById('brandImagePreview').style.display = 'none';
    document.getElementById('brandModal').style.display = 'flex';
}

function editBrand(brand) {
    isEditModeBrand = true;
    document.getElementById('brandModalTitle').textContent = 'Edit Brand';
    document.getElementById('brandForm').action = `/admin/brands/${brand.id}`;
    document.getElementById('brandFormMethod').value = 'PUT';

    document.getElementById('brandName').value = brand.name;
    document.getElementById('brandDescription').value = brand.description || '';
    document.getElementById('brandSortOrder').value = brand.sort_order;
    document.getElementById('brandStatus').checked = brand.is_active;

    if (brand.logo) {
        document.getElementById('brandPreviewImg').src = `/storage/${brand.logo}`;
        document.getElementById('brandImagePreview').style.display = 'block';
    } else {
        document.getElementById('brandImagePreview').style.display = 'none';
    }

    document.getElementById('brandModal').style.display = 'flex';
}

function closeBrandModal() {
    document.getElementById('brandModal').style.display = 'none';
}

function previewBrandImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('brandPreviewImg').src = e.target.result;
            document.getElementById('brandImagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function cancelBrandImage() {
    document.getElementById('brandLogo').value = '';
    if (!isEditModeBrand) {
        document.getElementById('brandImagePreview').style.display = 'none';
    }
}

function confirmDeleteBrand(id, name) {
    document.getElementById('deleteModalTitle').textContent = 'Delete Brand?';
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/brands/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}

// Delete Modal
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modals on outside click
document.getElementById('productModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeProductModal();
});

document.getElementById('categoryModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCategoryModal();
});

document.getElementById('brandModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeBrandModal();
});

document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
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
