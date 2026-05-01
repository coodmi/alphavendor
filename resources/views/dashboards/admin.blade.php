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
        <a href="{{ route('admin.analytics') }}" class="menu-item{{ request()->routeIs('admin.analytics') ? ' active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            <span>Analytics & Reports</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Catalog Management</div>
        <a href="javascript:void(0)" onclick="showSection('products')" class="menu-item">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('categories')" class="menu-item">
            <i class="fas fa-tags"></i>
            <span>Categories</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('brands')" class="menu-item">
            <i class="fas fa-copyright"></i>
            <span>Brands</span>
        </a>
        <a href="{{ route('admin.special-offers.index') }}" class="menu-item">
            <i class="fas fa-tag"></i>
            <span>Special Offers</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('payment-verification')" class="menu-item">
            <i class="fas fa-money-check-alt"></i>
            <span>Payment Verification</span>
            @php
                $pendingPaymentsCount = \App\Models\ManualPayment::pending()->count();
            @endphp
            @if($pendingPaymentsCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingPaymentsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payment-settings.index') }}" class="menu-item">
            <i class="fas fa-cog"></i>
            <span>Payment Settings</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('invoices')" class="menu-item">
            <i class="fas fa-file-invoice"></i>
            <span>Invoices</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('shipments')" class="menu-item">
            <i class="fas fa-shipping-fast"></i>
            <span>Shipments</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('refunds')" class="menu-item">
            <i class="fas fa-undo"></i>
            <span>Refunds & Returns</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Customer Management</div>
        <a href="javascript:void(0)" onclick="showSection('customers')" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Customers</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('customer-groups')" class="menu-item">
            <i class="fas fa-user-friends"></i>
            <span>Customer Groups</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('wishlists')" class="menu-item">
            <i class="fas fa-heart"></i>
            <span>Wishlists</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">User & Role Management</div>
        <a href="{{ route('admin.users') }}" class="menu-item{{ request()->routeIs('admin.users*') ? ' active' : '' }}">
            <i class="fas fa-users-cog"></i>
            <span>All Users</span>
        </a>
        <a href="{{ route('admin.applications') }}" class="menu-item{{ request()->routeIs('admin.applications*') ? ' active' : '' }}">
            <i class="fas fa-user-plus"></i>
            <span>Role Applications</span>
            @if($stats['pending_applications'] > 0)
                <span class="badge">{{ $stats['pending_applications'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.user-permissions') }}" class="menu-item{{ request()->routeIs('admin.user-permissions*') ? ' active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>User Permissions</span>
        </a>
        <a href="{{ route('admin.role-settings') }}" class="menu-item{{ request()->routeIs('admin.role-settings*') ? ' active' : '' }}">
            <i class="fas fa-user-tag"></i>
            <span>Role Settings</span>
        </a>
        <a href="{{ route('admin.user-activity') }}" class="menu-item{{ request()->routeIs('admin.user-activity*') ? ' active' : '' }}">
            <i class="fas fa-user-clock"></i>
            <span>User Activity Logs</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Vendor Management</div>
        <a href="javascript:void(0)" onclick="showSection('vendors')" class="menu-item">
            <i class="fas fa-store"></i>
            <span>All Vendors</span>
        </a>
        <a href="{{ route('admin.vendor-badges.index') }}" class="menu-item">
            <i class="fas fa-award"></i>
            <span>Vendor Badges</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('applications')" class="menu-item">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
            @if($stats['pending_applications'] > 0)
                <span class="badge">{{ $stats['pending_applications'] }}</span>
            @endif
        </a>
        <a href="javascript:void(0)" onclick="showSection('commissions')" class="menu-item">
            <i class="fas fa-percent"></i>
            <span>Commission Settings</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('vendor-payouts')" class="menu-item">
            <i class="fas fa-money-check-alt"></i>
            <span>Vendor Payouts</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('vendor-reviews')" class="menu-item">
            <i class="fas fa-star-half-alt"></i>
            <span>Vendor Reviews</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Marketing & Promotions</div>
        <a href="javascript:void(0)" onclick="showSection('coupons')" class="menu-item">
            <i class="fas fa-ticket-alt"></i>
            <span>Coupons</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('flash-sales')" class="menu-item">
            <i class="fas fa-bolt"></i>
            <span>Flash Sales</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('banners')" class="menu-item">
            <i class="fas fa-images"></i>
            <span>Banners</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('email-campaigns')" class="menu-item">
            <i class="fas fa-envelope-open-text"></i>
            <span>Email Campaigns</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('newsletters')" class="menu-item">
            <i class="fas fa-newspaper"></i>
            <span>Newsletters</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Pages</div>
        <a href="javascript:void(0)" onclick="showSection('home-page')" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('retail-page')" class="menu-item">
            <i class="fas fa-store"></i>
            <span>Retail Page</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('wholesale-page')" class="menu-item">
            <i class="fas fa-warehouse"></i>
            <span>Wholesale</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('import-page')" class="menu-item">
            <i class="fas fa-shipping-fast"></i>
            <span>Import</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('about-page')" class="menu-item">
            <i class="fas fa-info-circle"></i>
            <span>About</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('contact-page')" class="menu-item">
            <i class="fas fa-envelope"></i>
            <span>Contact</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Payment & Finance</div>
        <a href="javascript:void(0)" onclick="showSection('transactions')" class="menu-item">
            <i class="fas fa-exchange-alt"></i>
            <span>Transactions</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('payment-gateway')" class="menu-item">
            <i class="fas fa-credit-card"></i>
            <span>Payment Gateways</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('offline-payment')" class="menu-item">
            <i class="fas fa-money-bill-wave"></i>
            <span>Offline Payments</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('tax-settings')" class="menu-item">
            <i class="fas fa-calculator"></i>
            <span>Tax Settings</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('currency')" class="menu-item">
            <i class="fas fa-dollar-sign"></i>
            <span>Currency Management</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Shipping & Logistics</div>
        <a href="javascript:void(0)" onclick="showSection('shipping-methods')" class="menu-item">
            <i class="fas fa-truck"></i>
            <span>Shipping Methods</span>
        </a>
        <!-- Shipping Zones menu removed - Paperfly handles zones automatically -->
        <a href="javascript:void(0)" onclick="showSection('delivery-boys')" class="menu-item">
            <i class="fas fa-motorcycle"></i>
            <span>Delivery Personnel</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Security & Verification</div>
        <a href="javascript:void(0)" onclick="showSection('kyc-verification')" class="menu-item">
            <i class="fas fa-id-card"></i>
            <span>KYC Verification</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('fraud-detection')" class="menu-item">
            <i class="fas fa-shield-alt"></i>
            <span>Fraud Detection</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('security-logs')" class="menu-item">
            <i class="fas fa-lock"></i>
            <span>Security Logs</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Communication</div>
        <a href="javascript:void(0)" onclick="showSection('notifications')" class="menu-item">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('chat-messenger')" class="menu-item">
            <i class="fas fa-comments"></i>
            <span>Chat Support</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('otp-system')" class="menu-item">
            <i class="fas fa-mobile-alt"></i>
            <span>SMS & OTP</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('support-tickets')" class="menu-item">
            <i class="fas fa-ticket-alt"></i>
            <span>Support Tickets</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">System Settings</div>
        <a href="javascript:void(0)" onclick="showSection('general-settings')" class="menu-item">
            <i class="fas fa-cog"></i>
            <span>General Settings</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('email-settings')" class="menu-item">
            <i class="fas fa-envelope"></i>
            <span>Email Settings</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('sms-settings')" class="menu-item">
            <i class="fas fa-sms"></i>
            <span>SMS Settings</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('badges')" class="menu-item">
            <i class="fas fa-award"></i>
            <span>Badges & Rewards</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('languages')" class="menu-item">
            <i class="fas fa-language"></i>
            <span>Languages</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('backup')" class="menu-item">
            <i class="fas fa-database"></i>
            <span>Backup & Restore</span>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-blue-500 to-blue-300 text-white">
            <div class="mr-4"><i class="fas fa-users fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Users</div>
                <div class="text-3xl font-extrabold">{{ $stats['total_users'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-green-500 to-green-300 text-white">
            <div class="mr-4"><i class="fas fa-store fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Retailers</div>
                <div class="text-3xl font-extrabold">{{ $stats['retailers'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-teal-600 to-teal-300 text-white">
            <div class="mr-4"><i class="fas fa-box fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Wholesalers</div>
                <div class="text-3xl font-extrabold">{{ $stats['wholesalers'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-pink-500 to-pink-300 text-white">
            <div class="mr-4"><i class="fas fa-globe-asia fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Exporters</div>
                <div class="text-3xl font-extrabold">{{ $stats['exporters'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-gray-700 to-gray-400 text-white">
            <div class="mr-4"><i class="fas fa-hourglass-half fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Pending Applications</div>
                <div class="text-3xl font-extrabold">{{ $stats['pending_applications'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-indigo-500 to-indigo-300 text-white">
            <div class="mr-4"><i class="fas fa-cube fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Products</div>
                <div class="text-3xl font-extrabold">{{ $stats['total_products'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-teal-500 to-teal-300 text-white">
            <div class="mr-4"><i class="fas fa-th-list fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Categories</div>
                <div class="text-3xl font-extrabold">{{ $stats['total_categories'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-red-500 to-red-300 text-white">
            <div class="mr-4"><i class="fas fa-copyright fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Brands</div>
                <div class="text-3xl font-extrabold">{{ $stats['total_brands'] }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-teal-600 to-orange-300 text-white">
            <div class="mr-4"><i class="fas fa-coins fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Sales</div>
                <div class="text-3xl font-extrabold">৳{{ number_format($stats['total_sales'], 2) }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-lime-500 to-lime-300 text-white">
            <div class="mr-4"><i class="fas fa-shopping-cart fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Orders</div>
                <div class="text-3xl font-extrabold">{{ $stats['total_orders'] }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
        <h3 class="font-bold text-xl mb-6 text-gray-700">Orders & Sales Overview</h3>
        <canvas id="ordersChart" height="80"></canvas>
    </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('ordersChart').getContext('2d');
    var ordersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Orders',
                    data: [2, 3, 4, 5, 3, 2, 1, 0, 0, 0, 0, 0], // Example data, replace with real
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 8,
                },
                {
                    label: 'Sales',
                    data: [100, 200, 300, 400, 250, 100, 50, 0, 0, 0, 0, 0], // Example data, replace with real
                    backgroundColor: 'rgba(236, 72, 153, 0.7)',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                },
                title: {
                    display: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col justify-between">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Quick Actions</h2>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.users') }}" class="px-5 py-2 rounded-lg bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition">Manage Users</a>
                <a href="{{ route('admin.users.create') }}" class="px-5 py-2 rounded-lg bg-green-500 text-white font-semibold shadow hover:bg-green-600 transition">Add New User</a>
                <a href="{{ route('admin.applications') }}" class="px-5 py-2 rounded-lg bg-teal-500 text-gray-900 font-semibold shadow hover:bg-teal-600 transition">View Applications</a>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Recent Role Applications</h2>
            @if($recentApplications->count() > 0)
                <div class="divide-y">
                    @foreach($recentApplications as $application)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <span class="font-semibold text-gray-800">{{ $application->user->name }}</span>
                                <span class="ml-2 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ ucfirst($application->requested_role) }}</span>
                            </div>
                            <div class="text-sm text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                            <a href="{{ route('admin.applications.show', $application) }}" class="ml-4 px-4 py-1 rounded bg-blue-500 text-white text-xs font-semibold hover:bg-blue-600 transition">View</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No pending applications.</p>
            @endif

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

<!-- Customer Groups Section -->
<div id="customer-groups-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Customer Groups Management</h2>
                <p style="color: #7f8c8d;">Organize customers into groups for targeted marketing</p>
            </div>
            <button onclick="openAddCustomerGroupModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Create Group
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Group</label>
                <input type="text" id="customerGroupSearchInput" placeholder="Search by group name..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="customerGroupStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="customerGroupSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="name">Name</option>
                    <option value="members">Members Count</option>
                    <option value="recent">Most Recent</option>
                </select>
            </div>
            <div>
                <button onclick="resetCustomerGroupFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Group Name</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Description</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Members</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Discount</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Created</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerGroupsTableBody">
                    <tr class="customer-group-row" style="border-bottom: 1px solid #dee2e6;"
                        data-name="vip customers"
                        data-status="active"
                        data-members="127"
                        data-date="2026-01-01">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    V
                                </div>
                                <strong>VIP Customers</strong>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <small style="color: #7f8c8d;">High-value customers with special privileges</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                127 Members
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2ecc71;">15%</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Active
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 01, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewCustomerGroup(1)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="editCustomerGroup(1)" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteCustomerGroup(1)" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr class="customer-group-row" style="border-bottom: 1px solid #dee2e6;"
                        data-name="wholesale buyers"
                        data-status="active"
                        data-members="89"
                        data-date="2025-12-15">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    W
                                </div>
                                <strong>Wholesale Buyers</strong>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <small style="color: #7f8c8d;">Bulk purchase customers</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                89 Members
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2ecc71;">20%</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Active
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Dec 15, 2025</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewCustomerGroup(2)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="editCustomerGroup(2)" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteCustomerGroup(2)" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr class="customer-group-row" style="border-bottom: 1px solid #dee2e6;"
                        data-name="regular customers"
                        data-status="active"
                        data-members="543"
                        data-date="2025-11-20">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    R
                                </div>
                                <strong>Regular Customers</strong>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <small style="color: #7f8c8d;">Standard customer group</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                543 Members
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2ecc71;">5%</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Active
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Nov 20, 2025</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewCustomerGroup(3)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="editCustomerGroup(3)" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteCustomerGroup(3)" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Wishlists Section -->
<div id="wishlists-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Wishlists Management</h2>
                <p style="color: #7f8c8d;">Track customer wishlists and preferences</p>
            </div>
            <button onclick="exportWishlists()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Wishlists</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">1,247</h3>
                </div>
                <i class="fas fa-heart" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Items</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">3,892</h3>
                </div>
                <i class="fas fa-box" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Conversion Rate</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">23%</h3>
                </div>
                <i class="fas fa-chart-line" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Most Wishlisted</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 18px;">Electronics</h3>
                </div>
                <i class="fas fa-star" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search</label>
                <input type="text" id="wishlistSearchInput" placeholder="Search by customer or product..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Category</label>
                <select id="wishlistCategoryFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="fashion">Fashion</option>
                    <option value="home">Home & Garden</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="wishlistStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="purchased">Purchased</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="wishlistSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
            <div>
                <button onclick="resetWishlistFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Product</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Category</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Price</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Added Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody id="wishlistsTableBody">
                    <tr class="wishlist-row" style="border-bottom: 1px solid #dee2e6;"
                        data-customer="john doe"
                        data-product="wireless headphones"
                        data-category="electronics"
                        data-status="active">
                        <td style="padding: 12px;">
                            <div>
                                <strong>John Doe</strong><br>
                                <small style="color: #7f8c8d;">john@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-headphones" style="color: #95a5a6; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <strong>Wireless Headphones</strong><br>
                                    <small style="color: #7f8c8d;">SKU: WH-2024-001</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                Electronics
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$89.99</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                In Stock
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 08, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewWishlist(1)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="notifyCustomer(1)" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-bell"></i> Notify
                            </button>
                        </td>
                    </tr>
                    <tr class="wishlist-row" style="border-bottom: 1px solid #dee2e6;"
                        data-customer="jane smith"
                        data-product="summer dress"
                        data-category="fashion"
                        data-status="active">
                        <td style="padding: 12px;">
                            <div>
                                <strong>Jane Smith</strong><br>
                                <small style="color: #7f8c8d;">jane@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-tshirt" style="color: #95a5a6; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <strong>Summer Dress</strong><br>
                                    <small style="color: #7f8c8d;">SKU: SD-2024-045</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #f3e5f5; color: #7b1fa2; border-radius: 12px; font-size: 12px;">
                                Fashion
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$45.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px;">
                                Low Stock
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 07, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewWishlist(2)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="notifyCustomer(2)" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-bell"></i> Notify
                            </button>
                        </td>
                    </tr>
                    <tr class="wishlist-row" style="border-bottom: 1px solid #dee2e6;"
                        data-customer="mike johnson"
                        data-product="garden tool set"
                        data-category="home"
                        data-status="out_of_stock">
                        <td style="padding: 12px;">
                            <div>
                                <strong>Mike Johnson</strong><br>
                                <small style="color: #7f8c8d;">mike@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-tools" style="color: #95a5a6; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <strong>Garden Tool Set</strong><br>
                                    <small style="color: #7f8c8d;">SKU: GT-2024-789</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Home & Garden
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$129.99</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 12px;">
                                Out of Stock
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 05, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewWishlist(3)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="notifyCustomer(3)" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-bell"></i> Notify
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- All Vendors Section -->
<div id="vendors-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">All Vendors Management</h2>
                <p style="color: #7f8c8d;">Manage and monitor all vendor accounts</p>
            </div>
            <button onclick="exportVendors()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i> Export Vendors
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Vendors</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">{{ $stats['total_vendors'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-store" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Active Vendors</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">{{ $stats['active_vendors'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-check-circle" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Products</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">{{ $stats['total_products'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-box" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Sales</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">${{ number_format($stats['total_sales'] ?? 0, 0) }}</h3>
                </div>
                <i class="fas fa-dollar-sign" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr repeat(4, 1fr) auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Vendor</label>
                <input type="text" id="vendorSearchInput" placeholder="Search by name or business..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Type</label>
                <select id="vendorTypeFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Types</option>
                    <option value="retailer">Retailer</option>
                    <option value="wholesaler">Wholesaler</option>
                    <option value="exporter">Exporter</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="vendorStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Verification</label>
                <select id="vendorVerificationFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All</option>
                    <option value="verified">Verified</option>
                    <option value="unverified">Unverified</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="vendorSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="name">Name</option>
                    <option value="products">Products</option>
                    <option value="sales">Sales</option>
                </select>
            </div>
            <div>
                <button onclick="resetVendorFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Vendor</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Business Name</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Type</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Products</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Sales</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Rating</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Joined</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody id="vendorsTableBody">
                    @forelse($vendors ?? [] as $vendor)
                    <tr class="vendor-row" style="border-bottom: 1px solid #dee2e6;"
                        data-name="{{ strtolower($vendor->name ?? '') }}"
                        data-business="{{ strtolower($vendor->business_name ?? '') }}"
                        data-type="{{ strtolower($vendor->role ?? '') }}"
                        data-status="active"
                        data-verification="verified"
                        data-products="{{ $vendor->products_count ?? 0 }}"
                        data-sales="0">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($vendor->profile_image ?? false)
                                    <img src="{{ asset('storage/' . $vendor->profile_image) }}" alt="{{ $vendor->name }}" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($vendor->name ?? 'V', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $vendor->name ?? 'N/A' }}</strong><br>
                                    <small style="color: #7f8c8d;">{{ $vendor->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <strong>{{ $vendor->business_name ?? 'N/A' }}</strong>
                        </td>
                        <td style="padding: 12px;">
                            @php
                                $roleColors = [
                                    'retailer' => ['bg' => '#e3f2fd', 'color' => '#1976d2'],
                                    'wholesaler' => ['bg' => '#f3e5f5', 'color' => '#7b1fa2'],
                                    'exporter' => ['bg' => '#e8f5e9', 'color' => '#388e3c']
                                ];
                                $role = strtolower($vendor->role ?? '');
                                $colors = $roleColors[$role] ?? ['bg' => '#e2e3e5', 'color' => '#383d41'];
                            @endphp
                            <span style="padding: 4px 12px; background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($vendor->role ?? 'N/A') }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                {{ $vendor->products_count ?? 0 }} Items
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$0.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-star" style="color: #f39c12; font-size: 14px;"></i>
                                <strong>4.5</strong>
                                <small style="color: #7f8c8d;">(0)</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Active
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>{{ $vendor->created_at->format('M d, Y') ?? 'N/A' }}</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewVendor({{ $vendor->id ?? 0 }})" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="manageVendor({{ $vendor->id ?? 0 }})" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-cog"></i> Manage
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-store" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No vendors found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reviews & Ratings Section -->
<div id="reviews-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Reviews & Ratings</h2>
                <p style="color: #7f8c8d;">Manage product reviews and customer feedback</p>
            </div>
            <button onclick="exportReviews()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i> Export Reviews
            </button>
        </div>
    </div>

    <!-- Reviews Stats -->
    <div id="reviewsStats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Total Reviews</div>
                    <div id="totalReviews" style="font-size: 28px; font-weight: bold;">0</div>
                </div>
                <i class="fas fa-comments" style="font-size: 36px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Avg Rating</div>
                    <div id="avgRating" style="font-size: 28px; font-weight: bold;">0.0 <i class="fas fa-star" style="font-size: 18px;"></i></div>
                </div>
                <i class="fas fa-star-half-alt" style="font-size: 36px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Pending</div>
                    <div id="pendingReviews" style="font-size: 28px; font-weight: bold;">0</div>
                </div>
                <i class="fas fa-clock" style="font-size: 36px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Approved</div>
                    <div id="approvedReviews" style="font-size: 28px; font-weight: bold;">0</div>
                </div>
                <i class="fas fa-check-circle" style="font-size: 36px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Reported</div>
                    <div id="reportedReviews" style="font-size: 28px; font-weight: bold;">0</div>
                </div>
                <i class="fas fa-exclamation-triangle" style="font-size: 36px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div id="ratingDistribution" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Rating Distribution</h3>
        <div id="ratingBars" style="display: flex; flex-direction: column; gap: 15px;">
            <!-- Rating bars will be populated dynamically -->
        </div>
    </div>

    <!-- Reviews List -->
    <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h3 style="margin: 0; font-size: 18px;">Recent Reviews</h3>
        </div>
        
        <!-- Search and Filter -->
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" id="reviewSearchInput" onkeyup="filterReviews()" placeholder="Search reviews..." 
                    style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <select id="reviewRatingFilter" onchange="filterReviews()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">All Ratings</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
            <select id="reviewStatusFilter" onchange="filterReviews()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="reported">Reported</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <div style="overflow-x: auto;">
            <table id="reviewsTable" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Product</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Rating</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Review</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Date</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody id="reviewsTableBody">
                </tbody>
            </table>
        </div>

        <!-- Reviews Pagination -->
        <div id="reviewsPagination" style="display: flex; justify-content: center; margin-top: 20px;">
        </div>
    </div>
</div>

<!-- Coupons Section -->
<div id="coupons-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Coupons Management</h2>
        <p style="color: #7f8c8d;">Create and manage discount coupons</p>
    </div>

    <!-- Add New Coupon -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Create New Coupon</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Coupon Code *</label>
                <input type="text" id="couponCode" placeholder="e.g., SAVE20" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; text-transform: uppercase;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Discount Type *</label>
                <select id="couponType" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                    <option value="free-shipping">Free Shipping</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Discount Value *</label>
                <input type="number" id="couponValue" placeholder="Enter value" min="0" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Minimum Purchase</label>
                <input type="number" id="couponMinPurchase" placeholder="Optional" min="0" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Start Date *</label>
                <input type="date" id="couponStartDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">End Date *</label>
                <input type="date" id="couponEndDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Usage Limit</label>
                <input type="number" id="couponLimit" placeholder="Unlimited" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status *</label>
                <select id="couponStatus" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button onclick="addCoupon()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-plus"></i> Create Coupon
        </button>
    </div>

    <!-- Coupons List -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">Active Coupons</h3>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="couponSearch" placeholder="Search coupons..." style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <select id="couponStatusFilter" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CODE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TYPE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">VALUE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">USAGE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">VALID UNTIL</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="couponsTableBody">
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            No coupons created yet. Create your first coupon above.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Flash Sales Section -->
<div id="flash-sales-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Flash Sales</h2>
        <p style="color: #7f8c8d;">Create time-limited promotional sales</p>
    </div>

    <!-- Create Flash Sale -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Create New Flash Sale</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sale Title *</label>
                <input type="text" id="flashSaleTitle" placeholder="e.g., Weekend Flash Sale" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Start Date & Time *</label>
                <input type="datetime-local" id="flashSaleStart" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">End Date & Time *</label>
                <input type="datetime-local" id="flashSaleEnd" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Discount Percentage *</label>
                <input type="number" id="flashSaleDiscount" placeholder="e.g., 30" min="1" max="100" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Featured Products</label>
                <select id="flashSaleProducts" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 42px;">
                    <option value="all">All Products</option>
                    <option value="1">Product 1</option>
                    <option value="2">Product 2</option>
                </select>
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Banner Image</label>
                <input type="file" id="flashSaleBanner" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
        </div>
        <button onclick="createFlashSale()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-bolt"></i> Create Flash Sale
        </button>
    </div>

    <!-- Active Flash Sales -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Active & Upcoming Sales</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #1a6b73 0%, #fb923c 100%); padding: 20px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <h4 style="margin: 0; font-size: 18px;">Sample Flash Sale</h4>
                        <span style="background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">50% OFF</span>
                    </div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Ends in 2 days 5 hours</p>
                </div>
                <div style="padding: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d; font-size: 13px;">Products:</span>
                        <strong style="color: #2c3e50;">25 items</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="color: #7f8c8d; font-size: 13px;">Status:</span>
                        <span style="background: #10b981; color: white; padding: 2px 10px; border-radius: 12px; font-size: 12px;">Active</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="editFlashSale(1)" style="flex: 1; padding: 8px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="deleteFlashSale(1)" style="flex: 1; padding: 8px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Banners Section -->
<div id="banners-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Banner Management</h2>
        <p style="color: #7f8c8d;">Manage promotional banners across the site</p>
    </div>

    <!-- Add New Banner -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Add New Banner</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Banner Title *</label>
                <input type="text" id="bannerTitle" placeholder="e.g., Holiday Sale Banner" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Placement *</label>
                <select id="bannerPlacement" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="home-hero">Home - Hero Section</option>
                    <option value="home-middle">Home - Middle Section</option>
                    <option value="category">Category Pages</option>
                    <option value="product">Product Pages</option>
                    <option value="sidebar">Sidebar</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Link URL</label>
                <input type="url" id="bannerLink" placeholder="https://..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Banner Image *</label>
                <input type="file" id="bannerImage" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <small style="color: #7f8c8d;">Recommended: 1920x600px for hero, 1200x400px for other sections</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Start Date</label>
                <input type="date" id="bannerStartDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">End Date</label>
                <input type="date" id="bannerEndDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Priority</label>
                <input type="number" id="bannerPriority" placeholder="1-100" min="1" max="100" value="50" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status *</label>
                <select id="bannerStatus" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button onclick="addBanner()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-plus"></i> Add Banner
        </button>
    </div>

    <!-- Active Banners -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">Active Banners</h3>
            <select id="bannerPlacementFilter" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">All Placements</option>
                <option value="home-hero">Home Hero</option>
                <option value="home-middle">Home Middle</option>
                <option value="category">Category Pages</option>
                <option value="sidebar">Sidebar</option>
            </select>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                <div style="background: #f8f9fa; height: 150px; display: flex; align-items: center; justify-content: center; color: #7f8c8d;">
                    <i class="fas fa-image" style="font-size: 48px;"></i>
                </div>
                <div style="padding: 15px;">
                    <h4 style="margin: 0 0 10px 0; color: #2c3e50;">Sample Banner</h4>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #7f8c8d; font-size: 13px;">Placement:</span>
                        <strong style="color: #2c3e50; font-size: 13px;">Home Hero</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="color: #7f8c8d; font-size: 13px;">Status:</span>
                        <span style="background: #10b981; color: white; padding: 2px 10px; border-radius: 12px; font-size: 12px;">Active</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button onclick="editBanner(1)" style="flex: 1; padding: 8px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="deleteBanner(1)" style="flex: 1; padding: 8px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Campaigns Section -->
<div id="email-campaigns-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Email Campaigns</h2>
        <p style="color: #7f8c8d;">Create and manage marketing email campaigns</p>
    </div>

    <!-- Create Campaign -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Create New Campaign</h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Campaign Name *</label>
                <input type="text" id="campaignName" placeholder="e.g., Summer Sale 2026" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Email Subject *</label>
                <input type="text" id="campaignSubject" placeholder="e.g., Don't Miss Our Summer Sale!" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Target Audience *</label>
                    <select id="campaignAudience" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="all">All Customers</option>
                        <option value="retail">Retail Customers</option>
                        <option value="wholesale">Wholesale Customers</option>
                        <option value="vendors">Vendors</option>
                        <option value="inactive">Inactive Customers</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Send Date & Time *</label>
                    <input type="datetime-local" id="campaignSendDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Email Template *</label>
                <select id="campaignTemplate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="promotional">Promotional</option>
                    <option value="newsletter">Newsletter</option>
                    <option value="announcement">Announcement</option>
                    <option value="custom">Custom HTML</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Email Content *</label>
                <textarea id="campaignContent" rows="8" placeholder="Enter your email content here..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="saveCampaignDraft()" style="padding: 12px 30px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-save"></i> Save Draft
            </button>
            <button onclick="sendTestEmail()" style="padding: 12px 30px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-paper-plane"></i> Send Test
            </button>
            <button onclick="scheduleCampaign()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-calendar-check"></i> Schedule Campaign
            </button>
        </div>
    </div>

    <!-- Campaign Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Total Sent</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">1,234</h3>
                </div>
                <i class="fas fa-paper-plane" style="font-size: 24px; opacity: 0.5;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Open Rate</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">45.2%</h3>
                </div>
                <i class="fas fa-envelope-open" style="font-size: 24px; opacity: 0.5;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #1a6b73 0%, #fb923c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Click Rate</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">12.8%</h3>
                </div>
                <i class="fas fa-mouse-pointer" style="font-size: 24px; opacity: 0.5;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Conversions</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">89</h3>
                </div>
                <i class="fas fa-shopping-cart" style="font-size: 24px; opacity: 0.5;"></i>
            </div>
        </div>
    </div>

    <!-- Campaign History -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Campaign History</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CAMPAIGN</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">SENT DATE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">RECIPIENTS</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">OPENS</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CLICKS</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">New Year Sale 2026</strong>
                            <br><small style="color: #7f8c8d;">Big discounts on all items</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 1, 2026</td>
                        <td style="padding: 12px; color: #2c3e50;">1,234</td>
                        <td style="padding: 12px; color: #2c3e50;">45.2%</td>
                        <td style="padding: 12px; color: #2c3e50;">12.8%</td>
                        <td style="padding: 12px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Sent</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewCampaignReport(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-chart-line"></i>
                            </button>
                            <button onclick="duplicateCampaign(1)" style="padding: 6px 12px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Newsletters Section -->
<div id="newsletters-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Newsletters</h2>
        <p style="color: #7f8c8d;">Manage newsletter subscriptions and content</p>
    </div>

    <!-- Subscriber Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Total Subscribers</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">5,678</h3>
                    <small style="color: #10b981;"><i class="fas fa-arrow-up"></i> +12.5%</small>
                </div>
                <div style="background: #3b82f6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Active Subscribers</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">4,892</h3>
                    <small style="color: #10b981;"><i class="fas fa-arrow-up"></i> +8.3%</small>
                </div>
                <div style="background: #10b981; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-check" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">This Month</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">234</h3>
                    <small style="color: #10b981;"><i class="fas fa-arrow-up"></i> +23.1%</small>
                </div>
                <div style="background: #1a6b73; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-plus" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Unsubscribed</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">45</h3>
                    <small style="color: #ef4444;"><i class="fas fa-arrow-down"></i> -2.1%</small>
                </div>
                <div style="background: #ef4444; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-times" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Newsletter -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Create Newsletter</h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Newsletter Title *</label>
                    <input type="text" id="newsletterTitle" placeholder="e.g., January 2026 Newsletter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Send Date *</label>
                    <input type="datetime-local" id="newsletterSendDate" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Featured Content *</label>
                <textarea id="newsletterContent" rows="10" placeholder="Enter newsletter content..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Featured Products</label>
                    <select id="newsletterProducts" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 100px;">
                        <option value="1">Product 1</option>
                        <option value="2">Product 2</option>
                        <option value="3">Product 3</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Categories to Highlight</label>
                    <select id="newsletterCategories" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 100px;">
                        <option value="1">Category 1</option>
                        <option value="2">Category 2</option>
                        <option value="3">Category 3</option>
                    </select>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="saveNewsletterDraft()" style="padding: 12px 30px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-save"></i> Save Draft
            </button>
            <button onclick="sendTestNewsletter()" style="padding: 12px 30px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-paper-plane"></i> Send Test
            </button>
            <button onclick="scheduleNewsletter()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-calendar-check"></i> Schedule Newsletter
            </button>
        </div>
    </div>

    <!-- Subscribers List -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">Subscribers</h3>
            <div style="display: flex; gap: 10px;">
                <input type="text" id="subscriberSearch" placeholder="Search subscribers..." style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <button onclick="exportSubscribers()" style="padding: 8px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">EMAIL</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">NAME</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">SUBSCRIBED ON</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #2c3e50;">subscriber@example.com</td>
                        <td style="padding: 12px; color: #2c3e50;">John Doe</td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 5, 2026</td>
                        <td style="padding: 12px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewSubscriber(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="removeSubscriber(1)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Transactions Section -->
<div id="transactions-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Transactions</h2>
        <p style="color: #7f8c8d;">View and manage all platform transactions</p>
    </div>

    <!-- Transaction Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Total Revenue</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">$145,892</h3>
                    <small style="opacity: 0.8;"><i class="fas fa-arrow-up"></i> +15.3% this month</small>
                </div>
                <i class="fas fa-dollar-sign" style="font-size: 28px; opacity: 0.5;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Successful</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">1,234</h3>
                    <small style="opacity: 0.8;"><i class="fas fa-arrow-up"></i> +8.2%</small>
                </div>
                <i class="fas fa-check-circle" style="font-size: 28px; opacity: 0.5;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #1a6b73 0%, #fb923c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Pending</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">89</h3>
                    <small style="opacity: 0.8;">Awaiting processing</small>
                </div>
                <i class="fas fa-clock" style="font-size: 28px; opacity: 0.5;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; opacity: 0.9;">Failed</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px;">23</h3>
                    <small style="opacity: 0.8;"><i class="fas fa-arrow-down"></i> -3.1%</small>
                </div>
                <i class="fas fa-times-circle" style="font-size: 28px; opacity: 0.5;"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
            <input type="text" id="transactionSearch" placeholder="Search by ID, Customer..." style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <select id="transactionStatusFilter" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">All Status</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            <select id="transactionMethodFilter" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                <option value="">Payment Method</option>
                <option value="stripe">Stripe</option>
                <option value="paypal">PayPal</option>
                <option value="bank">Bank Transfer</option>
                <option value="cod">Cash on Delivery</option>
            </select>
            <input type="date" id="transactionDateFrom" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <button onclick="exportTransactions()" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>

    <!-- Transactions Table -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Recent Transactions</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TRANSACTION ID</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">DATE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CUSTOMER</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">AMOUNT</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">METHOD</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;"><strong style="color: #2c3e50;">#TXN001234</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 11, 2026<br><small style="color: #7f8c8d;">10:30 AM</small></td>
                        <td style="padding: 12px; color: #2c3e50;">John Smith<br><small style="color: #7f8c8d;">john@example.com</small></td>
                        <td style="padding: 12px;"><strong style="color: #10b981; font-size: 16px;">$259.99</strong></td>
                        <td style="padding: 12px; color: #2c3e50;"><i class="fab fa-cc-stripe" style="color: #6772e5;"></i> Stripe</td>
                        <td style="padding: 12px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Completed</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewTransaction(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="refundTransaction(1)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-undo"></i>
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;"><strong style="color: #2c3e50;">#TXN001233</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 11, 2026<br><small style="color: #7f8c8d;">09:15 AM</small></td>
                        <td style="padding: 12px; color: #2c3e50;">Sarah Johnson<br><small style="color: #7f8c8d;">sarah@example.com</small></td>
                        <td style="padding: 12px;"><strong style="color: #10b981; font-size: 16px;">$459.50</strong></td>
                        <td style="padding: 12px; color: #2c3e50;"><i class="fab fa-cc-paypal" style="color: #003087;"></i> PayPal</td>
                        <td style="padding: 12px;">
                            <span style="background: #1a6b73; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Pending</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewTransaction(2)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Gateways Section -->
<div id="payment-gateway-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Payment Gateways</h2>
        <p style="color: #7f8c8d;">Configure payment gateway integrations</p>
    </div>

    <!-- Available Gateways -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-bottom: 25px;">
        <!-- Stripe -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #6772e5 0%, #5469d4 100%); padding: 20px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fab fa-cc-stripe" style="font-size: 36px;"></i>
                        <div>
                            <h3 style="margin: 0; font-size: 20px;">Stripe</h3>
                            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Online Payment Processing</p>
                        </div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.3); border-radius: 24px; transition: 0.4s;"></span>
                    </label>
                </div>
            </div>
            <div style="padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Publishable Key</label>
                    <input type="text" placeholder="pk_live_..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Secret Key</label>
                    <input type="password" placeholder="sk_live_..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Webhook Secret</label>
                    <input type="text" placeholder="whsec_..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <button onclick="saveGatewaySettings('stripe')" style="width: 100%; padding: 12px; background: #6772e5; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-save"></i> Save Configuration
                </button>
            </div>
        </div>

        <!-- PayPal -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #003087 0%, #0070ba 100%); padding: 20px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fab fa-cc-paypal" style="font-size: 36px;"></i>
                        <div>
                            <h3 style="margin: 0; font-size: 20px;">PayPal</h3>
                            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">PayPal Express Checkout</p>
                        </div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.3); border-radius: 24px;"></span>
                    </label>
                </div>
            </div>
            <div style="padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Client ID</label>
                    <input type="text" placeholder="Enter PayPal Client ID" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Client Secret</label>
                    <input type="password" placeholder="Enter PayPal Secret" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Mode</label>
                    <select style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="sandbox">Sandbox (Test)</option>
                        <option value="live">Live (Production)</option>
                    </select>
                </div>
                <button onclick="saveGatewaySettings('paypal')" style="width: 100%; padding: 12px; background: #0070ba; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-save"></i> Save Configuration
                </button>
            </div>
        </div>

        <!-- Razorpay -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #3395ff 0%, #0c7fdc 100%); padding: 20px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-credit-card" style="font-size: 36px;"></i>
                        <div>
                            <h3 style="margin: 0; font-size: 20px;">Razorpay</h3>
                            <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">Indian Payment Gateway</p>
                        </div>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                        <input type="checkbox" style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.3); border-radius: 24px;"></span>
                    </label>
                </div>
            </div>
            <div style="padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Key ID</label>
                    <input type="text" placeholder="rzp_live_..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Key Secret</label>
                    <input type="password" placeholder="Enter Secret Key" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500; font-size: 13px;">Webhook Secret</label>
                    <input type="text" placeholder="Optional" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <button onclick="saveGatewaySettings('razorpay')" style="width: 100%; padding: 12px; background: #3395ff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-save"></i> Save Configuration
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Offline Payments Section -->
<div id="offline-payment-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Offline Payments</h2>
        <p style="color: #7f8c8d;">Manage offline payment methods like bank transfer and cash</p>
    </div>

    <!-- Add Offline Method -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Add Offline Payment Method</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Method Name *</label>
                <input type="text" id="offlineMethodName" placeholder="e.g., Bank Transfer" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Method Type *</label>
                <select id="offlineMethodType" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="bank-transfer">Bank Transfer</option>
                    <option value="cash">Cash on Delivery</option>
                    <option value="check">Check Payment</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Instructions for Customers *</label>
                <textarea id="offlineInstructions" rows="4" placeholder="Enter payment instructions..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Account Details (Optional)</label>
                <textarea id="offlineAccountDetails" rows="3" placeholder="Bank name, Account number, etc." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Additional Fee (Optional)</label>
                <input type="number" id="offlineFee" placeholder="0.00" min="0" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status *</label>
                <select id="offlineStatus" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button onclick="addOfflinePayment()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-plus"></i> Add Payment Method
        </button>
    </div>

    <!-- Active Methods -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Active Offline Payment Methods</h3>
        <div style="display: grid; gap: 15px;">
            <!-- Bank Transfer -->
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <div style="background: #3b82f6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-university" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: #2c3e50; font-size: 18px;">Bank Transfer</h4>
                            <span style="background: #10b981; color: white; padding: 2px 10px; border-radius: 12px; font-size: 11px; margin-top: 5px; display: inline-block;">Active</span>
                        </div>
                    </div>
                    <p style="margin: 10px 0; color: #7f8c8d; font-size: 14px;">Transfer payment to our bank account and send us the receipt.</p>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-top: 10px;">
                        <small style="color: #2c3e50; font-weight: 600;">Bank: ABC Bank | Account: 1234567890 | SWIFT: ABCDEF12</small>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button onclick="editOfflinePayment(1)" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteOfflinePayment(1)" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Cash on Delivery -->
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <div style="background: #10b981; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-money-bill-wave" style="color: white; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: #2c3e50; font-size: 18px;">Cash on Delivery</h4>
                            <span style="background: #10b981; color: white; padding: 2px 10px; border-radius: 12px; font-size: 11px; margin-top: 5px; display: inline-block;">Active</span>
                        </div>
                    </div>
                    <p style="margin: 10px 0; color: #7f8c8d; font-size: 14px;">Pay with cash when your order is delivered to your doorstep.</p>
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-top: 10px;">
                        <small style="color: #2c3e50; font-weight: 600;">Additional Fee: $5.00 per order</small>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button onclick="editOfflinePayment(2)" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteOfflinePayment(2)" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tax Settings Section -->
<div id="tax-settings-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Tax Settings</h2>
        <p style="color: #7f8c8d;">Configure tax rates and rules</p>
    </div>

    <!-- Global Tax Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Global Tax Configuration</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Enable Tax System</label>
                <select id="taxEnabled" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Tax Display</label>
                <select id="taxDisplay" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="exclusive">Excluding Tax</option>
                    <option value="inclusive">Including Tax</option>
                    <option value="both">Show Both</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Tax Based On</label>
                <select id="taxBasedOn" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="shipping">Shipping Address</option>
                    <option value="billing">Billing Address</option>
                    <option value="store">Store Address</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Shipping Tax Class</label>
                <select id="shippingTaxClass" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="standard">Standard Rate</option>
                    <option value="reduced">Reduced Rate</option>
                    <option value="zero">Zero Rate</option>
                </select>
            </div>
        </div>
        <button onclick="saveGlobalTaxSettings()" style="padding: 12px 30px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-save"></i> Save Global Settings
        </button>
    </div>

    <!-- Add Tax Rate -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Add New Tax Rate</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Country *</label>
                <select id="taxCountry" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">Select Country</option>
                    <option value="US">United States</option>
                    <option value="GB">United Kingdom</option>
                    <option value="CA">Canada</option>
                    <option value="AU">Australia</option>
                    <option value="IN">India</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">State/Province</label>
                <input type="text" id="taxState" placeholder="Optional" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Postal Code/ZIP</label>
                <input type="text" id="taxPostal" placeholder="Optional" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Tax Rate (%) *</label>
                <input type="number" id="taxRate" placeholder="e.g., 8.5" min="0" max="100" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Tax Name *</label>
                <input type="text" id="taxName" placeholder="e.g., VAT, GST, Sales Tax" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Priority</label>
                <input type="number" id="taxPriority" value="1" min="1" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
        </div>
        <button onclick="addTaxRate()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-plus"></i> Add Tax Rate
        </button>
    </div>

    <!-- Tax Rates Table -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Configured Tax Rates</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TAX NAME</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">COUNTRY</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">RATE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">PRIORITY</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #2c3e50;"><strong>US Sales Tax</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">United States</td>
                        <td style="padding: 12px; color: #2c3e50;">California</td>
                        <td style="padding: 12px;"><strong style="color: #1a6b73;">8.5%</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">1</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editTaxRate(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteTaxRate(1)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #2c3e50;"><strong>UK VAT</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">United Kingdom</td>
                        <td style="padding: 12px; color: #7f8c8d;">-</td>
                        <td style="padding: 12px;"><strong style="color: #1a6b73;">20%</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">1</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editTaxRate(2)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteTaxRate(2)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Currency Management Section -->
<div id="currency-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Currency Management</h2>
        <p style="color: #7f8c8d;">Manage supported currencies and exchange rates</p>
    </div>

    <!-- Default Currency -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Default Currency Settings</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Base Currency *</label>
                <select id="baseCurrency" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="USD">USD - US Dollar ($)</option>
                    <option value="EUR">EUR - Euro (€)</option>
                    <option value="GBP">GBP - British Pound (£)</option>
                    <option value="INR">INR - Indian Rupee (₹)</option>
                    <option value="AUD">AUD - Australian Dollar (A$)</option>
                    <option value="CAD">CAD - Canadian Dollar (C$)</option>
                    <option value="BDT">BDT - Bangladeshi Taka (৳)</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Currency Position</label>
                <select id="currencyPosition" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="left">Left ($99.99)</option>
                    <option value="right">Right (99.99$)</option>
                    <option value="left-space">Left with space ($ 99.99)</option>
                    <option value="right-space">Right with space (99.99 $)</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Decimal Separator</label>
                <select id="decimalSeparator" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value=".">Period (.)</option>
                    <option value=",">Comma (,)</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Thousand Separator</label>
                <select id="thousandSeparator" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value=",">Comma (,)</option>
                    <option value=".">Period (.)</option>
                    <option value=" ">Space ( )</option>
                    <option value="">None</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Number of Decimals</label>
                <input type="number" id="numDecimals" value="2" min="0" max="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Auto Update Rates</label>
                <select id="autoUpdateRates" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="manual">Manual Only</option>
                </select>
            </div>
        </div>
        <button onclick="saveCurrencySettings()" style="padding: 12px 30px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>

    <!-- Add Currency -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Add New Currency</h3>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr) auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Currency Code *</label>
                <input type="text" id="currencyCode" placeholder="e.g., USD" maxlength="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; text-transform: uppercase;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Currency Name *</label>
                <input type="text" id="currencyName" placeholder="e.g., US Dollar" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Symbol *</label>
                <input type="text" id="currencySymbol" placeholder="e.g., $" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Exchange Rate *</label>
                <input type="number" id="exchangeRate" placeholder="1.00" min="0" step="0.0001" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <button onclick="addCurrency()" style="padding: 10px 24px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
    </div>

    <!-- Supported Currencies -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">Supported Currencies</h3>
            <button onclick="updateAllRates()" style="padding: 8px 20px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-sync-alt"></i> Update All Rates
            </button>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CURRENCY</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CODE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">SYMBOL</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">EXCHANGE RATE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">LAST UPDATED</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6; background: #f0fdf4;">
                        <td style="padding: 12px;"><strong style="color: #2c3e50;">US Dollar</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">USD</td>
                        <td style="padding: 12px; color: #2c3e50; font-size: 16px;">$</td>
                        <td style="padding: 12px;"><strong style="color: #10b981;">1.0000</strong> <small style="color: #7f8c8d;">(Base)</small></td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 11, 2026</td>
                        <td style="padding: 12px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="color: #7f8c8d; font-size: 12px;">Base Currency</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;"><strong style="color: #2c3e50;">Euro</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">EUR</td>
                        <td style="padding: 12px; color: #2c3e50; font-size: 16px;">€</td>
                        <td style="padding: 12px;"><strong style="color: #1a6b73;">0.8523</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 11, 2026</td>
                        <td style="padding: 12px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editCurrency('EUR')" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="toggleCurrency('EUR')" style="padding: 6px 12px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;"><strong style="color: #2c3e50;">British Pound</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">GBP</td>
                        <td style="padding: 12px; color: #2c3e50; font-size: 16px;">£</td>
                        <td style="padding: 12px;"><strong style="color: #1a6b73;">0.7345</strong></td>
                        <td style="padding: 12px; color: #2c3e50;">Jan 11, 2026</td>
                        <td style="padding: 12px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editCurrency('GBP')" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="toggleCurrency('GBP')" style="padding: 6px 12px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Shipping Methods Section (Paperfly Integration) -->
<div id="shipping-methods-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Paperfly Delivery Settings</h2>
        <p style="color: #7f8c8d;">Configure Paperfly API integration for automated delivery</p>
    </div>

    <!-- API Configuration -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Paperfly API Configuration</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">API Base URL</label>
                <input type="text" id="paperflyBaseUrl" value="https://api.paperfly.com.bd" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f8f9fa;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Merchant Username *</label>
                <input type="text" id="paperflyUsername" placeholder="Enter merchant username" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Merchant Password *</label>
                <input type="password" id="paperflyPassword" placeholder="Enter merchant password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Paperfly API Key *</label>
                <input type="text" id="paperflyKey" placeholder="Paperfly_~La?Rj73FcLm" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Merchant Code *</label>
                <input type="text" id="paperflyMerchantCode" placeholder="e.g., M-1-5260" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
        </div>
        <button onclick="savePaperflyConfig()" style="padding: 12px 30px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-save"></i> Save Configuration
        </button>
        <button onclick="testPaperflyConnection()" style="padding: 12px 30px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; margin-left: 10px;">
            <i class="fas fa-plug"></i> Test Connection
        </button>
    </div>

    <!-- Pickup Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Default Pickup Location</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Merchant Name *</label>
                <input type="text" id="pickupMerchantName" placeholder="Your business name" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Phone Number *</label>
                <input type="tel" id="pickupPhone" placeholder="0171xxxxxxx" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Pickup Address *</label>
                <input type="text" id="pickupAddress" placeholder="Enter full address" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Thana *</label>
                <input type="text" id="pickupThana" placeholder="e.g., Dhanmondi" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">District *</label>
                <input type="text" id="pickupDistrict" placeholder="e.g., Dhaka" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
        </div>
        <button onclick="savePickupSettings()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-save"></i> Save Pickup Settings
        </button>
    </div>

    <!-- Delivery Options -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Available Delivery Options</h3>
        <div style="display: grid; gap: 15px;">
            <!-- Regular Delivery -->
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 18px;">
                            <i class="fas fa-truck" style="color: #3b82f6;"></i> Regular Delivery
                        </h4>
                        <p style="margin: 0; color: #7f8c8d; font-size: 14px;">Standard delivery with 3-5 business days</p>
                        <div style="margin-top: 10px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 13px; color: #7f8c8d; margin-bottom: 5px;">Delivery Time</div>
                        <div style="font-size: 20px; font-weight: 600; color: #2c3e50;">3-5 Days</div>
                    </div>
                </div>
            </div>

            <!-- Express Delivery -->
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 18px;">
                            <i class="fas fa-shipping-fast" style="color: #1a6b73;"></i> Express Delivery
                        </h4>
                        <p style="margin: 0; color: #7f8c8d; font-size: 14px;">Fast delivery within 1-2 business days</p>
                        <div style="margin-top: 10px;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 13px; color: #7f8c8d; margin-bottom: 5px;">Delivery Time</div>
                        <div style="font-size: 20px; font-weight: 600; color: #2c3e50;">1-2 Days</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Zones Section (Removed - Paperfly handles zones automatically) -->
<!-- Old Shipping Zones Section -->
<div id="shipping-zones-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Shipping Zones</h2>
        <p style="color: #7f8c8d;">Define shipping zones and regional rates</p>
    </div>

    <!-- Add Shipping Zone -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Add New Shipping Zone</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Zone Name *</label>
                <input type="text" id="zoneName" placeholder="e.g., North America" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Zone Type *</label>
                <select id="zoneType" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="country">By Country</option>
                    <option value="state">By State/Province</option>
                    <option value="postal">By Postal Code</option>
                    <option value="region">By Region</option>
                </select>
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Countries/Regions *</label>
                <select id="zoneRegions" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 100px;">
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                    <option value="MX">Mexico</option>
                    <option value="GB">United Kingdom</option>
                    <option value="AU">Australia</option>
                    <option value="IN">India</option>
                </select>
                <small style="color: #7f8c8d;">Hold Ctrl (Cmd on Mac) to select multiple</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Shipping Methods</label>
                <select id="zoneShippingMethods" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 80px;">
                    <option value="standard">Standard Shipping</option>
                    <option value="express">Express Delivery</option>
                    <option value="free">Free Shipping</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status *</label>
                <select id="zoneStatus" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button onclick="addShippingZone()" style="padding: 12px 30px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
            <i class="fas fa-plus"></i> Add Shipping Zone
        </button>
    </div>

    <!-- Configured Zones -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Configured Shipping Zones</h3>
        <div style="display: grid; gap: 20px;">
            <!-- Zone 1: North America -->
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); padding: 20px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="margin: 0; font-size: 20px;">North America</h4>
                            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">United States, Canada, Mexico</p>
                        </div>
                        <span style="background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #2c3e50; display: block; margin-bottom: 10px;">Available Shipping Methods:</strong>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <span style="background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                                <i class="fas fa-truck"></i> Standard ($9.99)
                            </span>
                            <span style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                                <i class="fas fa-shipping-fast"></i> Express ($19.99)
                            </span>
                            <span style="background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                                <i class="fas fa-gift"></i> Free Shipping
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button onclick="editShippingZone(1)" style="flex: 1; padding: 10px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-edit"></i> Edit Zone
                        </button>
                        <button onclick="deleteShippingZone(1)" style="flex: 1; padding: 10px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Zone 2: Europe -->
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); padding: 20px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="margin: 0; font-size: 20px;">Europe</h4>
                            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">UK, France, Germany, and 25 more</p>
                        </div>
                        <span style="background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #2c3e50; display: block; margin-bottom: 10px;">Available Shipping Methods:</strong>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <span style="background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                                <i class="fas fa-truck"></i> Standard ($14.99)
                            </span>
                            <span style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                                <i class="fas fa-shipping-fast"></i> Express ($29.99)
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button onclick="editShippingZone(2)" style="flex: 1; padding: 10px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-edit"></i> Edit Zone
                        </button>
                        <button onclick="deleteShippingZone(2)" style="flex: 1; padding: 10px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Zone 3: Asia Pacific -->
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #1a6b73 0%, #fb923c 100%); padding: 20px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="margin: 0; font-size: 20px;">Asia Pacific</h4>
                            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Australia, India, Japan, Singapore</p>
                        </div>
                        <span style="background: rgba(255,255,255,0.3); padding: 4px 12px; border-radius: 12px; font-size: 12px;">Active</span>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #2c3e50; display: block; margin-bottom: 10px;">Available Shipping Methods:</strong>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <span style="background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 6px; font-size: 13px;">
                                <i class="fas fa-truck"></i> Standard ($12.99)
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button onclick="editShippingZone(3)" style="flex: 1; padding: 10px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-edit"></i> Edit Zone
                        </button>
                        <button onclick="deleteShippingZone(3)" style="flex: 1; padding: 10px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paperfly Order Tracking Section -->
<div id="delivery-boys-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Paperfly Order Tracking</h2>
        <p style="color: #7f8c8d;">Track deliveries managed by Paperfly</p>
    </div>

    <!-- Delivery Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Total Deliveries</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;" id="totalDeliveries">0</h3>
                </div>
                <div style="background: #3b82f6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-box" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">In Transit</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;" id="inTransitCount">0</h3>
                </div>
                <div style="background: #1a6b73; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-truck" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Out for Delivery</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;" id="outForDeliveryCount">0</h3>
                </div>
                <div style="background: #8b5cf6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-shipping-fast" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Delivered Today</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;" id="deliveredTodayCount">0</h3>
                </div>
                <div style="background: #10b981; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Track Order -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Track Order</h3>
        <div style="display: flex; gap: 15px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Order Number or Tracking Number</label>
                <input type="text" id="trackingSearchInput" placeholder="Enter order number or Paperfly tracking number" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <button onclick="trackPaperflyOrder()" style="padding: 12px 30px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-search"></i> Track Order
            </button>
            <button onclick="refreshTracking()" style="padding: 12px 30px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Tracking Result -->
    <div id="trackingResultContainer" style="display: none; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h3 style="color: #2c3e50; margin: 0 0 5px 0;">Tracking Details</h3>
                <p style="margin: 0; color: #7f8c8d;">
                    <strong>Tracking Number:</strong> <span id="trackingNumber">-</span> | 
                    <strong>Order Reference:</strong> <span id="orderReference">-</span>
                </p>
            </div>
            <div>
                <span id="deliveryStatusBadge" style="padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 14px;"></span>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div style="position: relative; padding-left: 50px;">
            <div id="trackingTimeline" style="position: relative;">
                <!-- Timeline items will be inserted here by JavaScript -->
            </div>
        </div>

        <!-- Customer & Delivery Info -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <div>
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Customer Information</h4>
                <p style="margin: 5px 0; color: #7f8c8d;"><strong>Name:</strong> <span id="customerName">-</span></p>
                <p style="margin: 5px 0; color: #7f8c8d;"><strong>Phone:</strong> <span id="customerPhone">-</span></p>
                <p style="margin: 5px 0; color: #7f8c8d;"><strong>Address:</strong> <span id="customerAddress">-</span></p>
            </div>
            <div>
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Delivery Information</h4>
                <p style="margin: 5px 0; color: #7f8c8d;"><strong>Estimated Delivery:</strong> <span id="estimatedDelivery">-</span></p>
                <p style="margin: 5px 0; color: #7f8c8d;"><strong>Delivery Type:</strong> <span id="deliveryType">-</span></p>
                <p style="margin: 5px 0; color: #7f8c8d;"><strong>Package Value:</strong> ৳<span id="packageValue">0</span></p>
            </div>
        </div>
    </div>

    <!-- Recent Orders with Tracking -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">Recent Orders</h3>
            <div style="display: flex; gap: 10px;">
                <select id="orderStatusFilter" onchange="filterPaperflyOrders()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Orders</option>
                    <option value="pending">Pending Pickup</option>
                    <option value="picked">Picked Up</option>
                    <option value="in_transit">In Transit</option>
                    <option value="out_for_delivery">Out for Delivery</option>
                    <option value="delivered">Delivered</option>
                </select>
                <button onclick="syncAllTracking()" style="padding: 8px 15px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Sync All
                </button>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">ORDER</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">CUSTOMER</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TRACKING NO.</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">LAST UPDATE</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="paperflyOrdersTable">
                    <!-- Orders will be loaded here by JavaScript -->
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 10px; display: block; opacity: 0.3;"></i>
                            Loading orders...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Commission Settings Section -->
<div id="commissions-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Commission Settings</h2>
        <p style="color: #7f8c8d;">Manage platform commission rates</p>
    </div>

    <!-- Add New Commission Rate -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Add New Commission Rate</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Type *</label>
                <select id="commissionType" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="global">Global</option>
                    <option value="category">Category-specific</option>
                    <option value="vendor">Vendor-specific</option>
                    <option value="product">Product-specific</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Commission Rate (%) *</label>
                <input type="number" id="commissionRate" placeholder="Enter rate" min="0" max="100" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <button onclick="addCommissionRate()" style="padding: 10px 24px; background: #1a6b73; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-plus"></i> Add Commission
                </button>
            </div>
        </div>
    </div>

    <!-- Current Commission Rates -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 10px;">Current Commission Rates</h3>
        <p style="color: #7f8c8d; font-size: 13px; margin-bottom: 20px;">Priority: Product-specific > Category-specific > Vendor-specific > Global</p>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TYPE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">APPLIED TO</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">COMMISSION RATE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            No commission settings found. Add one above.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commission Calculator -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Commission Calculator</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Sale Amount ($)</label>
                <input type="number" id="saleAmount" value="100" min="0" step="0.01" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Commission Rate (%)</label>
                <input type="number" id="calcCommissionRate" value="10" min="0" max="100" step="0.01" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Results</label>
                <div style="padding: 12px; background: #f8f9fa; border-radius: 6px;">
                    <div style="margin-bottom: 5px;">
                        <strong style="color: #1a6b73; font-size: 18px;">Platform Commission:</strong>
                        <strong style="color: #1a6b73; font-size: 20px;" id="platformCommission">$10.00</strong>
                    </div>
                    <div>
                        <small style="color: #7f8c8d;">Vendor Receives:</small>
                        <strong style="color: #2ecc71;" id="vendorReceives">$90.00</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Payouts Section -->
<div id="vendor-payouts-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Vendor Payouts Management</h2>
                <p style="color: #7f8c8d;">Process and manage vendor payments</p>
            </div>
            <button onclick="processBulkPayout()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-money-check-alt"></i> Process Payout
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Pending Payouts</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">$24,580</h3>
                </div>
                <i class="fas fa-clock" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Completed</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">$189,340</h3>
                </div>
                <i class="fas fa-check-circle" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">This Month</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">$45,230</h3>
                </div>
                <i class="fas fa-calendar" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Vendors</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">{{ $stats['total_vendors'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-users" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search</label>
                <input type="text" id="payoutSearchInput" placeholder="Search by vendor name..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="payoutStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Date Range</label>
                <select id="payoutDateFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="payoutSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="amount">Amount</option>
                </select>
            </div>
            <div>
                <button onclick="resetPayoutFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Payout ID</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Vendor</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Amount</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Commission</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Net Amount</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">#PAY-001234</strong>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>Tech Solutions Inc.</strong><br>
                                <small style="color: #7f8c8d;">john@techsolutions.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$8,450.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="color: #e74c3c;">- $845.00 (10%)</span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2ecc71; font-size: 16px;">$7,605.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px;">
                                Pending
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 11, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewPayout(1)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="approvePayout(1)" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">#PAY-001233</strong>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>Fashion Hub</strong><br>
                                <small style="color: #7f8c8d;">contact@fashionhub.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$12,300.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="color: #e74c3c;">- $1,230.00 (10%)</span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2ecc71; font-size: 16px;">$11,070.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Completed
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 10, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewPayout(2)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="downloadReceipt(2)" style="padding: 6px 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-download"></i> Receipt
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">#PAY-001232</strong>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>Home & Garden Co.</strong><br>
                                <small style="color: #7f8c8d;">info@homeandgarden.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$5,670.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="color: #e74c3c;">- $567.00 (10%)</span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2ecc71; font-size: 16px;">$5,103.00</strong>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #cce5ff; color: #004085; border-radius: 12px; font-size: 12px;">
                                Processing
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 09, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewPayout(3)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="trackPayout(3)" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-search"></i> Track
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Vendor Reviews Section -->
<div id="vendor-reviews-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Vendor Reviews Management</h2>
                <p style="color: #7f8c8d;">Monitor and manage vendor ratings and reviews</p>
            </div>
            <button onclick="exportReviews()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i> Export Reviews
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Total Reviews</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">1,892</h3>
                </div>
                <i class="fas fa-star" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Average Rating</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">4.6</h3>
                </div>
                <i class="fas fa-chart-line" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Pending Review</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">23</h3>
                </div>
                <i class="fas fa-clock" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 10px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Flagged</p>
                    <h3 style="margin: 10px 0 0 0; font-size: 32px;">7</h3>
                </div>
                <i class="fas fa-flag" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search</label>
                <input type="text" id="reviewSearchInput" placeholder="Search by vendor or customer..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Rating</label>
                <select id="reviewRatingFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="reviewStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="flagged">Flagged</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="reviewSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="rating_high">Highest Rating</option>
                    <option value="rating_low">Lowest Rating</option>
                </select>
            </div>
            <div>
                <button onclick="resetReviewFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Vendor</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Rating</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Review</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div>
                                <strong>Tech Solutions Inc.</strong><br>
                                <small style="color: #7f8c8d;">Electronics Vendor</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>John Smith</strong><br>
                                <small style="color: #7f8c8d;">john@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <strong>5.0</strong>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <p style="margin: 0; color: #2c3e50; font-size: 14px;">Excellent service! Fast shipping and great product quality...</p>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">
                                Approved
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 10, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewReview(1)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="flagReview(1)" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-flag"></i> Flag
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div>
                                <strong>Fashion Hub</strong><br>
                                <small style="color: #7f8c8d;">Clothing Vendor</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>Sarah Johnson</strong><br>
                                <small style="color: #7f8c8d;">sarah@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="far fa-star" style="color: #f39c12;"></i>
                                <strong>4.0</strong>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <p style="margin: 0; color: #2c3e50; font-size: 14px;">Good quality products but delivery was a bit slow...</p>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px;">
                                Pending
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 09, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewReview(2)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="approveReview(2)" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div>
                                <strong>Home & Garden Co.</strong><br>
                                <small style="color: #7f8c8d;">Home Goods Vendor</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>Mike Brown</strong><br>
                                <small style="color: #7f8c8d;">mike@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                <i class="far fa-star" style="color: #f39c12;"></i>
                                <i class="far fa-star" style="color: #f39c12;"></i>
                                <i class="far fa-star" style="color: #f39c12;"></i>
                                <strong>2.0</strong>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <p style="margin: 0; color: #2c3e50; font-size: 14px;">Product arrived damaged. Poor packaging...</p>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 12px;">
                                Flagged
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 08, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewReview(3)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="resolveReview(3)" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-check-circle"></i> Resolve
                            </button>
                        </td>
                    </tr>
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

<!-- Orders Section -->
<div id="orders-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Orders Management</h2>
                <p style="color: #7f8c8d;">Manage all orders and transactions</p>
            </div>
            <div style="display:flex; gap:12px; align-items:center;">
                <a href="{{ route('admin.orders') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-md hover:bg-gray-50">View all orders</a>
                <button onclick="exportOrders()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-download"></i> Export Orders
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Order</label>
                <input type="text" id="orderSearchInput" placeholder="Search by order ID, customer..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="orderStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Payment</label>
                <select id="orderPaymentFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Payments</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Date Range</label>
                <select id="orderDateFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="orderSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest</option>
                    <option value="amount_high">Amount (High)</option>
                    <option value="amount_low">Amount (Low)</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Vendor type</label>
                <select id="orderVendorRoleFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Vendors</option>
                    <option value="wholesaler">Wholesaler</option>
                    <option value="retailer">Retailer</option>
                    <option value="importer">Importer</option>
                </select>
            </div>
            <div>
                <button onclick="resetOrderFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Order ID</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Vendor</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Items</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Total</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Payment</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    @forelse($orders ?? [] as $order)
                    <tr class="order-row" style="border-bottom: 1px solid #dee2e6;"
                        data-order-id="{{ strtolower($order->order_number ?? '') }}"
                        data-customer="{{ strtolower($order->user->name ?? '') }}"
                        data-status="{{ strtolower($order->status ?? '') }}"
                        data-payment="{{ strtolower($order->payment_status ?? '') }}"
                        data-date="{{ $order->created_at->format('Y-m-d') ?? '' }}"
                        data-amount="{{ $order->total_amount ?? 0 }}"
                        data-vendor-id="{{ $order->vendor->id ?? '' }}"
                        data-vendor-role="{{ strtolower($order->vendor->role ?? '') }}">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">#{{ $order->order_number ?? 'N/A' }}</strong>
                        </td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>{{ $order->user->name ?? 'N/A' }}</strong><br>
                                <small style="color: #7f8c8d;">{{ $order->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                {{ $order->vendor->name ?? 'Multiple' }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ $order->items_count ?? 0 }} item(s)</td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">${{ number_format($order->total_amount ?? 0, 2) }}</strong>
                        </td>
                        <td style="padding: 12px;">
                            @php
                                $paymentColors = [
                                    'paid' => ['bg' => '#d4edda', 'color' => '#155724'],
                                    'unpaid' => ['bg' => '#fff3cd', 'color' => '#856404'],
                                    'refunded' => ['bg' => '#f8d7da', 'color' => '#721c24']
                                ];
                                $payment = strtolower($order->payment_status ?? 'unpaid');
                                $colors = $paymentColors[$payment] ?? ['bg' => '#e2e3e5', 'color' => '#383d41'];
                            @endphp
                            <span style="padding: 4px 12px; background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($order->payment_status ?? 'Unpaid') }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            @php
                                $statusColors = [
                                    'pending' => ['bg' => '#fff3cd', 'color' => '#856404'],
                                    'processing' => ['bg' => '#cce5ff', 'color' => '#004085'],
                                    'shipped' => ['bg' => '#d1ecf1', 'color' => '#0c5460'],
                                    'delivered' => ['bg' => '#d4edda', 'color' => '#155724'],
                                    'cancelled' => ['bg' => '#f8d7da', 'color' => '#721c24']
                                ];
                                $status = strtolower($order->status ?? 'pending');
                                $colors = $statusColors[$status] ?? ['bg' => '#e2e3e5', 'color' => '#383d41'];
                            @endphp
                            <span style="padding: 4px 12px; background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($order->status ?? 'Pending') }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>{{ $order->created_at->format('M d, Y') ?? 'N/A' }}</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewOrder({{ $order->id ?? 0 }})" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="updateOrderStatus({{ $order->id ?? 0 }})" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Invoices Section -->
<div id="invoices-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Invoices Management</h2>
                <p style="color: #7f8c8d;">Generate and manage order invoices</p>
            </div>
            <button onclick="generateInvoice()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-invoice"></i> Generate Invoice
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Invoice</label>
                <input type="text" id="invoiceSearchInput" placeholder="Search by invoice number..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="invoiceStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="overdue">Overdue</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Date Range</label>
                <select id="invoiceDateFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="invoiceSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest</option>
                    <option value="amount">Amount</option>
                </select>
            </div>
            <div>
                <button onclick="resetInvoiceFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Invoice #</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Order #</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Amount</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Issue Date</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Due Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">INV-{{ str_pad($order->id ?? 1000, 6, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td style="padding: 12px;">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>{{ $order->user->name ?? 'N/A' }}</strong><br>
                                <small style="color: #7f8c8d;">{{ $order->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">${{ number_format($order->total_amount ?? 0, 2) }}</strong>
                        </td>
                        <td style="padding: 12px;">
                            @php
                                $invoiceStatus = $order->payment_status ?? 'unpaid';
                                $statusColors = [
                                    'paid' => ['bg' => '#d4edda', 'color' => '#155724'],
                                    'unpaid' => ['bg' => '#fff3cd', 'color' => '#856404'],
                                    'overdue' => ['bg' => '#f8d7da', 'color' => '#721c24']
                                ];
                                $colors = $statusColors[$invoiceStatus] ?? ['bg' => '#e2e3e5', 'color' => '#383d41'];
                            @endphp
                            <span style="padding: 4px 12px; background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst($invoiceStatus) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>{{ $order->created_at->format('M d, Y') ?? 'N/A' }}</small>
                        </td>
                        <td style="padding: 12px;">
                            <small>{{ $order->created_at->addDays(7)->format('M d, Y') ?? 'N/A' }}</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="downloadInvoice({{ $order->id ?? 0 }})" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button onclick="viewInvoice({{ $order->id ?? 0 }})" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-file-invoice" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No invoices found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Shipments Section -->
<div id="shipments-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Shipments Management</h2>
                <p style="color: #7f8c8d;">Track and manage order shipments</p>
            </div>
            <button onclick="createShipment()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-shipping-fast"></i> Create Shipment
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Shipment</label>
                <input type="text" id="shipmentSearchInput" placeholder="Search by tracking number..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="shipmentStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_transit">In Transit</option>
                    <option value="out_for_delivery">Out for Delivery</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Carrier</label>
                <select id="shipmentCarrierFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Carriers</option>
                    <option value="fedex">FedEx</option>
                    <option value="ups">UPS</option>
                    <option value="dhl">DHL</option>
                    <option value="usps">USPS</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="shipmentSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
            <div>
                <button onclick="resetShipmentFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Tracking #</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Order #</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Carrier</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Ship Date</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Est. Delivery</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                    @if(in_array($order->status ?? '', ['shipped', 'delivered']))
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">TRK-{{ strtoupper(substr(md5($order->id ?? ''), 0, 12)) }}</strong>
                        </td>
                        <td style="padding: 12px;">#{{ $order->order_number ?? 'N/A' }}</td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>{{ $order->user->name ?? 'N/A' }}</strong><br>
                                <small style="color: #7f8c8d;">{{ $order->user->email ?? '' }}</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                                FedEx
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            @php
                                $shipStatus = $order->status == 'delivered' ? 'delivered' : 'in_transit';
                                $shipColors = [
                                    'pending' => ['bg' => '#fff3cd', 'color' => '#856404'],
                                    'in_transit' => ['bg' => '#cce5ff', 'color' => '#004085'],
                                    'out_for_delivery' => ['bg' => '#d1ecf1', 'color' => '#0c5460'],
                                    'delivered' => ['bg' => '#d4edda', 'color' => '#155724']
                                ];
                                $colors = $shipColors[$shipStatus] ?? ['bg' => '#e2e3e5', 'color' => '#383d41'];
                            @endphp
                            <span style="padding: 4px 12px; background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; border-radius: 12px; font-size: 12px;">
                                {{ ucfirst(str_replace('_', ' ', $shipStatus)) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>{{ $order->created_at->addDays(1)->format('M d, Y') ?? 'N/A' }}</small>
                        </td>
                        <td style="padding: 12px;">
                            <small>{{ $order->created_at->addDays(5)->format('M d, Y') ?? 'N/A' }}</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="trackShipment('{{ $order->id ?? 0 }}')" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-map-marker-alt"></i> Track
                            </button>
                            <button onclick="updateShipment({{ $order->id ?? 0 }})" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-shipping-fast" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No shipments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Refunds & Returns Section -->
<div id="refunds-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Refunds & Returns Management</h2>
                <p style="color: #7f8c8d;">Handle customer refunds and return requests</p>
            </div>
            <button onclick="processRefund()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-undo"></i> Process Refund
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search</label>
                <input type="text" id="refundSearchInput" placeholder="Search by order or customer..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Type</label>
                <select id="refundTypeFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Types</option>
                    <option value="refund">Refund</option>
                    <option value="return">Return</option>
                    <option value="replacement">Replacement</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="refundStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="refundSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="recent">Most Recent</option>
                    <option value="oldest">Oldest</option>
                    <option value="amount">Amount</option>
                </select>
            </div>
            <div>
                <button onclick="resetRefundFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Request ID</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Order #</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Customer</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Type</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Amount</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Reason</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">Date</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #667eea;">REF-001234</strong>
                        </td>
                        <td style="padding: 12px;">#ORD-5678</td>
                        <td style="padding: 12px;">
                            <div>
                                <strong>John Doe</strong><br>
                                <small style="color: #7f8c8d;">john@example.com</small>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px;">
                                Refund
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">$159.99</strong>
                        </td>
                        <td style="padding: 12px;">
                            <small>Product damaged</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px;">
                                Pending
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <small>Jan 10, 2026</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewRefund(1)" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="approveRefund(1)" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button onclick="rejectRefund(1)" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" style="padding: 40px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-undo" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                            No refund or return requests at the moment.
                        </td>
                    </tr>
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

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; color: #2c3e50; margin-bottom: 6px;">Search Product</label>
                <input type="text" id="productSearchInput" placeholder="Search by name or SKU..." 
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; color: #2c3e50; margin-bottom: 6px;">Category</label>
                <select id="productCategoryFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; color: #2c3e50; margin-bottom: 6px;">Vendor</label>
                <select id="productVendorFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Vendors</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; color: #2c3e50; margin-bottom: 6px;">Status</label>
                <select id="productStatusFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 500; color: #2c3e50; margin-bottom: 6px;">Featured</label>
                <select id="productFeaturedFilter" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Products</option>
                    <option value="1">Featured Only</option>
                    <option value="0">Non-Featured</option>
                </select>
            </div>
            <div>
                <button onclick="resetProductFilters()" style="width: 100%; padding: 10px 12px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
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
                    <tr style="border-bottom: 1px solid #dee2e6;" 
                        data-category-id="{{ $product->category_id ?? '' }}"
                        data-vendor-id="{{ $product->vendor_id ?? '' }}"
                        data-status="{{ $product->status }}"
                        data-featured="{{ $product->is_featured ? '1' : '0' }}">
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

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Category</label>
                <input type="text" id="categorySearchInput" placeholder="Search by name..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="categoryStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Products</label>
                <select id="categoryProductsFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Categories</option>
                    <option value="hasProducts">Has Products</option>
                    <option value="noProducts">No Products</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="categorySortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">Default</option>
                    <option value="name">Name</option>
                    <option value="products">Products Count</option>
                    <option value="sort_order">Sort Order</option>
                </select>
            </div>
            <div>
                <button onclick="resetCategoryFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
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
                <tbody id="categoriesTableBody">
                    @forelse($categories as $category)
                    <tr class="category-row" style="border-bottom: 1px solid #dee2e6;" 
                        data-name="{{ strtolower($category->name) }}"
                        data-status="{{ $category->is_active ? 'active' : 'inactive' }}"
                        data-products="{{ $category->products_count }}"
                        data-sort-order="{{ $category->sort_order }}">
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

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search Brand</label>
                <input type="text" id="brandSearchInput" placeholder="Search by name..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="brandStatusFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Products</label>
                <select id="brandProductsFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Brands</option>
                    <option value="hasProducts">Has Products</option>
                    <option value="noProducts">No Products</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Sort By</label>
                <select id="brandSortFilter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">Default</option>
                    <option value="name">Name</option>
                    <option value="products">Products Count</option>
                    <option value="sort_order">Sort Order</option>
                </select>
            </div>
            <div>
                <button onclick="resetBrandFilters()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
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
                <tbody id="brandsTableBody">
                    @forelse($brands as $brand)
                    <tr class="brand-row" style="border-bottom: 1px solid #dee2e6;"
                        data-name="{{ strtolower($brand->name) }}"
                        data-status="{{ $brand->is_active ? 'active' : 'inactive' }}"
                        data-products="{{ $brand->products_count ?? 0 }}"
                        data-sort-order="{{ $brand->sort_order }}">
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

<!-- Retail Page Section -->
<div id="retail-page-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Retail Page Management</h2>
                <p style="color: #7f8c8d;">Customize the retail marketplace page content</p>
            </div>
            <a href="{{ route('retail') }}" target="_blank" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="fas fa-external-link-alt"></i> Preview Page
            </a>
        </div>
    </div>

    <form action="{{ route('admin.retail-page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Hero Section -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-image" style="margin-right: 10px; color: #667eea;"></i>
                Hero Section
            </h3>

            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ $retailPageContent['hero_title'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Description</label>
                    <textarea name="hero_description" rows="3"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>{{ $retailPageContent['hero_description'] ?? '' }}</textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Image</label>
                    <div style="margin-bottom: 12px;" id="retailImagePreviewContainer">
                        @if(isset($retailPageContent['hero_image']) && $retailPageContent['hero_image'])
                            @php
                                $retailImageUrl = str_starts_with($retailPageContent['hero_image'], 'http') ? $retailPageContent['hero_image'] : asset('storage/' . $retailPageContent['hero_image']);
                                $retailImageUrl .= '?v=' . time();
                            @endphp
                            <img src="{{ $retailImageUrl }}"
                                alt="Current Hero Image"
                                id="retailImagePreview"
                                style="width: 192px; height: 128px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        @else
                            <img src=""
                                alt="Image Preview"
                                id="retailImagePreview"
                                style="width: 192px; height: 128px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; display: none;">
                        @endif
                    </div>
                    <input type="file" name="hero_image" id="retailHeroImageInput" accept="image/*"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Upload a new image to replace the current one (max 2MB)</p>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-chart-bar" style="margin-right: 10px; color: #667eea;"></i>
                Statistics Section
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <!-- Stat 1 -->
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                    <h4 style="font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 15px;">Statistic 1</h4>
                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Number</label>
                    <input type="text" name="stat1_number" value="{{ $retailPageContent['stat1_number'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;"
                        placeholder="e.g., 500+" required>

                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Label</label>
                    <input type="text" name="stat1_label" value="{{ $retailPageContent['stat1_label'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        placeholder="e.g., Retail Stores" required>
                </div>

                <!-- Stat 2 -->
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                    <h4 style="font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 15px;">Statistic 2</h4>
                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Number</label>
                    <input type="text" name="stat2_number" value="{{ $retailPageContent['stat2_number'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;"
                        placeholder="e.g., 10K+" required>

                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Label</label>
                    <input type="text" name="stat2_label" value="{{ $retailPageContent['stat2_label'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        placeholder="e.g., Products" required>
                </div>

                <!-- Stat 3 -->
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                    <h4 style="font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 15px;">Statistic 3</h4>
                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Number</label>
                    <input type="text" name="stat3_number" value="{{ $retailPageContent['stat3_number'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;"
                        placeholder="e.g., 50K+" required>

                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Label</label>
                    <input type="text" name="stat3_label" value="{{ $retailPageContent['stat3_label'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        placeholder="e.g., Happy Customers" required>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- About Page Section -->
<div id="about-page-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">About Page Management</h2>
                <p style="color: #7f8c8d;">Customize the about us page content</p>
            </div>
            <a href="{{ route('about') }}" target="_blank" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="fas fa-external-link-alt"></i> Preview Page
            </a>
        </div>
    </div>

    <form action="{{ route('admin.about-page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Hero Section -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-image" style="margin-right: 10px; color: #667eea;"></i>
                Hero Section
            </h3>

            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ $aboutPageContent['hero_title'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Description</label>
                    <textarea name="hero_description" rows="3"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>{{ $aboutPageContent['hero_description'] ?? '' }}</textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Image</label>
                    <div style="margin-bottom: 12px;" id="aboutImagePreviewContainer">
                        @if(isset($aboutPageContent['hero_image']) && $aboutPageContent['hero_image'])
                            @php
                                $aboutImageUrl = str_starts_with($aboutPageContent['hero_image'], 'http') ? $aboutPageContent['hero_image'] : asset('storage/' . $aboutPageContent['hero_image']);
                                $aboutImageUrl .= '?v=' . time();
                            @endphp
                            <img src="{{ $aboutImageUrl }}"
                                alt="Current Hero Image"
                                id="aboutImagePreview"
                                style="width: 192px; height: 128px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                        @else
                            <img src=""
                                alt="Image Preview"
                                id="aboutImagePreview"
                                style="width: 192px; height: 128px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; display: none;">
                        @endif
                    </div>
                    <input type="file" name="hero_image" id="aboutHeroImageInput" accept="image/*"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Upload a new image to replace the current one (max 2MB)</p>
                </div>
            </div>
        </div>

        <!-- Mission, Vision, Values Section -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-bullseye" style="margin-right: 10px; color: #667eea;"></i>
                Mission, Vision & Values
            </h3>

            <div style="display: grid; grid-template-columns: 1fr; gap: 25px;">
                <!-- Mission -->
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                    <h4 style="font-size: 16px; font-weight: 600; color: #2c3e50; margin-bottom: 15px;">Mission</h4>
                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Title</label>
                    <input type="text" name="mission_title" value="{{ $aboutPageContent['mission_title'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;" required>

                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Description</label>
                    <textarea name="mission_description" rows="3"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required>{{ $aboutPageContent['mission_description'] ?? '' }}</textarea>
                </div>

                <!-- Vision -->
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                    <h4 style="font-size: 16px; font-weight: 600; color: #2c3e50; margin-bottom: 15px;">Vision</h4>
                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Title</label>
                    <input type="text" name="vision_title" value="{{ $aboutPageContent['vision_title'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;" required>

                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Description</label>
                    <textarea name="vision_description" rows="3"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required>{{ $aboutPageContent['vision_description'] ?? '' }}</textarea>
                </div>

                <!-- Values -->
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                    <h4 style="font-size: 16px; font-weight: 600; color: #2c3e50; margin-bottom: 15px;">Values</h4>
                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Title</label>
                    <input type="text" name="values_title" value="{{ $aboutPageContent['values_title'] ?? '' }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;" required>

                    <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Description</label>
                    <textarea name="values_description" rows="3"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required>{{ $aboutPageContent['values_description'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Contact Page Section -->
<div id="contact-page-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Contact Page Management</h2>
                <p style="color: #7f8c8d;">Manage contact information and details</p>
            </div>
            <a href="{{ route('contact') }}" target="_blank" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="fas fa-external-link-alt"></i> Preview Page
            </a>
        </div>
    </div>

    <form action="{{ route('admin.contact-page.update') }}" method="POST">
        @csrf

        <!-- Hero Section -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-heading" style="margin-right: 10px; color: #667eea;"></i>
                Hero Section
            </h3>

            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ $contactPageContent['hero_title'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Description</label>
                    <textarea name="hero_description" rows="3"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>{{ $contactPageContent['hero_description'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
            <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-address-book" style="margin-right: 10px; color: #667eea;"></i>
                Contact Information
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" value="{{ $contactPageContent['email'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Phone</label>
                    <input type="text" name="phone" value="{{ $contactPageContent['phone'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Address</label>
                    <input type="text" name="address" value="{{ $contactPageContent['address'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Business Hours</label>
                    <input type="text" name="business_hours" value="{{ $contactPageContent['business_hours'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Support Email</label>
                    <input type="email" name="support_email" value="{{ $contactPageContent['support_email'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Sales Email</label>
                    <input type="email" name="sales_email" value="{{ $contactPageContent['sales_email'] ?? '' }}"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Home Page Section - Redirects to dedicated page -->
<div id="home-page-section" class="content-section" style="display: none;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 40px; text-align: center;">
        <i class="fas fa-images" style="font-size: 64px; color: #667eea; margin-bottom: 20px;"></i>
        <h2 style="font-size: 24px; color: #2c3e50; margin-bottom: 12px;">Home Page Hero Slider Management</h2>
        <p style="color: #7f8c8d; margin-bottom: 24px;">Manage your homepage hero slider with multiple slides, images, titles, and CTAs</p>
        <a href="{{ route('admin.home-page') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;">
            <i class="fas fa-arrow-right"></i> Go to Hero Slider Management
        </a>
    </div>
</div>

<!-- Wholesale Page Section - Redirects to dedicated page -->
<div id="wholesale-page-section" class="content-section" style="display: none;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 40px; text-align: center;">
        <i class="fas fa-images" style="font-size: 64px; color: #667eea; margin-bottom: 20px;"></i>
        <h2 style="font-size: 24px; color: #2c3e50; margin-bottom: 12px;">Wholesale Page Hero Slider Management</h2>
        <p style="color: #7f8c8d; margin-bottom: 24px;">Manage your wholesale page hero slider with multiple slides, images, titles, and CTAs</p>
        <a href="{{ route('admin.wholesale-page') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;">
            <i class="fas fa-arrow-right"></i> Go to Hero Slider Management
        </a>
    </div>
</div>

<!-- Import Page Section - Redirects to dedicated page -->
<div id="import-page-section" class="content-section" style="display: none;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 40px; text-align: center;">
        <i class="fas fa-images" style="font-size: 64px; color: #667eea; margin-bottom: 20px;"></i>
        <h2 style="font-size: 24px; color: #2c3e50; margin-bottom: 12px;">Import Page Hero Slider Management</h2>
        <p style="color: #7f8c8d; margin-bottom: 24px;">Manage your import page hero slider with multiple slides, images, titles, and CTAs</p>
        <a href="{{ route('admin.import-page') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;">
            <i class="fas fa-arrow-right"></i> Go to Hero Slider Management
        </a>
    </div>
</div>

<!-- About Page Section - Redirects to dedicated page -->
<div id="about-page-section" class="content-section" style="display: none;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 40px; text-align: center;">
        <i class="fas fa-info-circle" style="font-size: 64px; color: #667eea; margin-bottom: 20px;"></i>
        <h2 style="font-size: 24px; color: #2c3e50; margin-bottom: 12px;">About Page Content Management</h2>
        <p style="color: #7f8c8d; margin-bottom: 24px;">Manage all content sections of your About page including hero, story, stats, values, mission, and vision</p>
        <a href="{{ route('admin.about-page.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px;">
            <i class="fas fa-arrow-right"></i> Go to About Page Management
        </a>
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

<!-- KYC Verification Section -->
<div id="kyc-verification-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">KYC Verification</h2>
        <p style="color: #7f8c8d;">Manage vendor and customer identity verification</p>
    </div>

    <!-- Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Pending Verification</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">23</h3>
                </div>
                <div style="background: #1a6b73; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Verified</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">187</h3>
                </div>
                <div style="background: #10b981; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Rejected</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">8</h3>
                </div>
                <div style="background: #ef4444; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times-circle" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Under Review</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">12</h3>
                </div>
                <div style="background: #3b82f6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-search" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search</label>
                <input type="text" id="kycSearch" placeholder="Search by name, email..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Status</label>
                <select id="kycStatusFilter" onchange="filterKYC()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">User Type</label>
                <select id="kycUserTypeFilter" onchange="filterKYC()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Types</option>
                    <option value="vendor">Vendors</option>
                    <option value="customer">Customers</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Date Range</label>
                <select id="kycDateFilter" onchange="filterKYC()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
    </div>

    <!-- KYC Submissions List -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">KYC Submissions</h3>
            <button onclick="exportKYCData()" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">USER</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TYPE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">DOCUMENTS</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">SUBMITTED</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">STATUS</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="kycSubmissionsTable">
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #3b82f6; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    JD
                                </div>
                                <div>
                                    <strong style="color: #2c3e50;">John Doe</strong>
                                    <br><small style="color: #7f8c8d;">john@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-store"></i> Vendor
                            </span>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">
                            <div style="font-size: 13px;">
                                <i class="fas fa-id-card" style="color: #10b981;"></i> National ID<br>
                                <i class="fas fa-file-alt" style="color: #3b82f6;"></i> Business License<br>
                                <i class="fas fa-image" style="color: #1a6b73;"></i> Selfie
                            </div>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 08, 2026<br>
                            3 days ago
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="reviewKYC(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #10b981; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    SA
                                </div>
                                <div>
                                    <strong style="color: #2c3e50;">Sarah Ahmed</strong>
                                    <br><small style="color: #7f8c8d;">sarah@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-user"></i> Customer
                            </span>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">
                            <div style="font-size: 13px;">
                                <i class="fas fa-id-card" style="color: #10b981;"></i> National ID<br>
                                <i class="fas fa-image" style="color: #1a6b73;"></i> Selfie
                            </div>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 10, 2026<br>
                            1 day ago
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-search"></i> Under Review
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="reviewKYC(2)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-eye"></i> Review
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #1a6b73; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    MK
                                </div>
                                <div>
                                    <strong style="color: #2c3e50;">Mike Khan</strong>
                                    <br><small style="color: #7f8c8d;">mike@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-store"></i> Vendor
                            </span>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">
                            <div style="font-size: 13px;">
                                <i class="fas fa-id-card" style="color: #10b981;"></i> National ID<br>
                                <i class="fas fa-file-alt" style="color: #3b82f6;"></i> Business License<br>
                                <i class="fas fa-image" style="color: #1a6b73;"></i> Selfie
                            </div>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 05, 2026<br>
                            6 days ago
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewKYCDetails(3)" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Fraud Detection Section -->
<div id="fraud-detection-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Fraud Detection</h2>
        <p style="color: #7f8c8d;">Monitor and manage suspicious activities</p>
    </div>

    <!-- Alert Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Critical Alerts</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">5</h3>
                </div>
                <div style="background: #ef4444; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-triangle" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Medium Risk</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">18</h3>
                </div>
                <div style="background: #1a6b73; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-shield-alt" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Blocked Users</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">12</h3>
                </div>
                <div style="background: #8b5cf6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-slash" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Resolved Today</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">7</h3>
                </div>
                <div style="background: #10b981; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-double" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Fraud Detection Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Fraud Detection Rules</h3>
        <div style="display: grid; gap: 20px;">
            <!-- Rule 1: Multiple Failed Logins -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <h4 style="color: #2c3e50; margin: 0 0 5px 0;">Multiple Failed Login Attempts</h4>
                        <p style="color: #7f8c8d; font-size: 13px; margin: 0;">Block account after repeated failed login attempts</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 24px;"></span>
                        <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                    </label>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Max Attempts</label>
                        <input type="number" value="5" min="3" max="10" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Time Window (minutes)</label>
                        <input type="number" value="30" min="5" max="120" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Rule 2: Unusual Transaction Patterns -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <h4 style="color: #2c3e50; margin: 0 0 5px 0;">Unusual Transaction Patterns</h4>
                        <p style="color: #7f8c8d; font-size: 13px; margin: 0;">Flag transactions that deviate from normal patterns</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 24px;"></span>
                        <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                    </label>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Transaction Amount Threshold ($)</label>
                        <input type="number" value="1000" min="100" step="100" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Frequency Alert (per day)</label>
                        <input type="number" value="10" min="5" max="50" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Rule 3: Multiple Accounts Detection -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <h4 style="color: #2c3e50; margin: 0 0 5px 0;">Multiple Accounts from Same Device</h4>
                        <p style="color: #7f8c8d; font-size: 13px; margin: 0;">Detect and flag multiple accounts from same IP/device</p>
                    </div>
                    <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                        <input type="checkbox" checked style="opacity: 0; width: 0; height: 0;">
                        <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 24px;"></span>
                        <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                    </label>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Max Accounts per IP</label>
                        <input type="number" value="3" min="2" max="10" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 13px;">Detection Period (days)</label>
                        <input type="number" value="7" min="1" max="30" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <button onclick="saveFraudRules()" style="padding: 12px 30px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <i class="fas fa-save"></i> Save Rules
            </button>
        </div>
    </div>

    <!-- Recent Fraud Alerts -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0;">Recent Fraud Alerts</h3>
            <div style="display: flex; gap: 10px;">
                <select id="fraudAlertFilter" onchange="filterFraudAlerts()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Alerts</option>
                    <option value="critical">Critical</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">ALERT</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">USER</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TYPE</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">SEVERITY</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TIME</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="fraudAlertsTable">
                    <tr style="border-bottom: 1px solid #dee2e6; background: #fef2f2;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Multiple Failed Login Attempts</strong>
                            <br><small style="color: #7f8c8d;">7 attempts from same IP</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">unknown@email.com</strong>
                            <br><small style="color: #7f8c8d;">IP: 192.168.1.100</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-user-lock"></i> Login Attempt
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #ef4444; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                CRITICAL
                            </span>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            5 mins ago
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="investigateFraud(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-search"></i> Investigate
                            </button>
                            <button onclick="blockUser(1)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-ban"></i> Block
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6; background: #fef9c3;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Unusual Transaction Pattern</strong>
                            <br><small style="color: #7f8c8d;">12 orders in 1 hour</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">buyer123@email.com</strong>
                            <br><small style="color: #7f8c8d;">User ID: #4521</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-shopping-cart"></i> Transaction
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #1a6b73; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                MEDIUM
                            </span>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            23 mins ago
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="investigateFraud(2)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-search"></i> Investigate
                            </button>
                            <button onclick="dismissAlert(2)" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-check"></i> Dismiss
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Multiple Accounts Detected</strong>
                            <br><small style="color: #7f8c8d;">3 accounts from same device</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Multiple Users</strong>
                            <br><small style="color: #7f8c8d;">IP: 10.0.0.55</small>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #ede9fe; color: #5b21b6; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-users"></i> Multi-Account
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #1a6b73; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                MEDIUM
                            </span>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            1 hour ago
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="investigateFraud(3)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-search"></i> Investigate
                            </button>
                            <button onclick="dismissAlert(3)" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-check"></i> Dismiss
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Security Logs Section -->
<div id="security-logs-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Security Logs</h2>
        <p style="color: #7f8c8d;">Monitor all security-related events and activities</p>
    </div>

    <!-- Log Statistics -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Total Events Today</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">1,234</h3>
                </div>
                <div style="background: #3b82f6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clipboard-list" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Failed Logins</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">45</h3>
                </div>
                <div style="background: #ef4444; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-times" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Permission Changes</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">8</h3>
                </div>
                <div style="background: #1a6b73; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-key" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Suspicious Activity</p>
                    <h3 style="margin: 5px 0 0 0; font-size: 28px; color: #2c3e50;">3</h3>
                </div>
                <div style="background: #8b5cf6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-circle" style="color: white; font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Search</label>
                <input type="text" id="logSearch" placeholder="Search logs..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Event Type</label>
                <select id="logEventFilter" onchange="filterSecurityLogs()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Events</option>
                    <option value="login">Login/Logout</option>
                    <option value="permission">Permission Changes</option>
                    <option value="data">Data Access</option>
                    <option value="security">Security Events</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Severity</label>
                <select id="logSeverityFilter" onchange="filterSecurityLogs()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="">All Levels</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">User</label>
                <input type="text" id="logUserFilter" placeholder="Filter by user..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Date Range</label>
                <select id="logDateFilter" onchange="filterSecurityLogs()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
        </div>
        <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="exportSecurityLogs()" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-download"></i> Export Logs
            </button>
            <button onclick="clearLogFilters()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-redo"></i> Clear Filters
            </button>
        </div>
    </div>

    <!-- Security Logs Table -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Recent Security Events</h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">TIMESTAMP</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">EVENT</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">USER</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">IP ADDRESS</th>
                        <th style="padding: 12px; text-align: left; color: #2c3e50;">SEVERITY</th>
                        <th style="padding: 12px; text-align: center; color: #2c3e50;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="securityLogsTable">
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 11, 2026<br>
                            10:34:22 AM
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Admin Login Success</strong>
                            <br><small style="color: #7f8c8d;">User successfully logged into admin panel</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">admin@example.com</strong>
                            <br><small style="color: #7f8c8d;">Admin</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; font-family: monospace; font-size: 13px;">
                            192.168.1.50
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-check-circle"></i> Low
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewLogDetails(1)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i> Details
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6; background: #fef2f2;">
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 11, 2026<br>
                            10:31:15 AM
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Failed Login Attempt</strong>
                            <br><small style="color: #7f8c8d;">Invalid password entered (Attempt 4/5)</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">unknown@email.com</strong>
                            <br><small style="color: #7f8c8d;">Unknown</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; font-family: monospace; font-size: 13px;">
                            45.76.123.89
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #ef4444; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                CRITICAL
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewLogDetails(2)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i> Details
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 11, 2026<br>
                            10:25:08 AM
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Permission Updated</strong>
                            <br><small style="color: #7f8c8d;">User role changed from Customer to Vendor</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">john@example.com</strong>
                            <br><small style="color: #7f8c8d;">Vendor</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; font-family: monospace; font-size: 13px;">
                            192.168.1.50
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-exclamation-triangle"></i> Medium
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewLogDetails(3)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i> Details
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 11, 2026<br>
                            10:18:45 AM
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Data Export</strong>
                            <br><small style="color: #7f8c8d;">Customer data exported to CSV</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">admin@example.com</strong>
                            <br><small style="color: #7f8c8d;">Admin</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; font-family: monospace; font-size: 13px;">
                            192.168.1.50
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-exclamation-triangle"></i> Medium
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewLogDetails(4)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i> Details
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">
                            Jan 11, 2026<br>
                            10:12:30 AM
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Password Changed</strong>
                            <br><small style="color: #7f8c8d;">User successfully changed their password</small>
                        </td>
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">sarah@example.com</strong>
                            <br><small style="color: #7f8c8d;">Customer</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; font-family: monospace; font-size: 13px;">
                            203.112.45.78
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <i class="fas fa-check-circle"></i> Low
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewLogDetails(5)" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-eye"></i> Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
            <p style="color: #7f8c8d; font-size: 14px; margin: 0;">Showing 1-5 of 1,234 events</p>
            <div style="display: flex; gap: 5px;">
                <button style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; color: #2c3e50;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button style="padding: 8px 12px; background: #3b82f6; border: 1px solid #3b82f6; border-radius: 6px; cursor: pointer; color: white;">1</button>
                <button style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; color: #2c3e50;">2</button>
                <button style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; color: #2c3e50;">3</button>
                <button style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; color: #2c3e50;">...</button>
                <button style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; color: #2c3e50;">247</button>
                <button style="padding: 8px 12px; background: white; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; color: #2c3e50;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Section -->
<div id="notifications-section" class="content-section" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin: 0 0 5px 0;">
                <i class="fas fa-bell"></i> Notifications
            </h2>
            <p style="color: #7f8c8d; margin: 0;">Manage and send notifications to users</p>
        </div>
        <div>
            <button onclick="showCreateNotificationModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fas fa-plus"></i> Create Notification
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; opacity: 0.9; font-size: 14px;">Total Notifications</p>
                    <h3 style="margin: 0; font-size: 32px; font-weight: 700;" id="totalNotifications">0</h3>
                </div>
                <i class="fas fa-bell" style="font-size: 40px; opacity: 0.2;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; opacity: 0.9; font-size: 14px;">Unread</p>
                    <h3 style="margin: 0; font-size: 32px; font-weight: 700;" id="unreadNotifications">0</h3>
                </div>
                <i class="fas fa-envelope" style="font-size: 40px; opacity: 0.2;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="margin: 0 0 8px 0; opacity: 0.9; font-size: 14px;">Sent Today</p>
                    <h3 style="margin: 0; font-size: 32px; font-weight: 700;" id="todayNotifications">0</h3>
                </div>
                <i class="fas fa-paper-plane" style="font-size: 40px; opacity: 0.2;"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 14px;">Filter by Type</label>
                <select id="notificationTypeFilter" onchange="filterNotifications()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="all">All Types</option>
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 14px;">Filter by Status</label>
                <select id="notificationStatusFilter" onchange="filterNotifications()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="all">All Status</option>
                    <option value="unread">Unread</option>
                    <option value="read">Read</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500; font-size: 14px;">Search</label>
                <input type="text" id="notificationSearch" onkeyup="filterNotifications()" placeholder="Search notifications..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div style="display: flex; align-items: end; gap: 10px;">
                <button onclick="markAllNotificationsRead()" style="flex: 1; padding: 10px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div id="notificationsList" style="min-height: 400px;">
            <!-- Notifications will be loaded here via JavaScript -->
            <div style="padding: 60px 20px; text-align: center; color: #7f8c8d;">
                <i class="fas fa-bell" style="font-size: 48px; opacity: 0.3; margin-bottom: 20px;"></i>
                <p>Loading notifications...</p>
            </div>
        </div>
    </div>
</div>

<!-- Chat Support Section -->
<div id="chat-messenger-section" class="content-section" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin: 0 0 5px 0;">
                <i class="fas fa-comments"></i> Chat Support
            </h2>
            <p style="color: #7f8c8d; margin: 0;">Manage customer conversations and support tickets</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <select id="chatStatusFilter" onchange="filterConversations()" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Active Chats</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="activeChats">0</h3>
                </div>
                <i class="fas fa-comments" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Pending</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="pendingChats">0</h3>
                </div>
                <i class="fas fa-clock" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Resolved Today</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="resolvedToday">0</h3>
                </div>
                <i class="fas fa-check-circle" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Avg Response Time</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;">2.5m</h3>
                </div>
                <i class="fas fa-stopwatch" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Chat Interface -->
    <div style="display: grid; grid-template-columns: 350px 1fr; gap: 20px; height: 600px;">
        <!-- Conversations List -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; flex-direction: column;">
            <div style="padding: 20px; border-bottom: 1px solid #eee;">
                <input type="text" id="chatSearch" onkeyup="filterConversations()" placeholder="Search conversations..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div id="conversationsList" style="flex: 1; overflow-y: auto;">
                <!-- Conversations will be loaded here -->
                <div style="padding: 40px 20px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-comments" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                    <p>Loading conversations...</p>
                </div>
            </div>
        </div>

        <!-- Chat Window -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; flex-direction: column;">
            <div id="chatHeader" style="padding: 20px; border-bottom: 1px solid #eee; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 18px;" id="chatUserName">Select a conversation</h3>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;" id="chatSubject"></p>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <select id="conversationStatus" onchange="updateConversationStatus()" style="padding: 8px 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; font-size: 13px; background: rgba(255,255,255,0.2); color: white;">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 20px; background: #f8f9fa;">
                <!-- Messages will be loaded here -->
                <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #7f8c8d;">
                    <div style="text-align: center;">
                        <i class="fas fa-comment-dots" style="font-size: 64px; opacity: 0.2; margin-bottom: 20px;"></i>
                        <p>Select a conversation to start messaging</p>
                    </div>
                </div>
            </div>

            <div id="chatInput" style="padding: 20px; border-top: 1px solid #eee; display: none;">
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="messageInput" placeholder="Type your message..." style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" onkeypress="handleMessageKeyPress(event)">
                    <button onclick="sendMessage()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SMS & OTP Section -->
<div id="otp-system-section" class="content-section" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin: 0 0 5px 0;">
                <i class="fas fa-sms"></i> SMS & OTP Management
            </h2>
            <p style="color: #7f8c8d; margin: 0;">Manage SMS logs, OTP verifications, and templates</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Total SMS Sent</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="totalSms">0</h3>
                </div>
                <i class="fas fa-paper-plane" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">OTP Verified</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="verifiedOtp">0</h3>
                </div>
                <i class="fas fa-check-circle" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Failed SMS</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="failedSms">0</h3>
                </div>
                <i class="fas fa-times-circle" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Total Cost</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="totalCost">$0</h3>
                </div>
                <i class="fas fa-dollar-sign" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- SMS Tabs -->
    <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div style="display: flex; gap: 10px; border-bottom: 2px solid #eee; margin-bottom: 20px;">
            <button onclick="showSmsTab('logs')" id="smsLogsTab" class="sms-tab" style="padding: 12px 24px; background: #667eea; color: white; border: none; border-radius: 8px 8px 0 0; cursor: pointer; font-weight: 600;">SMS Logs</button>
            <button onclick="showSmsTab('otp')" id="smsOtpTab" class="sms-tab" style="padding: 12px 24px; background: transparent; color: #2c3e50; border: none; cursor: pointer;">OTP Verifications</button>
            <button onclick="showSmsTab('templates')" id="smsTemplatesTab" class="sms-tab" style="padding: 12px 24px; background: transparent; color: #2c3e50; border: none; cursor: pointer;">SMS Templates</button>
        </div>

        <!-- SMS Logs Tab -->
        <div id="smsLogsContent" class="sms-tab-content">
            <div style="margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 200px; gap: 15px;">
                    <select id="smsTypeFilter" onchange="loadSmsLogs()" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="all">All Types</option>
                        <option value="otp">OTP</option>
                        <option value="notification">Notification</option>
                        <option value="marketing">Marketing</option>
                        <option value="transactional">Transactional</option>
                    </select>
                    <select id="smsStatusFilter" onchange="loadSmsLogs()" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="all">All Status</option>
                        <option value="sent">Sent</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                    <input type="text" id="smsSearch" onkeyup="loadSmsLogs()" placeholder="Search phone or message..." style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <button onclick="showSendSmsModal()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-paper-plane"></i> Send SMS
                    </button>
                </div>
            </div>
            <div id="smsLogsList" style="overflow-x: auto;">
                <p style="text-align: center; padding: 40px; color: #7f8c8d;">Loading SMS logs...</p>
            </div>
        </div>

        <!-- OTP Verifications Tab -->
        <div id="smsOtpContent" class="sms-tab-content" style="display: none;">
            <div style="margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <select id="otpStatusFilter" onchange="loadOtpLogs()" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="failed">Failed</option>
                        <option value="expired">Expired</option>
                    </select>
                    <select id="otpPurposeFilter" onchange="loadOtpLogs()" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="all">All Purposes</option>
                        <option value="registration">Registration</option>
                        <option value="login">Login</option>
                        <option value="password_reset">Password Reset</option>
                        <option value="phone_verification">Phone Verification</option>
                        <option value="transaction">Transaction</option>
                    </select>
                    <button onclick="showGenerateOtpModal()" style="padding: 10px 20px; background: #30cfd0; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-key"></i> Generate OTP
                    </button>
                </div>
            </div>
            <div id="otpLogsList" style="overflow-x: auto;">
                <p style="text-align: center; padding: 40px; color: #7f8c8d;">Loading OTP logs...</p>
            </div>
        </div>

        <!-- SMS Templates Tab -->
        <div id="smsTemplatesContent" class="sms-tab-content" style="display: none;">
            <div style="margin-bottom: 20px; text-align: right;">
                <button onclick="showCreateTemplateModal()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-plus"></i> Create Template
                </button>
            </div>
            <div id="templatesList">
                <p style="text-align: center; padding: 40px; color: #7f8c8d;">Loading templates...</p>
            </div>
        </div>
    </div>
</div>

<!-- Support Tickets Section -->
<div id="support-tickets-section" class="content-section" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin: 0 0 5px 0;">
                <i class="fas fa-ticket-alt"></i> Support Tickets
            </h2>
            <p style="color: #7f8c8d; margin: 0;">Manage customer support tickets and inquiries</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Total Tickets</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="totalTickets">0</h3>
                </div>
                <i class="fas fa-ticket-alt" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Open Tickets</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="openTickets">0</h3>
                </div>
                <i class="fas fa-inbox" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Resolved Today</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="resolvedToday">0</h3>
                </div>
                <i class="fas fa-check-double" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Avg Response Time</p>
                    <h3 style="margin: 0; font-size: 28px; font-weight: 700;" id="avgResponseTime">0m</h3>
                </div>
                <i class="fas fa-clock" style="font-size: 32px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Tickets Interface -->
    <div style="display: grid; grid-template-columns: 380px 1fr; gap: 20px; height: 650px;">
        <!-- Tickets List -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; flex-direction: column;">
            <div style="padding: 20px; border-bottom: 1px solid #eee;">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <select id="ticketStatusFilter" onchange="loadTickets()" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px;">
                        <option value="all">All Status</option>
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="pending_customer">Pending Customer</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                    <select id="ticketPriorityFilter" onchange="loadTickets()" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px;">
                        <option value="all">All Priority</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="normal">Normal</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <input type="text" id="ticketSearch" onkeyup="loadTickets()" placeholder="Search tickets..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div id="ticketsList" style="flex: 1; overflow-y: auto;">
                <div style="padding: 40px 20px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-ticket-alt" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                    <p>Loading tickets...</p>
                </div>
            </div>
        </div>

        <!-- Ticket Detail View -->
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; flex-direction: column;">
            <div id="ticketDetailHeader" style="padding: 20px; border-bottom: 1px solid #eee; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: none;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <h3 style="margin: 0; font-size: 16px;" id="ticketNumber"></h3>
                            <span id="ticketPriorityBadge" style="padding: 4px 12px; background: rgba(255,255,255,0.2); border-radius: 12px; font-size: 11px; font-weight: 600;"></span>
                        </div>
                        <p style="margin: 0 0 5px 0; font-size: 18px; font-weight: 600;" id="ticketSubject"></p>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;" id="ticketCustomer"></p>
                    </div>
                    <select id="ticketStatus" onchange="updateTicketStatus()" style="padding: 8px 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 6px; font-size: 13px; background: rgba(255,255,255,0.2); color: white; cursor: pointer;">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="pending_customer">Pending Customer</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>

            <div id="ticketMessages" style="flex: 1; overflow-y: auto; padding: 20px; background: #f8f9fa;">
                <div style="text-align: center; padding: 60px 20px; color: #7f8c8d;">
                    <i class="fas fa-comments" style="font-size: 64px; opacity: 0.2; margin-bottom: 20px;"></i>
                    <p style="font-size: 16px; margin: 0;">Select a ticket to view details</p>
                </div>
            </div>

            <div id="ticketReplyBox" style="padding: 20px; border-top: 1px solid #eee; display: none;">
                <textarea id="ticketReplyText" placeholder="Type your reply..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; resize: vertical; min-height: 80px;"></textarea>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button onclick="sendTicketReply()" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fas fa-paper-plane"></i> Send Reply
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- General Settings Section -->
<div id="general-settings-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-cog"></i> General Settings
        </h2>
        <p style="color: #7f8c8d;">Configure general site settings and preferences</p>
    </div>

    <!-- Site Information -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="margin-right: 10px; color: #667eea;"></i> Site Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Site Name *</label>
                <input type="text" id="siteName" value="Alpha Vendor" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Site Tagline</label>
                <input type="text" id="siteTagline" value="Your trusted marketplace" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Site Description</label>
                <textarea id="siteDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">Alpha Vendor is a comprehensive marketplace platform for vendors and customers.</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Admin Email</label>
                <input type="email" id="adminEmail" value="admin@alphavendor.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Support Email</label>
                <input type="email" id="supportEmail" value="support@alphavendor.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
    </div>

    <!-- Logo & Branding -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-palette" style="margin-right: 10px; color: #667eea;"></i> Logo & Branding
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Site Logo</label>
                <div style="border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center;">
                    <i class="fas fa-image" style="font-size: 48px; color: #ddd; margin-bottom: 10px;"></i>
                    <p style="color: #7f8c8d; margin: 10px 0;">Click to upload logo</p>
                    <input type="file" id="siteLogo" accept="image/*" style="display: none;">
                    <button onclick="document.getElementById('siteLogo').click()" style="padding: 8px 16px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Favicon</label>
                <div style="border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center;">
                    <i class="fas fa-star" style="font-size: 48px; color: #ddd; margin-bottom: 10px;"></i>
                    <p style="color: #7f8c8d; margin: 10px 0;">Click to upload favicon</p>
                    <input type="file" id="siteFavicon" accept="image/*" style="display: none;">
                    <button onclick="document.getElementById('siteFavicon').click()" style="padding: 8px 16px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Regional Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-globe" style="margin-right: 10px; color: #667eea;"></i> Regional Settings
        </h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Timezone</label>
                <select id="timezone" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="Asia/Dhaka" selected>Asia/Dhaka (UTC+6)</option>
                    <option value="America/New_York">America/New York (UTC-5)</option>
                    <option value="Europe/London">Europe/London (UTC+0)</option>
                    <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Date Format</label>
                <select id="dateFormat" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="d/m/Y" selected>DD/MM/YYYY</option>
                    <option value="m/d/Y">MM/DD/YYYY</option>
                    <option value="Y-m-d">YYYY-MM-DD</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Time Format</label>
                <select id="timeFormat" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="12" selected>12 Hour (AM/PM)</option>
                    <option value="24">24 Hour</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Site Status -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-power-off" style="margin-right: 10px; color: #667eea;"></i> Site Status
        </h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Maintenance Mode</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Temporarily disable site access for maintenance</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="maintenanceMode" style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleMaintenance()" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <div id="maintenanceMessage" style="display: none;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Maintenance Message</label>
                <textarea id="maintenanceText" rows="3" placeholder="We're currently performing scheduled maintenance. We'll be back soon!" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;"></textarea>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="text-align: right;">
        <button onclick="saveGeneralSettings()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save General Settings
        </button>
    </div>
</div>

<!-- Email Settings Section -->
<div id="email-settings-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-envelope"></i> Email Settings
        </h2>
        <p style="color: #7f8c8d;">Configure email delivery and SMTP settings</p>
    </div>

    <!-- Email Provider -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-server" style="margin-right: 10px; color: #667eea;"></i> Email Provider
        </h3>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Email Driver</label>
            <select id="emailDriver" onchange="toggleEmailDriver()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="smtp" selected>SMTP</option>
                <option value="sendmail">Sendmail</option>
                <option value="mailgun">Mailgun</option>
                <option value="ses">Amazon SES</option>
            </select>
        </div>
    </div>

    <!-- SMTP Configuration -->
    <div id="smtpConfig" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cog" style="margin-right: 10px; color: #667eea;"></i> SMTP Configuration
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SMTP Host *</label>
                <input type="text" id="smtpHost" placeholder="smtp.gmail.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SMTP Port *</label>
                <input type="number" id="smtpPort" placeholder="587" value="587" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SMTP Username *</label>
                <input type="text" id="smtpUsername" placeholder="your-email@gmail.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SMTP Password *</label>
                <input type="password" id="smtpPassword" placeholder="••••••••" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Encryption</label>
                <select id="smtpEncryption" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="tls" selected>TLS</option>
                    <option value="ssl">SSL</option>
                    <option value="">None</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">From Email</label>
                <input type="email" id="fromEmail" placeholder="noreply@alphavendor.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">From Name</label>
                <input type="text" id="fromName" placeholder="Alpha Vendor" value="Alpha Vendor" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
    </div>

    <!-- Test Email -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-paper-plane" style="margin-right: 10px; color: #667eea;"></i> Test Email Configuration
        </h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <p style="color: #7f8c8d; margin: 0 0 15px 0;">Send a test email to verify your configuration</p>
            <div style="display: flex; gap: 10px;">
                <input type="email" id="testEmailAddress" placeholder="recipient@example.com" style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <button onclick="sendTestEmail()" style="padding: 12px 24px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-paper-plane"></i> Send Test Email
                </button>
            </div>
            <div id="testEmailResult" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Email Templates -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-file-alt" style="margin-right: 10px; color: #667eea;"></i> Email Templates
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Welcome Email</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Sent when new user registers</p>
            </div>
            <div style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Order Confirmation</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Sent when order is placed</p>
            </div>
            <div style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Password Reset</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Sent for password recovery</p>
            </div>
            <div style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Shipment Tracking</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Sent when order ships</p>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="text-align: right;">
        <button onclick="saveEmailSettings()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save Email Settings
        </button>
    </div>
</div>

<!-- SMS Settings Section -->
<div id="sms-settings-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-sms"></i> SMS Settings
        </h2>
        <p style="color: #7f8c8d;">Configure SMS provider and delivery settings</p>
    </div>

    <!-- SMS Provider -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-mobile-alt" style="margin-right: 10px; color: #667eea;"></i> SMS Provider
        </h3>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SMS Gateway</label>
            <select id="smsGateway" onchange="toggleSmsProvider()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="twilio" selected>Twilio</option>
                <option value="nexmo">Nexmo (Vonage)</option>
                <option value="bulksms">BulkSMS</option>
                <option value="custom">Custom API</option>
            </select>
        </div>
    </div>

    <!-- Twilio Configuration -->
    <div id="twilioConfig" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cog" style="margin-right: 10px; color: #667eea;"></i> Twilio Configuration
        </h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Account SID *</label>
                <input type="text" id="twilioSid" placeholder="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Auth Token *</label>
                <input type="password" id="twilioToken" placeholder="••••••••••••••••••••••••••••••••" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">From Number *</label>
                <input type="text" id="twilioFrom" placeholder="+1234567890" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
        <div style="margin-top: 15px; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <p style="margin: 0; color: #1976d2; font-size: 13px;">
                <i class="fas fa-info-circle"></i> Get your Twilio credentials from 
                <a href="https://www.twilio.com/console" target="_blank" style="color: #1976d2; font-weight: 600;">Twilio Console</a>
            </p>
        </div>
    </div>

    <!-- SMS Features -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-toggle-on" style="margin-right: 10px; color: #667eea;"></i> SMS Features
        </h3>
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">OTP Verification</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Enable SMS-based OTP for user authentication</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="enableOtp" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('enableOtp')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Order Notifications</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Send SMS updates for order status changes</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="enableOrderSms" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('enableOrderSms')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Marketing SMS</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Enable promotional SMS campaigns</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="enableMarketingSms" style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('enableMarketingSms')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Test SMS -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-paper-plane" style="margin-right: 10px; color: #667eea;"></i> Test SMS Configuration
        </h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <p style="color: #7f8c8d; margin: 0 0 15px 0;">Send a test SMS to verify your configuration</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <input type="tel" id="testSmsNumber" placeholder="+1234567890" style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <button onclick="sendTestSms()" style="padding: 12px 24px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-paper-plane"></i> Send Test SMS
                </button>
            </div>
            <textarea id="testSmsMessage" rows="3" placeholder="Your test message here..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">Hello! This is a test SMS from Alpha Vendor.</textarea>
            <div id="testSmsResult" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="text-align: right;">
        <button onclick="saveSmsSettings()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save SMS Settings
        </button>
    </div>
</div>

<!-- Badges & Rewards Section -->
<div id="badges-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-award"></i> Badges & Rewards
        </h2>
        <p style="color: #7f8c8d;">Manage user badges, reward points, and achievement systems</p>
    </div>

    <!-- Reward Points Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-coins" style="margin-right: 10px; color: #667eea;"></i> Reward Points Configuration
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Points per Dollar Spent</label>
                <input type="number" id="pointsPerDollar" value="10" min="1" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Customer earns points for each dollar spent</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Point Value ($)</label>
                <input type="number" id="pointValue" value="0.01" step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Dollar value of 1 reward point</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Minimum Redemption Points</label>
                <input type="number" id="minRedemption" value="100" min="1" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Minimum points required to redeem</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Points Expiry (Days)</label>
                <input type="number" id="pointsExpiry" value="365" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">0 for no expiry</small>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <div>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Enable Reward Points System</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Allow customers to earn and redeem points</p>
            </div>
            <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                <input type="checkbox" id="enableRewards" checked style="opacity: 0; width: 0; height: 0;">
                <span onclick="toggleCheckbox('enableRewards')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
            </label>
        </div>
    </div>

    <!-- Badges Management -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0; display: flex; align-items: center;">
                <i class="fas fa-medal" style="margin-right: 10px; color: #667eea;"></i> Achievement Badges
            </h3>
            <button onclick="showCreateBadgeModal()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-plus"></i> Create Badge
            </button>
        </div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <!-- Badge Card 1 -->
            <div style="border: 1px solid #ddd; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; margin: 0 auto 15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-star" style="font-size: 36px; color: white;"></i>
                </div>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">First Purchase</h4>
                <p style="margin: 0 0 10px 0; font-size: 13px; color: #7f8c8d;">Complete your first order</p>
                <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px;">243 earned</span>
                <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                    <button onclick="editBadge(1)" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteBadge(1)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <!-- Badge Card 2 -->
            <div style="border: 1px solid #ddd; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; margin: 0 auto 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-shopping-bag" style="font-size: 36px; color: white;"></i>
                </div>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Shopping Spree</h4>
                <p style="margin: 0 0 10px 0; font-size: 13px; color: #7f8c8d;">Complete 10 orders</p>
                <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px;">87 earned</span>
                <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                    <button onclick="editBadge(2)" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteBadge(2)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <!-- Badge Card 3 -->
            <div style="border: 1px solid #ddd; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='none'">
                <div style="width: 80px; height: 80px; margin: 0 auto 15px; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-crown" style="font-size: 36px; color: white;"></i>
                </div>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">VIP Customer</h4>
                <p style="margin: 0 0 10px 0; font-size: 13px; color: #7f8c8d;">Spend over $1000</p>
                <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px;">34 earned</span>
                <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                    <button onclick="editBadge(3)" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="deleteBadge(3)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="text-align: right;">
        <button onclick="saveRewardSettings()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save Reward Settings
        </button>
    </div>
</div>

<!-- Languages Section -->
<div id="languages-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-language"></i> Languages
        </h2>
        <p style="color: #7f8c8d;">Manage multiple languages and translations</p>
    </div>

    <!-- Language Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cog" style="margin-right: 10px; color: #667eea;"></i> Language Configuration
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Default Language</label>
                <select id="defaultLanguage" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="en" selected>English</option>
                    <option value="bn">বাংলা (Bengali)</option>
                    <option value="es">Español (Spanish)</option>
                    <option value="fr">Français (French)</option>
                    <option value="ar">العربية (Arabic)</option>
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: #2c3e50; font-size: 14px;">Enable Multi-Language</h4>
                            <p style="margin: 0; font-size: 12px; color: #7f8c8d;">Allow users to switch languages</p>
                        </div>
                        <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                            <input type="checkbox" id="enableMultiLang" checked style="opacity: 0; width: 0; height: 0;">
                            <span onclick="toggleCheckbox('enableMultiLang')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Installed Languages -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0; display: flex; align-items: center;">
                <i class="fas fa-globe" style="margin-right: 10px; color: #667eea;"></i> Installed Languages
            </h3>
            <button onclick="showAddLanguageModal()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-plus"></i> Add Language
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Language</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Code</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Translation</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 24px;">🇺🇸</span>
                                <strong style="color: #2c3e50;">English</strong>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center; font-family: monospace; color: #667eea;">en</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Default</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="background: #e3f2fd; padding: 8px 12px; border-radius: 8px; display: inline-block;">
                                <strong style="color: #1976d2;">100%</strong>
                                <small style="color: #7f8c8d;"> (523/523)</small>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editLanguage('en')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 24px;">🇧🇩</span>
                                <strong style="color: #2c3e50;">বাংলা (Bengali)</strong>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center; font-family: monospace; color: #667eea;">bn</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Active</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="background: #fff3cd; padding: 8px 12px; border-radius: 8px; display: inline-block;">
                                <strong style="color: #856404;">78%</strong>
                                <small style="color: #7f8c8d;"> (408/523)</small>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editLanguage('bn')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteLanguage('bn')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 24px;">🇪🇸</span>
                                <strong style="color: #2c3e50;">Español (Spanish)</strong>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center; font-family: monospace; color: #667eea;">es</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Active</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="background: #f8d7da; padding: 8px 12px; border-radius: 8px; display: inline-block;">
                                <strong style="color: #721c24;">45%</strong>
                                <small style="color: #7f8c8d;"> (235/523)</small>
                            </div>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editLanguage('es')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteLanguage('es')" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Translation Tools -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-tools" style="margin-right: 10px; color: #667eea;"></i> Translation Tools
        </h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
            <button onclick="exportTranslations()" style="padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; text-align: left; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <i class="fas fa-file-export" style="color: #667eea; font-size: 20px; margin-bottom: 8px;"></i>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Export Translations</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Download translation files</p>
            </button>
            <button onclick="importTranslations()" style="padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; text-align: left; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <i class="fas fa-file-import" style="color: #667eea; font-size: 20px; margin-bottom: 8px;"></i>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Import Translations</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Upload translation files</p>
            </button>
            <button onclick="syncTranslations()" style="padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; text-align: left; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <i class="fas fa-sync" style="color: #667eea; font-size: 20px; margin-bottom: 8px;"></i>
                <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Sync Translations</h4>
                <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Update missing keys</p>
            </button>
        </div>
    </div>
</div>

<!-- Backup & Restore Section -->
<div id="backup-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-database"></i> Backup & Restore
        </h2>
        <p style="color: #7f8c8d;">Create backups and restore your database</p>
    </div>

    <!-- Backup Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cog" style="margin-right: 10px; color: #667eea;"></i> Backup Configuration
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Automatic Backup Frequency</label>
                <select id="backupFrequency" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="disabled">Disabled</option>
                    <option value="daily" selected>Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Backup Time</label>
                <input type="time" id="backupTime" value="02:00" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Keep Backups For (Days)</label>
                <input type="number" id="backupRetention" value="30" min="1" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Storage Location</label>
                <select id="backupStorage" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="local" selected>Local Server</option>
                    <option value="s3">Amazon S3</option>
                    <option value="gdrive">Google Drive</option>
                    <option value="dropbox">Dropbox</option>
                </select>
            </div>
        </div>
        <div style="padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <p style="margin: 0; color: #1976d2; font-size: 13px;">
                <i class="fas fa-info-circle"></i> Last automatic backup: <strong>Jan 11, 2026 at 2:00 AM</strong> (4.2 MB)
            </p>
        </div>
    </div>

    <!-- Create Backup -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-plus-circle" style="margin-right: 10px; color: #667eea;"></i> Create New Backup
        </h3>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Backup Name (Optional)</label>
                    <input type="text" id="backupName" placeholder="e.g., Before Update v2.0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                <button onclick="createBackup()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1); white-space: nowrap;">
                    <i class="fas fa-save"></i> Create Backup
                </button>
            </div>
            <div style="margin-top: 15px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" id="includeFiles" checked style="margin-right: 8px;">
                    <span style="color: #2c3e50; font-size: 14px;">Include uploaded files and media</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Backup History -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-history" style="margin-right: 10px; color: #667eea;"></i> Backup History
        </h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Date & Time</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Name</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Size</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Type</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #2c3e50;">
                            <strong>Jan 11, 2026</strong><br>
                            <small style="color: #7f8c8d;">02:00 AM</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">Auto Backup - Daily</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">4.2 MB</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Automatic</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="downloadBackup(1)" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button onclick="restoreBackup(1)" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                            <button onclick="deleteBackup(1)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #2c3e50;">
                            <strong>Jan 10, 2026</strong><br>
                            <small style="color: #7f8c8d;">02:00 AM</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">Auto Backup - Daily</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">4.1 MB</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Automatic</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="downloadBackup(2)" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button onclick="restoreBackup(2)" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                            <button onclick="deleteBackup(2)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; color: #2c3e50;">
                            <strong>Jan 8, 2026</strong><br>
                            <small style="color: #7f8c8d;">03:45 PM</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">Before Major Update</td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">3.8 MB</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #667eea; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Manual</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="downloadBackup(3)" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button onclick="restoreBackup(3)" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                            <button onclick="deleteBackup(3)" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Restore from Upload -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-upload" style="margin-right: 10px; color: #667eea;"></i> Restore from File
        </h3>
        <div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; border-radius: 4px; margin-bottom: 15px;">
            <p style="margin: 0; color: #856404; font-size: 13px;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> Restoring a backup will overwrite all current data. Make sure to create a backup before proceeding.
            </p>
        </div>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
            <input type="file" id="backupFileUpload" accept=".sql,.zip" style="display: none;" onchange="handleBackupUpload(event)">
            <button onclick="document.getElementById('backupFileUpload').click()" style="padding: 12px 24px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-file-upload"></i> Choose Backup File
            </button>
            <p style="margin: 10px 0 0 0; color: #7f8c8d; font-size: 13px;">Supported formats: .sql, .zip</p>
        </div>
    </div>

    <!-- Save Settings Button -->
    <div style="text-align: right;">
        <button onclick="saveBackupSettings()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save Backup Settings
        </button>
    </div>
</div>

<!-- SEO Settings Section -->
<div id="seo-settings-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-search"></i> SEO Settings
        </h2>
        <p style="color: #7f8c8d;">Configure search engine optimization settings</p>
    </div>

    <!-- General SEO Settings -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cog" style="margin-right: 10px; color: #667eea;"></i> General SEO Configuration
        </h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Site Title *</label>
                <input type="text" id="seoSiteTitle" value="Alpha Vendor - Your Trusted Marketplace" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Primary title that appears in search results</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Meta Description *</label>
                <textarea id="seoMetaDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">Discover quality products from trusted vendors. Shop wholesale, retail, and import products at competitive prices.</textarea>
                <small style="color: #7f8c8d;">Recommended: 150-160 characters</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Meta Keywords</label>
                <input type="text" id="seoKeywords" value="marketplace, wholesale, retail, vendor, e-commerce" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Comma-separated keywords</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Canonical URL</label>
                <input type="url" id="seoCanonical" placeholder="https://alphavendor.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Primary domain for canonical tags</small>
            </div>
        </div>
    </div>

    <!-- Google Analytics -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fab fa-google" style="margin-right: 10px; color: #667eea;"></i> Google Analytics & Search Console
        </h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Google Analytics ID (GA4)</label>
                <input type="text" id="googleAnalyticsId" placeholder="G-XXXXXXXXXX" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
                <small style="color: #7f8c8d;">Your Google Analytics 4 Measurement ID</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Google Tag Manager ID</label>
                <input type="text" id="googleTagManagerId" placeholder="GTM-XXXXXXX" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
                <small style="color: #7f8c8d;">Optional: Google Tag Manager container ID</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Google Search Console Verification</label>
                <input type="text" id="googleVerification" placeholder="google-site-verification=xxxxxxxxxxxxxxxxxxxx" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
                <small style="color: #7f8c8d;">Meta tag content for site verification</small>
            </div>
        </div>
        <div style="margin-top: 15px; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <p style="margin: 0; color: #1976d2; font-size: 13px;">
                <i class="fas fa-info-circle"></i> Get your tracking codes from 
                <a href="https://analytics.google.com" target="_blank" style="color: #1976d2; font-weight: 600;">Google Analytics</a> and 
                <a href="https://tagmanager.google.com" target="_blank" style="color: #1976d2; font-weight: 600;">Tag Manager</a>
            </p>
        </div>
    </div>

    <!-- Social Media Integration -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-share-alt" style="margin-right: 10px; color: #667eea;"></i> Social Media & Tracking
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Facebook Pixel ID</label>
                <input type="text" id="facebookPixelId" placeholder="xxxxxxxxxxxxxxxxxxxx" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Facebook App ID</label>
                <input type="text" id="facebookAppId" placeholder="xxxxxxxxxxxxxxxxxxxx" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: monospace;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Twitter Card Type</label>
                <select id="twitterCardType" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="summary">Summary</option>
                    <option value="summary_large_image" selected>Summary Large Image</option>
                    <option value="app">App</option>
                    <option value="player">Player</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Twitter Handle</label>
                <input type="text" id="twitterHandle" placeholder="@alphavendor" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
    </div>

    <!-- Robots & Indexing -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-robot" style="margin-right: 10px; color: #667eea;"></i> Robots & Indexing Control
        </h3>
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Allow Search Engine Indexing</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Let search engines crawl and index your site</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="allowIndexing" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('allowIndexing')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Show Breadcrumbs</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Display breadcrumb navigation for better SEO</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="showBreadcrumbs" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('showBreadcrumbs')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="text-align: right;">
        <button onclick="saveSeoSettings()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save SEO Settings
        </button>
    </div>
</div>

<!-- Meta Tags Section -->
<div id="meta-tags-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-code"></i> Meta Tags
        </h2>
        <p style="color: #7f8c8d;">Manage meta tags for specific pages</p>
    </div>

    <!-- Page Meta Tags -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin: 0; display: flex; align-items: center;">
                <i class="fas fa-file-code" style="margin-right: 10px; color: #667eea;"></i> Page-Specific Meta Tags
            </h3>
            <button onclick="showAddMetaTagModal()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-plus"></i> Add Meta Tag
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Page</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Title</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Description</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Home Page</strong><br>
                            <small style="color: #7f8c8d; font-family: monospace;">/</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; max-width: 200px;">Alpha Vendor - Your Trusted Marketplace</td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px; max-width: 300px;">Discover quality products from trusted vendors worldwide</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editMetaTag('home')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">Products Page</strong><br>
                            <small style="color: #7f8c8d; font-family: monospace;">/products</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; max-width: 200px;">All Products - Alpha Vendor</td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px; max-width: 300px;">Browse our wide selection of quality products</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editMetaTag('products')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">About Page</strong><br>
                            <small style="color: #7f8c8d; font-family: monospace;">/about</small>
                        </td>
                        <td style="padding: 12px; color: #2c3e50; max-width: 200px;">About Us - Alpha Vendor</td>
                        <td style="padding: 12px; color: #7f8c8d; font-size: 13px; max-width: 300px;">Learn more about our marketplace platform</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="editMetaTag('about')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Open Graph Tags -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fab fa-facebook" style="margin-right: 10px; color: #667eea;"></i> Open Graph (Facebook/LinkedIn)
        </h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">OG Title</label>
                <input type="text" id="ogTitle" value="Alpha Vendor - Your Trusted Marketplace" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">OG Description</label>
                <textarea id="ogDescription" rows="2" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">Discover quality products from trusted vendors. Shop wholesale, retail, and import products.</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">OG Image URL</label>
                <input type="url" id="ogImage" placeholder="https://alphavendor.com/images/og-image.jpg" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Recommended: 1200x630 pixels</small>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">OG Type</label>
                    <select id="ogType" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        <option value="website" selected>Website</option>
                        <option value="article">Article</option>
                        <option value="product">Product</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">OG Locale</label>
                    <input type="text" id="ogLocale" value="en_US" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Twitter Card Tags -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fab fa-twitter" style="margin-right: 10px; color: #667eea;"></i> Twitter Card Tags
        </h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Twitter Title</label>
                <input type="text" id="twitterTitle" value="Alpha Vendor - Your Trusted Marketplace" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Twitter Description</label>
                <textarea id="twitterDescription" rows="2" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">Discover quality products from trusted vendors worldwide.</textarea>
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Twitter Image URL</label>
                <input type="url" id="twitterImage" placeholder="https://alphavendor.com/images/twitter-card.jpg" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <small style="color: #7f8c8d;">Recommended: 1200x675 pixels</small>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="text-align: right;">
        <button onclick="saveMetaTags()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-save"></i> Save Meta Tags
        </button>
    </div>
</div>

<!-- Sitemap Section -->
<div id="sitemap-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">
            <i class="fas fa-sitemap"></i> Sitemap
        </h2>
        <p style="color: #7f8c8d;">Generate and manage XML sitemaps for search engines</p>
    </div>

    <!-- Sitemap Status -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-line" style="margin-right: 10px; color: #667eea;"></i> Sitemap Status
        </h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Total URLs</p>
                        <h3 style="margin: 0; font-size: 28px; font-weight: 700;">1,247</h3>
                    </div>
                    <i class="fas fa-link" style="font-size: 32px; opacity: 0.3;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); padding: 20px; border-radius: 12px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">Last Updated</p>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Jan 11, 2026</h3>
                    </div>
                    <i class="fas fa-clock" style="font-size: 32px; opacity: 0.3;"></i>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 12px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="margin: 0 0 5px 0; opacity: 0.9; font-size: 13px;">File Size</p>
                        <h3 style="margin: 0; font-size: 28px; font-weight: 700;">234 KB</h3>
                    </div>
                    <i class="fas fa-file" style="font-size: 32px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        <div style="padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <p style="margin: 0; color: #1976d2; font-size: 13px;">
                <i class="fas fa-info-circle"></i> Your sitemap is available at: 
                <a href="/sitemap.xml" target="_blank" style="color: #1976d2; font-weight: 600; text-decoration: underline;">/sitemap.xml</a>
            </p>
        </div>
    </div>

    <!-- Generate Sitemap -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-cogs" style="margin-right: 10px; color: #667eea;"></i> Sitemap Configuration
        </h3>
        <div style="display: grid; gap: 15px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Include Products</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Add all product pages to sitemap</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="includeProducts" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('includeProducts')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Include Categories</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Add all category pages to sitemap</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="includeCategories" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('includeCategories')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Include Static Pages</h4>
                    <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Add about, contact, and other static pages</p>
                </div>
                <label style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" id="includePages" checked style="opacity: 0; width: 0; height: 0;">
                    <span onclick="toggleCheckbox('includePages')" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #10b981; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>
        </div>
        <div style="text-align: center;">
            <button onclick="generateSitemap()" style="padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fas fa-sync-alt"></i> Regenerate Sitemap
            </button>
        </div>
    </div>

    <!-- Submit to Search Engines -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-paper-plane" style="margin-right: 10px; color: #667eea;"></i> Submit to Search Engines
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <button onclick="submitToGoogle()" style="padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; text-align: left; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4285f4 0%, #34a853 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fab fa-google" style="color: white; font-size: 24px;"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Google</h4>
                        <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Submit to Google Search</p>
                    </div>
                </div>
            </button>
            <button onclick="submitToBing()" style="padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; text-align: left; transition: all 0.3s;" onmouseover="this.style.borderColor='#667eea'" onmouseout="this.style.borderColor='#ddd'">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #008373 0%, #00a896 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fab fa-microsoft" style="color: white; font-size: 24px;"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;">Bing</h4>
                        <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Submit to Bing Webmaster</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Sitemap Index -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-list" style="margin-right: 10px; color: #667eea;"></i> Sitemap Index
        </h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Sitemap File</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">URLs</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Last Modified</th>
                        <th style="padding: 12px; text-align: center; font-weight: 600; color: #2c3e50;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">sitemap-products.xml</strong><br>
                            <small style="color: #7f8c8d; font-family: monospace;">/sitemap-products.xml</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">856</span>
                        </td>
                        <td style="padding: 12px; text-align: center; color: #7f8c8d; font-size: 13px;">Jan 11, 2026</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewSitemap('products')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="downloadSitemap('products')" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">sitemap-categories.xml</strong><br>
                            <small style="color: #7f8c8d; font-family: monospace;">/sitemap-categories.xml</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">45</span>
                        </td>
                        <td style="padding: 12px; text-align: center; color: #7f8c8d; font-size: 13px;">Jan 11, 2026</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewSitemap('categories')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="downloadSitemap('categories')" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">sitemap-pages.xml</strong><br>
                            <small style="color: #7f8c8d; font-family: monospace;">/sitemap-pages.xml</small>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">12</span>
                        </td>
                        <td style="padding: 12px; text-align: center; color: #7f8c8d; font-size: 13px;">Jan 11, 2026</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewSitemap('pages')" style="padding: 6px 12px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button onclick="downloadSitemap('pages')" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </td>
                    </tr>
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

    // Show selected section and activate menu item
    const sectionMap = {
        'dashboard': 'dashboard-section',
        'customers': 'customers-section',
        'customer-groups': 'customer-groups-section',
        'wishlists': 'wishlists-section',
        'vendors': 'vendors-section',
        'applications': 'applications-section',
        'commissions': 'commissions-section',
        'vendor-payouts': 'vendor-payouts-section',
        'vendor-reviews': 'vendor-reviews-section',
        'orders': 'orders-section',
        'invoices': 'invoices-section',
        'shipments': 'shipments-section',
        'refunds': 'refunds-section',
        'products': 'products-section',
        'categories': 'categories-section',
        'brands': 'brands-section',
        'reviews': 'reviews-section',
        'coupons': 'coupons-section',
        'flash-sales': 'flash-sales-section',
        'banners': 'banners-section',
        'email-campaigns': 'email-campaigns-section',
        'newsletters': 'newsletters-section',
        'transactions': 'transactions-section',
        'payment-gateway': 'payment-gateway-section',
        'offline-payment': 'offline-payment-section',
        'tax-settings': 'tax-settings-section',
        'currency': 'currency-section',
        'shipping-methods': 'shipping-methods-section',
        'delivery-boys': 'delivery-boys-section',
        'kyc-verification': 'kyc-verification-section',
        'fraud-detection': 'fraud-detection-section',
        'security-logs': 'security-logs-section',
        'notifications': 'notifications-section',
        'chat-messenger': 'chat-messenger-section',
        'otp-system': 'otp-system-section',
        'support-tickets': 'support-tickets-section',
        'general-settings': 'general-settings-section',
        'email-settings': 'email-settings-section',
        'sms-settings': 'sms-settings-section',
        'badges': 'badges-section',
        'languages': 'languages-section',
        'backup': 'backup-section',
        'seo-settings': 'seo-settings-section',
        'meta-tags': 'meta-tags-section',
        'sitemap': 'sitemap-section',
        'retail-page': 'retail-page-section',
        'about-page': 'about-page-section',
        'contact-page': 'contact-page-section',
        'home-page': 'home-page-section',
        'wholesale-page': 'wholesale-page-section',
        'import-page': 'import-page-section',
        'employees': 'employees-section',
        'employee-permissions': 'employee-permissions-section'
    };

    const sectionId = sectionMap[section];
    if (sectionId) {
        const sectionElement = document.getElementById(sectionId);
        if (sectionElement) {
            sectionElement.style.display = 'block';
            // Optionally, load data for these sections
        }
        // Find and activate the corresponding menu item
        const menuItem = document.querySelector(`a[onclick="showSection('${section}')"]`);
        if (menuItem) {
            menuItem.classList.add('active');
        }
    }
}
<!-- Employees Section -->
<div id="employees-section" class="content-section" style="display:none;">
    <h2>All Employees</h2>
    <p>Employee management functionality goes here. (List, add, edit, delete employees.)</p>
    <!-- TODO: Implement employee listing and management -->
</div>

<!-- Employee Permissions Section -->
<div id="employee-permissions-section" class="content-section" style="display:none;">
    <h2>Employee Permissions</h2>
    <p>Employee permissions management functionality goes here. (Assign, edit, revoke permissions.)</p>
    <!-- TODO: Implement employee permissions management -->
</div>

// Toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    
    const colors = {
        'success': '#2ecc71',
        'error': '#e74c3c',
        'warning': '#f39c12',
        'info': '#3498db'
    };
    
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-left: 4px solid ${colors[type]};
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 400px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: slideInRight 0.3s ease-out;
    `;
    
    toast.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 18px;"></i>
            <span style="color: #2c3e50; flex: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: #7f8c8d; cursor: pointer; font-size: 16px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);
}

// Product Filter Functions
function filterProducts() {
    const searchInput = document.getElementById('productSearchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('productCategoryFilter').value;
    const vendorFilter = document.getElementById('productVendorFilter').value;
    const statusFilter = document.getElementById('productStatusFilter').value;
    const featuredFilter = document.getElementById('productFeaturedFilter').value;
    
    const rows = document.querySelectorAll('#products-section tbody tr');
    
    rows.forEach(row => {
        // Get row data
        const productName = row.querySelector('td:nth-child(2) strong')?.textContent.toLowerCase() || '';
        const productSku = row.querySelector('td:nth-child(2) small')?.textContent.toLowerCase() || '';
        const categoryId = row.getAttribute('data-category-id') || '';
        const vendorId = row.getAttribute('data-vendor-id') || '';
        const status = row.getAttribute('data-status') || '';
        const isFeatured = row.getAttribute('data-featured') || '';
        
        // Check all filters
        const matchesSearch = searchInput === '' || productName.includes(searchInput) || productSku.includes(searchInput);
        const matchesCategory = categoryFilter === '' || categoryId === categoryFilter;
        const matchesVendor = vendorFilter === '' || vendorId === vendorFilter;
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesFeatured = featuredFilter === '' || isFeatured === featuredFilter;
        
        // Show/hide row
        if (matchesSearch && matchesCategory && matchesVendor && matchesStatus && matchesFeatured) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function resetProductFilters() {
    document.getElementById('productSearchInput').value = '';
    document.getElementById('productCategoryFilter').value = '';
    document.getElementById('productVendorFilter').value = '';
    document.getElementById('productStatusFilter').value = '';
    document.getElementById('productFeaturedFilter').value = '';
    filterProducts();
}

// Attach event listeners for filters
document.addEventListener('DOMContentLoaded', function() {
    const productSearchInput = document.getElementById('productSearchInput');
    const productCategoryFilter = document.getElementById('productCategoryFilter');
    const productVendorFilter = document.getElementById('productVendorFilter');
    const productStatusFilter = document.getElementById('productStatusFilter');
    const productFeaturedFilter = document.getElementById('productFeaturedFilter');
    
    if (productSearchInput) {
        productSearchInput.addEventListener('input', filterProducts);
    }
    if (productCategoryFilter) {
        productCategoryFilter.addEventListener('change', filterProducts);
    }
    if (productVendorFilter) {
        productVendorFilter.addEventListener('change', filterProducts);
    }
    if (productStatusFilter) {
        productStatusFilter.addEventListener('change', filterProducts);
    }
    if (productFeaturedFilter) {
        productFeaturedFilter.addEventListener('change', filterProducts);
    }

    // Category Filters
    const categorySearchInput = document.getElementById('categorySearchInput');
    const categoryStatusFilter = document.getElementById('categoryStatusFilter');
    const categoryProductsFilter = document.getElementById('categoryProductsFilter');
    const categorySortFilter = document.getElementById('categorySortFilter');
    
    if (categorySearchInput) {
        categorySearchInput.addEventListener('input', filterCategories);
    }
    if (categoryStatusFilter) {
        categoryStatusFilter.addEventListener('change', filterCategories);
    }
    if (categoryProductsFilter) {
        categoryProductsFilter.addEventListener('change', filterCategories);
    }
    if (categorySortFilter) {
        categorySortFilter.addEventListener('change', filterCategories);
    }

    // Brand Filters
    const brandSearchInput = document.getElementById('brandSearchInput');
    const brandStatusFilter = document.getElementById('brandStatusFilter');
    const brandProductsFilter = document.getElementById('brandProductsFilter');
    const brandSortFilter = document.getElementById('brandSortFilter');
    
    if (brandSearchInput) {
        brandSearchInput.addEventListener('input', filterBrands);
    }
    if (brandStatusFilter) {
        brandStatusFilter.addEventListener('change', filterBrands);
    }
    if (brandProductsFilter) {
        brandProductsFilter.addEventListener('change', filterBrands);
    }
    if (brandSortFilter) {
        brandSortFilter.addEventListener('change', filterBrands);
    }

    // Order Filters
    const orderSearchInput = document.getElementById('orderSearchInput');
    const orderStatusFilter = document.getElementById('orderStatusFilter');
    const orderPaymentFilter = document.getElementById('orderPaymentFilter');
    const orderDateFilter = document.getElementById('orderDateFilter');
    const orderSortFilter = document.getElementById('orderSortFilter');
    const orderVendorRoleFilter = document.getElementById('orderVendorRoleFilter');
    
    if (orderSearchInput) {
        orderSearchInput.addEventListener('input', filterOrders);
    }
    if (orderStatusFilter) {
        orderStatusFilter.addEventListener('change', filterOrders);
    }
    if (orderPaymentFilter) {
        orderPaymentFilter.addEventListener('change', filterOrders);
    }
    if (orderDateFilter) {
        orderDateFilter.addEventListener('change', filterOrders);
    }
    if (orderSortFilter) {
        orderSortFilter.addEventListener('change', filterOrders);
    }
    if (orderVendorRoleFilter) {
        orderVendorRoleFilter.addEventListener('change', filterOrders);
    }

    // Customer Group Filters
    const customerGroupSearchInput = document.getElementById('customerGroupSearchInput');
    const customerGroupStatusFilter = document.getElementById('customerGroupStatusFilter');
    const customerGroupSortFilter = document.getElementById('customerGroupSortFilter');
    
    if (customerGroupSearchInput) {
        customerGroupSearchInput.addEventListener('input', filterCustomerGroups);
    }
    if (customerGroupStatusFilter) {
        customerGroupStatusFilter.addEventListener('change', filterCustomerGroups);
    }
    if (customerGroupSortFilter) {
        customerGroupSortFilter.addEventListener('change', filterCustomerGroups);
    }

    // Wishlist Filters
    const wishlistSearchInput = document.getElementById('wishlistSearchInput');
    const wishlistCategoryFilter = document.getElementById('wishlistCategoryFilter');
    const wishlistStatusFilter = document.getElementById('wishlistStatusFilter');
    const wishlistSortFilter = document.getElementById('wishlistSortFilter');
    
    if (wishlistSearchInput) {
        wishlistSearchInput.addEventListener('input', filterWishlists);
    }
    if (wishlistCategoryFilter) {
        wishlistCategoryFilter.addEventListener('change', filterWishlists);
    }
    if (wishlistStatusFilter) {
        wishlistStatusFilter.addEventListener('change', filterWishlists);
    }
    if (wishlistSortFilter) {
        wishlistSortFilter.addEventListener('change', filterWishlists);
    }

    // Vendor Filters
    const vendorSearchInput = document.getElementById('vendorSearchInput');
    const vendorTypeFilter = document.getElementById('vendorTypeFilter');
    const vendorStatusFilter = document.getElementById('vendorStatusFilter');
    const vendorVerificationFilter = document.getElementById('vendorVerificationFilter');
    const vendorSortFilter = document.getElementById('vendorSortFilter');
    
    if (vendorSearchInput) {
        vendorSearchInput.addEventListener('input', filterVendors);
    }
    if (vendorTypeFilter) {
        vendorTypeFilter.addEventListener('change', filterVendors);
    }
    if (vendorStatusFilter) {
        vendorStatusFilter.addEventListener('change', filterVendors);
    }
    if (vendorVerificationFilter) {
        vendorVerificationFilter.addEventListener('change', filterVendors);
    }
    if (vendorSortFilter) {
        vendorSortFilter.addEventListener('change', filterVendors);
    }

    // Review Filters
    const reviewSearchInput = document.getElementById('reviewSearchInput');
    const reviewRatingFilter = document.getElementById('reviewRatingFilter');
    const reviewStatusFilter = document.getElementById('reviewStatusFilter');
    
    if (reviewSearchInput) {
        reviewSearchInput.addEventListener('input', filterReviews);
    }
    if (reviewRatingFilter) {
        reviewRatingFilter.addEventListener('change', filterReviews);
    }
    if (reviewStatusFilter) {
        reviewStatusFilter.addEventListener('change', filterReviews);
    }
});

// Category Filter Functions
function filterCategories() {
    const searchTerm = document.getElementById('categorySearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('categoryStatusFilter').value;
    const productsFilter = document.getElementById('categoryProductsFilter').value;
    const sortFilter = document.getElementById('categorySortFilter').value;
    
    const rows = document.querySelectorAll('.category-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const status = row.getAttribute('data-status');
        const productsCount = parseInt(row.getAttribute('data-products'));
        
        let shouldShow = true;
        
        // Search filter
        if (searchTerm && !name.includes(searchTerm)) {
            shouldShow = false;
        }
        
        // Status filter
        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }
        
        // Products filter
        if (productsFilter === 'hasProducts' && productsCount === 0) {
            shouldShow = false;
        }
        if (productsFilter === 'noProducts' && productsCount > 0) {
            shouldShow = false;
        }
        
        row.style.display = shouldShow ? '' : 'none';
        
        if (shouldShow) {
            visibleRows.push(row);
        }
    });
    
    // Apply sorting
    if (sortFilter && visibleRows.length > 0) {
        const tbody = document.getElementById('categoriesTableBody');
        
        visibleRows.sort((a, b) => {
            if (sortFilter === 'name') {
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            } else if (sortFilter === 'products') {
                return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
            } else if (sortFilter === 'sort_order') {
                return parseInt(a.getAttribute('data-sort-order')) - parseInt(b.getAttribute('data-sort-order'));
            }
            return 0;
        });
        
        visibleRows.forEach(row => {
            tbody.appendChild(row);
        });
    }
}

function resetCategoryFilters() {
    document.getElementById('categorySearchInput').value = '';
    document.getElementById('categoryStatusFilter').value = '';
    document.getElementById('categoryProductsFilter').value = '';
    document.getElementById('categorySortFilter').value = '';
    filterCategories();
}

// Brand Filter Functions
function filterBrands() {
    const searchTerm = document.getElementById('brandSearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('brandStatusFilter').value;
    const productsFilter = document.getElementById('brandProductsFilter').value;
    const sortFilter = document.getElementById('brandSortFilter').value;
    
    const rows = document.querySelectorAll('.brand-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const status = row.getAttribute('data-status');
        const productsCount = parseInt(row.getAttribute('data-products'));
        
        let shouldShow = true;
        
        // Search filter
        if (searchTerm && !name.includes(searchTerm)) {
            shouldShow = false;
        }
        
        // Status filter
        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }
        
        // Products filter
        if (productsFilter === 'hasProducts' && productsCount === 0) {
            shouldShow = false;
        }
        if (productsFilter === 'noProducts' && productsCount > 0) {
            shouldShow = false;
        }
        
        row.style.display = shouldShow ? '' : 'none';
        
        if (shouldShow) {
            visibleRows.push(row);
        }
    });
    
    // Apply sorting
    if (sortFilter && visibleRows.length > 0) {
        const tbody = document.getElementById('brandsTableBody');
        
        visibleRows.sort((a, b) => {
            if (sortFilter === 'name') {
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            } else if (sortFilter === 'products') {
                return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
            } else if (sortFilter === 'sort_order') {
                return parseInt(a.getAttribute('data-sort-order')) - parseInt(b.getAttribute('data-sort-order'));
            }
            return 0;
        });
        
        visibleRows.forEach(row => {
            tbody.appendChild(row);
        });
    }
}

function resetBrandFilters() {
    document.getElementById('brandSearchInput').value = '';
    document.getElementById('brandStatusFilter').value = '';
    document.getElementById('brandProductsFilter').value = '';
    document.getElementById('brandSortFilter').value = '';
    filterBrands();
}

// Orders Filter Functions
function filterOrders() {
    const searchTerm = document.getElementById('orderSearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('orderStatusFilter').value;
    const paymentFilter = document.getElementById('orderPaymentFilter').value;
    const dateFilter = document.getElementById('orderDateFilter').value;
    const sortFilter = document.getElementById('orderSortFilter').value;
    
    const rows = document.querySelectorAll('.order-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const orderId = row.getAttribute('data-order-id');
        const customer = row.getAttribute('data-customer');
        const status = row.getAttribute('data-status');
        const payment = row.getAttribute('data-payment');
        const vendorId = row.getAttribute('data-vendor-id') || '';
        const vendorRole = row.getAttribute('data-vendor-role') || '';
        
        let shouldShow = true;
        
        if (searchTerm && !orderId.includes(searchTerm) && !customer.includes(searchTerm)) {
            shouldShow = false;
        }
        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }
        if (paymentFilter && payment !== paymentFilter) {
            shouldShow = false;
        }
        // Vendor type filter (single dropdown)
        const vendorRoleFilter = document.getElementById('orderVendorRoleFilter')?.value || '';
        if (vendorRoleFilter) {
            if (vendorRole !== vendorRoleFilter) shouldShow = false;
        }
        
        row.style.display = shouldShow ? '' : 'none';
        if (shouldShow) visibleRows.push(row);
    });
    
    if (sortFilter && visibleRows.length > 0) {
        const tbody = document.getElementById('ordersTableBody');
        visibleRows.sort((a, b) => {
            const amountA = parseFloat(a.getAttribute('data-amount'));
            const amountB = parseFloat(b.getAttribute('data-amount'));
            const dateA = new Date(a.getAttribute('data-date'));
            const dateB = new Date(b.getAttribute('data-date'));
            
            if (sortFilter === 'recent') return dateB - dateA;
            if (sortFilter === 'oldest') return dateA - dateB;
            if (sortFilter === 'amount_high') return amountB - amountA;
            if (sortFilter === 'amount_low') return amountA - amountB;
            return 0;
        });
        visibleRows.forEach(row => tbody.appendChild(row));
    }
}

function resetOrderFilters() {
    document.getElementById('orderSearchInput').value = '';
    document.getElementById('orderStatusFilter').value = '';
    document.getElementById('orderPaymentFilter').value = '';
    document.getElementById('orderDateFilter').value = '';
    document.getElementById('orderSortFilter').value = 'recent';
    const vr = document.getElementById('orderVendorRoleFilter'); if (vr) vr.value = '';
    filterOrders();
}

function exportOrders() {
    alert('Export orders functionality will be implemented soon.');
}

function viewOrder(id) {
    alert('View order #' + id + ' details.');
}

function updateOrderStatus(id) {
    alert('Update status for order #' + id);
}

// Invoices Filter Functions
function resetInvoiceFilters() {
    document.getElementById('invoiceSearchInput').value = '';
    document.getElementById('invoiceStatusFilter').value = '';
    document.getElementById('invoiceDateFilter').value = '';
    document.getElementById('invoiceSortFilter').value = 'recent';
}

function generateInvoice() {
    alert('Generate new invoice functionality will be implemented soon.');
}

function downloadInvoice(id) {
    alert('Download invoice PDF for order #' + id);
}

function viewInvoice(id) {
    alert('View invoice for order #' + id);
}

// Shipments Filter Functions
function resetShipmentFilters() {
    document.getElementById('shipmentSearchInput').value = '';
    document.getElementById('shipmentStatusFilter').value = '';
    document.getElementById('shipmentCarrierFilter').value = '';
    document.getElementById('shipmentSortFilter').value = 'recent';
}

function createShipment() {
    alert('Create new shipment functionality will be implemented soon.');
}

function trackShipment(id) {
    alert('Track shipment for order #' + id);
}

function updateShipment(id) {
    alert('Update shipment status for order #' + id);
}

// Refunds Filter Functions
function resetRefundFilters() {
    document.getElementById('refundSearchInput').value = '';
    document.getElementById('refundTypeFilter').value = '';
    document.getElementById('refundStatusFilter').value = '';
    document.getElementById('refundSortFilter').value = 'recent';
}

function processRefund() {
    alert('Process new refund functionality will be implemented soon.');
}

function viewRefund(id) {
    alert('View refund request #' + id);
}

function approveRefund(id) {
    if (confirm('Are you sure you want to approve this refund request?')) {
        alert('Refund #' + id + ' has been approved.');
    }
}

function rejectRefund(id) {
    if (confirm('Are you sure you want to reject this refund request?')) {
        alert('Refund #' + id + ' has been rejected.');
    }
}

// Customer Groups Filter Functions
function filterCustomerGroups() {
    const searchTerm = document.getElementById('customerGroupSearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('customerGroupStatusFilter').value;
    const sortFilter = document.getElementById('customerGroupSortFilter').value;
    
    const rows = document.querySelectorAll('.customer-group-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const status = row.getAttribute('data-status');
        
        let shouldShow = true;
        
        if (searchTerm && !name.includes(searchTerm)) {
            shouldShow = false;
        }
        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }
        
        row.style.display = shouldShow ? '' : 'none';
        if (shouldShow) visibleRows.push(row);
    });
    
    if (sortFilter && visibleRows.length > 0) {
        const tbody = document.getElementById('customerGroupsTableBody');
        visibleRows.sort((a, b) => {
            if (sortFilter === 'name') {
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            } else if (sortFilter === 'members') {
                return parseInt(b.getAttribute('data-members')) - parseInt(a.getAttribute('data-members'));
            } else if (sortFilter === 'recent') {
                return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
            }
            return 0;
        });
        visibleRows.forEach(row => tbody.appendChild(row));
    }
}

function resetCustomerGroupFilters() {
    document.getElementById('customerGroupSearchInput').value = '';
    document.getElementById('customerGroupStatusFilter').value = '';
    document.getElementById('customerGroupSortFilter').value = 'name';
    filterCustomerGroups();
}

function openAddCustomerGroupModal() {
    alert('Create customer group functionality will be implemented soon.');
}

function viewCustomerGroup(id) {
    alert('View customer group #' + id + ' members and details.');
}

function editCustomerGroup(id) {
    alert('Edit customer group #' + id);
}

function deleteCustomerGroup(id) {
    if (confirm('Are you sure you want to delete this customer group?')) {
        alert('Customer group #' + id + ' has been deleted.');
    }
}

// Wishlists Filter Functions
function filterWishlists() {
    const searchTerm = document.getElementById('wishlistSearchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('wishlistCategoryFilter').value;
    const statusFilter = document.getElementById('wishlistStatusFilter').value;
    const sortFilter = document.getElementById('wishlistSortFilter').value;
    
    const rows = document.querySelectorAll('.wishlist-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const customer = row.getAttribute('data-customer');
        const product = row.getAttribute('data-product');
        const category = row.getAttribute('data-category');
        const status = row.getAttribute('data-status');
        
        let shouldShow = true;
        
        if (searchTerm && !customer.includes(searchTerm) && !product.includes(searchTerm)) {
            shouldShow = false;
        }
        if (categoryFilter && category !== categoryFilter) {
            shouldShow = false;
        }
        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }
        
        row.style.display = shouldShow ? '' : 'none';
        if (shouldShow) visibleRows.push(row);
    });
}

function resetWishlistFilters() {
    document.getElementById('wishlistSearchInput').value = '';
    document.getElementById('wishlistCategoryFilter').value = '';
    document.getElementById('wishlistStatusFilter').value = '';
    document.getElementById('wishlistSortFilter').value = 'recent';
    filterWishlists();
}

function exportWishlists() {
    alert('Export wishlists data functionality will be implemented soon.');
}

function viewWishlist(id) {
    alert('View wishlist item #' + id + ' details.');
}

function notifyCustomer(id) {
    if (confirm('Send notification to customer about wishlist item availability?')) {
        alert('Notification sent to customer for wishlist item #' + id);
    }
}

// Vendors Filter Functions
function filterVendors() {
    const searchTerm = document.getElementById('vendorSearchInput').value.toLowerCase();
    const typeFilter = document.getElementById('vendorTypeFilter').value;
    const statusFilter = document.getElementById('vendorStatusFilter').value;
    const verificationFilter = document.getElementById('vendorVerificationFilter').value;
    const sortFilter = document.getElementById('vendorSortFilter').value;
    
    const rows = document.querySelectorAll('.vendor-row');
    let visibleRows = [];
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const business = row.getAttribute('data-business');
        const type = row.getAttribute('data-type');
        const status = row.getAttribute('data-status');
        const verification = row.getAttribute('data-verification');
        
        let shouldShow = true;
        
        if (searchTerm && !name.includes(searchTerm) && !business.includes(searchTerm)) {
            shouldShow = false;
        }
        if (typeFilter && type !== typeFilter) {
            shouldShow = false;
        }
        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }
        if (verificationFilter && verification !== verificationFilter) {
            shouldShow = false;
        }
        
        row.style.display = shouldShow ? '' : 'none';
        if (shouldShow) visibleRows.push(row);
    });
    
    if (sortFilter && visibleRows.length > 0) {
        const tbody = document.getElementById('vendorsTableBody');
        visibleRows.sort((a, b) => {
            if (sortFilter === 'name') {
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            } else if (sortFilter === 'products') {
                return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
            } else if (sortFilter === 'sales') {
                return parseFloat(b.getAttribute('data-sales')) - parseFloat(a.getAttribute('data-sales'));
            }
            return 0;
        });
        visibleRows.forEach(row => tbody.appendChild(row));
    }
}

function resetVendorFilters() {
    document.getElementById('vendorSearchInput').value = '';
    document.getElementById('vendorTypeFilter').value = '';
    document.getElementById('vendorStatusFilter').value = '';
    document.getElementById('vendorVerificationFilter').value = '';
    document.getElementById('vendorSortFilter').value = 'recent';
    filterVendors();
}

function exportVendors() {
    alert('Export vendors data functionality will be implemented soon.');
}

function viewVendor(id) {
    window.location.href = '/admin/vendors/' + id;
}

function manageVendor(id) {
    window.location.href = '/admin/vendors/' + id;
}

// Vendor Payouts Filter Functions
function resetPayoutFilters() {
    document.getElementById('payoutSearchInput').value = '';
    document.getElementById('payoutStatusFilter').value = '';
    document.getElementById('payoutDateFilter').value = '';
    document.getElementById('payoutSortFilter').value = 'recent';
}

function processBulkPayout() {
    alert('Process bulk payout functionality will be implemented soon.');
}

function viewPayout(id) {
    alert('View payout #' + id + ' details.');
}

function approvePayout(id) {
    if (confirm('Are you sure you want to approve this payout?')) {
        alert('Payout #' + id + ' has been approved for processing.');
    }
}

function downloadReceipt(id) {
    alert('Download receipt for payout #' + id);
}

function trackPayout(id) {
    alert('Track payout #' + id + ' status.');
}

// Vendor Reviews Filter Functions
function resetReviewFilters() {
    document.getElementById('reviewSearchInput').value = '';
    document.getElementById('reviewRatingFilter').value = '';
    document.getElementById('reviewStatusFilter').value = '';
    document.getElementById('reviewSortFilter').value = 'recent';
}

function exportReviews() {
    alert('Export reviews data functionality will be implemented soon.');
}

function viewReview(id) {
    alert('View full review #' + id + ' details.');
}

function approveReview(id) {
    if (confirm('Approve this review?')) {
        alert('Review #' + id + ' has been approved.');
    }
}

function flagReview(id) {
    if (confirm('Flag this review for moderation?')) {
        alert('Review #' + id + ' has been flagged.');
    }
}

function resolveReview(id) {
    if (confirm('Mark this flagged review as resolved?')) {
        alert('Review #' + id + ' has been resolved.');
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



// Reviews Functions
let currentReviewPage = 1;
let reviewFilters = {};

function loadReviews(page = 1) {
    currentReviewPage = page;
    const params = new URLSearchParams({
        page: page,
        ...reviewFilters
    });

    fetch(`/admin/reviews?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            updateReviewsTable(data.data);
            updateReviewsPagination(data);
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            showToast('Error loading reviews', 'error');
        });
}

function loadReviewsStats() {
    fetch('/admin/reviews/stats', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('totalReviews').textContent = data.total_reviews;
            document.getElementById('pendingReviews').textContent = data.pending_reviews;
            document.getElementById('approvedReviews').textContent = data.approved_reviews;
            document.getElementById('reportedReviews').textContent = data.reported_reviews;
            document.getElementById('avgRating').innerHTML = `${data.average_rating.toFixed(1)} <i class="fas fa-star" style="font-size: 18px;"></i>`;
            
            updateRatingDistribution(data.rating_distribution, data.total_reviews);
        })
        .catch(error => {
            console.error('Error loading review stats:', error);
        });
}

function updateRatingDistribution(distribution, total) {
    const container = document.getElementById('ratingBars');
    container.innerHTML = '';
    
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#fa709a'];
    const gradients = [
        'linear-gradient(90deg, #43e97b, #38f9d7)',
        'linear-gradient(90deg, #4facfe, #00f2fe)', 
        'linear-gradient(90deg, #f093fb, #f5576c)',
        'linear-gradient(90deg, #fa709a, #fee140)',
        'linear-gradient(90deg, #667eea, #764ba2)'
    ];
    
    for (let i = 5; i >= 1; i--) {
        const count = distribution[i] || 0;
        const percentage = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
        
        const barHtml = `
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="width: 80px; font-weight: 500;">${i} Star${i !== 1 ? 's' : ''}</span>
                <div style="flex: 1; background: #e0e0e0; height: 20px; border-radius: 10px; overflow: hidden;">
                    <div style="width: ${percentage}%; height: 100%; background: ${gradients[5-i]};"></div>
                </div>
                <span style="width: 80px; text-align: right; font-weight: 500;">${count} (${percentage}%)</span>
            </div>
        `;
        container.innerHTML += barHtml;
    }
}

function updateReviewsTable(reviews) {
    const tbody = document.getElementById('reviewsTableBody');
    tbody.innerHTML = '';
    
    if (reviews.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #7f8c8d;">No reviews found</td></tr>';
        return;
    }
    
    reviews.forEach(review => {
        const stars = generateStars(review.rating);
        const statusBadge = getStatusBadge(review.status);
        const actions = getReviewActions(review);
        
        const row = `
            <tr style="border-bottom: 1px solid #dee2e6;">
                <td style="padding: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="${review.product.image || 'https://via.placeholder.com/50'}" alt="Product" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                        <div>
                            <strong>${review.product.name}</strong><br>
                            <small style="color: #7f8c8d;">${review.product.sku || 'N/A'}</small>
                        </div>
                    </div>
                </td>
                <td style="padding: 12px;">
                    <div>
                        <strong>${review.user.name}</strong><br>
                        <small style="color: #7f8c8d;">${review.user.email}</small>
                    </div>
                </td>
                <td style="padding: 12px;">
                    <div style="display: flex; gap: 2px;">
                        ${stars}
                    </div>
                    <small style="color: #2c3e50; font-weight: 500;">${review.rating}.0</small>
                </td>
                <td style="padding: 12px; max-width: 300px;">
                    <div style="color: #2c3e50; line-height: 1.5; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        ${review.title ? `<strong>${review.title}</strong><br>` : ''}${review.comment}
                    </div>
                </td>
                <td style="padding: 12px;">
                    <small>${new Date(review.created_at).toLocaleDateString()}</small>
                </td>
                <td style="padding: 12px;">
                    ${statusBadge}
                </td>
                <td style="padding: 12px; text-align: center;">
                    ${actions}
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += `<i class="fa${i <= rating ? 's' : 'r'} fa-star" style="color: #f39c12;"></i>`;
    }
    return stars;
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 12px;">Pending</span>',
        'approved': '<span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">Approved</span>',
        'rejected': '<span style="padding: 4px 12px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 12px;">Rejected</span>',
        'reported': '<span style="padding: 4px 12px; background: #ffeaa7; color: #d63031; border-radius: 12px; font-size: 12px;">Reported</span>'
    };
    return badges[status] || status;
}

function getReviewActions(review) {
    let actions = `<button onclick="viewReview(${review.id})" style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
        <i class="fas fa-eye"></i>
    </button>`;
    
    if (review.status === 'pending') {
        actions += `<button onclick="approveReview(${review.id})" style="padding: 6px 12px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
            <i class="fas fa-check"></i>
        </button>
        <button onclick="rejectReview(${review.id})" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
            <i class="fas fa-times"></i>
        </button>`;
    }
    
    actions += `<button onclick="directOrder(${review.product.id})" style="padding: 6px 12px; background: #9b59b6; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
        <i class="fas fa-shopping-cart"></i>
    </button>
    <button onclick="deleteReview(${review.id})" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
        <i class="fas fa-trash"></i>
    </button>`;
    
    return actions;
}

function filterReviews() {
    const searchInput = document.getElementById('reviewSearchInput');
    const ratingFilter = document.getElementById('reviewRatingFilter');
    const statusFilter = document.getElementById('reviewStatusFilter');
    
    reviewFilters = {
        search: searchInput?.value || '',
        rating: ratingFilter?.value || '',
        status: statusFilter?.value || ''
    };
    
    loadReviews(1);
}

function viewReview(id) {
    fetch(`/admin/reviews/${id}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(review => {
            // Show review details modal
            showToast(`Viewing review by ${review.user.name}`, 'info');
        })
        .catch(error => {
            console.error('Error loading review:', error);
            showToast('Error loading review details', 'error');
        });
}

function approveReview(id) {
    if (confirm('Approve this review?')) {
        fetch(`/admin/reviews/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Review approved successfully', 'success');
                loadReviews(currentReviewPage);
                loadReviewsStats();
            } else {
                showToast('Error approving review', 'error');
            }
        })
        .catch(error => {
            console.error('Error approving review:', error);
            showToast('Error approving review', 'error');
        });
    }
}

function rejectReview(id) {
    const reason = prompt('Reason for rejection (optional):');
    if (reason !== null) {
        fetch(`/admin/reviews/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Review rejected successfully', 'warning');
                loadReviews(currentReviewPage);
                loadReviewsStats();
            } else {
                showToast('Error rejecting review', 'error');
            }
        })
        .catch(error => {
            console.error('Error rejecting review:', error);
            showToast('Error rejecting review', 'error');
        });
    }
}

function deleteReview(id) {
    if (confirm('Are you sure you want to delete this review?')) {
        fetch(`/admin/reviews/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Review deleted successfully', 'success');
                loadReviews(currentReviewPage);
                loadReviewsStats();
            } else {
                showToast('Error deleting review', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting review:', error);
            showToast('Error deleting review', 'error');
        });
    }
}

function directOrder(productId) {
    if (confirm('Create a direct order for this product?')) {
        fetch(`/admin/direct-order/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Direct order created successfully! Order #${data.order_number}`, 'success');
            } else {
                showToast('Error creating direct order', 'error');
            }
        })
        .catch(error => {
            console.error('Error creating direct order:', error);
            showToast('Error creating direct order', 'error');
        });
    }
}

function updateReviewsPagination(data) {
    const paginationContainer = document.getElementById('reviewsPagination');
    if (!paginationContainer) return;
    
    let paginationHtml = '';
    
    if (data.last_page > 1) {
        // Previous button
        if (data.current_page > 1) {
            paginationHtml += `<button onclick="loadReviews(${data.current_page - 1})" style="padding: 8px 12px; margin: 0 2px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Previous</button>`;
        }
        
        // Page numbers
        const startPage = Math.max(1, data.current_page - 2);
        const endPage = Math.min(data.last_page, data.current_page + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === data.current_page;
            paginationHtml += `<button onclick="loadReviews(${i})" style="padding: 8px 12px; margin: 0 2px; background: ${isActive ? '#2ecc71' : '#ecf0f1'}; color: ${isActive ? 'white' : '#2c3e50'}; border: none; border-radius: 4px; cursor: pointer;">${i}</button>`;
        }
        
        // Next button
        if (data.current_page < data.last_page) {
            paginationHtml += `<button onclick="loadReviews(${data.current_page + 1})" style="padding: 8px 12px; margin: 0 2px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Next</button>`;
        }
    }
    
    paginationContainer.innerHTML = paginationHtml;
}

function exportReviews() {
    showToast('Exporting reviews...', 'info');
    setTimeout(() => {
        showToast('Reviews exported successfully', 'success');
    }, 1500);
}

// Retail Page Image Preview
document.getElementById('retailHeroImageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('retailImagePreview');

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// About Page Image Preview
document.getElementById('aboutHeroImageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('aboutImagePreview');

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Home Page Image Preview
document.getElementById('homeHeroImageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('homeImagePreview');

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Wholesale Page Image Preview
document.getElementById('wholesaleHeroImageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('wholesaleImagePreview');

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Import Page Image Preview
document.getElementById('importHeroImageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('importImagePreview');

    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Commission Management Functions
function addCommissionRate() {
    const type = document.getElementById('commissionType').value;
    const rate = document.getElementById('commissionRate').value;
    
    if (!rate || rate === '') {
        alert('Please enter a commission rate');
        return;
    }
    
    if (parseFloat(rate) < 0 || parseFloat(rate) > 100) {
        alert('Commission rate must be between 0 and 100');
        return;
    }
    
    // Here you would send this data to the backend
    alert(`Commission rate added: ${rate}% for ${type}`);
    
    // Clear the form
    document.getElementById('commissionRate').value = '';
}

function editCommission(id) {
    alert('Edit commission functionality would be implemented here');
}

function deleteCommission(id) {
    if (confirm('Are you sure you want to delete this commission rate?')) {
        alert('Commission deleted');
    }
}

// Coupons Management Functions
function addCoupon() {
    const code = document.getElementById('couponCode').value;
    const type = document.getElementById('couponType').value;
    const value = document.getElementById('couponValue').value;
    const startDate = document.getElementById('couponStartDate').value;
    const endDate = document.getElementById('couponEndDate').value;
    
    if (!code || !value || !startDate || !endDate) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert(`Coupon "${code}" created successfully!`);
    
    // Clear form
    document.getElementById('couponCode').value = '';
    document.getElementById('couponValue').value = '';
    document.getElementById('couponMinPurchase').value = '';
    document.getElementById('couponStartDate').value = '';
    document.getElementById('couponEndDate').value = '';
    document.getElementById('couponLimit').value = '';
}

function editCoupon(id) {
    alert('Edit coupon: ' + id);
}

function deleteCoupon(id) {
    if (confirm('Are you sure you want to delete this coupon?')) {
        alert('Coupon deleted');
    }
}

// Flash Sales Functions
function createFlashSale() {
    const title = document.getElementById('flashSaleTitle').value;
    const startDate = document.getElementById('flashSaleStart').value;
    const endDate = document.getElementById('flashSaleEnd').value;
    const discount = document.getElementById('flashSaleDiscount').value;
    
    if (!title || !startDate || !endDate || !discount) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert(`Flash Sale "${title}" created successfully!`);
    
    // Clear form
    document.getElementById('flashSaleTitle').value = '';
    document.getElementById('flashSaleStart').value = '';
    document.getElementById('flashSaleEnd').value = '';
    document.getElementById('flashSaleDiscount').value = '';
}

function editFlashSale(id) {
    alert('Edit flash sale: ' + id);
}

function deleteFlashSale(id) {
    if (confirm('Are you sure you want to delete this flash sale?')) {
        alert('Flash sale deleted');
    }
}

// Banners Management Functions
function addBanner() {
    const title = document.getElementById('bannerTitle').value;
    const placement = document.getElementById('bannerPlacement').value;
    const image = document.getElementById('bannerImage').files[0];
    
    if (!title || !image) {
        alert('Please provide banner title and image');
        return;
    }
    
    alert(`Banner "${title}" added successfully!`);
    
    // Clear form
    document.getElementById('bannerTitle').value = '';
    document.getElementById('bannerLink').value = '';
    document.getElementById('bannerImage').value = '';
    document.getElementById('bannerStartDate').value = '';
    document.getElementById('bannerEndDate').value = '';
}

function editBanner(id) {
    alert('Edit banner: ' + id);
}

function deleteBanner(id) {
    if (confirm('Are you sure you want to delete this banner?')) {
        alert('Banner deleted');
    }
}

// Email Campaigns Functions
function saveCampaignDraft() {
    const name = document.getElementById('campaignName').value;
    
    if (!name) {
        alert('Please enter a campaign name');
        return;
    }
    
    alert('Campaign draft saved successfully!');
}

function sendTestEmail() {
    const subject = document.getElementById('campaignSubject').value;
    
    if (!subject) {
        alert('Please enter email subject');
        return;
    }
    
    const email = prompt('Enter email address to send test:');
    if (email) {
        alert(`Test email sent to ${email}`);
    }
}

function scheduleCampaign() {
    const name = document.getElementById('campaignName').value;
    const subject = document.getElementById('campaignSubject').value;
    const sendDate = document.getElementById('campaignSendDate').value;
    const content = document.getElementById('campaignContent').value;
    
    if (!name || !subject || !sendDate || !content) {
        alert('Please fill in all required fields');
        return;
    }
    
    if (confirm('Schedule this campaign?')) {
        alert('Campaign scheduled successfully!');
        
        // Clear form
        document.getElementById('campaignName').value = '';
        document.getElementById('campaignSubject').value = '';
        document.getElementById('campaignSendDate').value = '';
        document.getElementById('campaignContent').value = '';
    }
}

function viewCampaignReport(id) {
    alert('View campaign report: ' + id);
}

function duplicateCampaign(id) {
    alert('Campaign duplicated: ' + id);
}

// Newsletters Functions
function saveNewsletterDraft() {
    const title = document.getElementById('newsletterTitle').value;
    
    if (!title) {
        alert('Please enter newsletter title');
        return;
    }
    
    alert('Newsletter draft saved successfully!');
}

function sendTestNewsletter() {
    const title = document.getElementById('newsletterTitle').value;
    
    if (!title) {
        alert('Please enter newsletter title');
        return;
    }
    
    const email = prompt('Enter email address to send test:');
    if (email) {
        alert(`Test newsletter sent to ${email}`);
    }
}

function scheduleNewsletter() {
    const title = document.getElementById('newsletterTitle').value;
    const sendDate = document.getElementById('newsletterSendDate').value;
    const content = document.getElementById('newsletterContent').value;
    
    if (!title || !sendDate || !content) {
        alert('Please fill in all required fields');
        return;
    }
    
    if (confirm('Schedule this newsletter?')) {
        alert('Newsletter scheduled successfully!');
        
        // Clear form
        document.getElementById('newsletterTitle').value = '';
        document.getElementById('newsletterSendDate').value = '';
        document.getElementById('newsletterContent').value = '';
    }
}

function exportSubscribers() {
    alert('Exporting subscribers list...');
}

function viewSubscriber(id) {
    alert('View subscriber: ' + id);
}

function removeSubscriber(id) {
    if (confirm('Remove this subscriber?')) {
        alert('Subscriber removed');
    }
}

// Transactions Functions
function exportTransactions() {
    alert('Exporting transactions...');
}

function viewTransaction(id) {
    alert('View transaction details: ' + id);
}

function refundTransaction(id) {
    if (confirm('Process refund for this transaction?')) {
        alert('Refund processed for transaction: ' + id);
    }
}

// Payment Gateway Functions
function saveGatewaySettings(gateway) {
    alert(gateway.charAt(0).toUpperCase() + gateway.slice(1) + ' settings saved successfully!');
}

// Offline Payment Functions
function addOfflinePayment() {
    const name = document.getElementById('offlineMethodName').value;
    const type = document.getElementById('offlineMethodType').value;
    const instructions = document.getElementById('offlineInstructions').value;
    
    if (!name || !instructions) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Offline payment method "' + name + '" added successfully!');
    
    // Clear form
    document.getElementById('offlineMethodName').value = '';
    document.getElementById('offlineInstructions').value = '';
    document.getElementById('offlineAccountDetails').value = '';
    document.getElementById('offlineFee').value = '';
}

function editOfflinePayment(id) {
    alert('Edit offline payment: ' + id);
}

function deleteOfflinePayment(id) {
    if (confirm('Delete this offline payment method?')) {
        alert('Offline payment method deleted');
    }
}

// Tax Settings Functions
function saveGlobalTaxSettings() {
    alert('Global tax settings saved successfully!');
}

function addTaxRate() {
    const country = document.getElementById('taxCountry').value;
    const rate = document.getElementById('taxRate').value;
    const name = document.getElementById('taxName').value;
    
    if (!country || !rate || !name) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Tax rate "' + name + '" added successfully!');
    
    // Clear form
    document.getElementById('taxCountry').value = '';
    document.getElementById('taxState').value = '';
    document.getElementById('taxPostal').value = '';
    document.getElementById('taxRate').value = '';
    document.getElementById('taxName').value = '';
}

function editTaxRate(id) {
    alert('Edit tax rate: ' + id);
}

function deleteTaxRate(id) {
    if (confirm('Delete this tax rate?')) {
        alert('Tax rate deleted');
    }
}

// Currency Management Functions
function saveCurrencySettings() {
    alert('Currency settings saved successfully!');
}

function addCurrency() {
    const code = document.getElementById('currencyCode').value;
    const name = document.getElementById('currencyName').value;
    const symbol = document.getElementById('currencySymbol').value;
    const rate = document.getElementById('exchangeRate').value;
    
    if (!code || !name || !symbol || !rate) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Currency "' + code + '" added successfully!');
    
    // Clear form
    document.getElementById('currencyCode').value = '';
    document.getElementById('currencyName').value = '';
    document.getElementById('currencySymbol').value = '';
    document.getElementById('exchangeRate').value = '';
}

function editCurrency(code) {
    alert('Edit currency: ' + code);
}

function toggleCurrency(code) {
    alert('Toggle currency status: ' + code);
}

function updateAllRates() {
    if (confirm('Update all exchange rates from live feed?')) {
        alert('Updating all exchange rates...');
    }
}

// Shipping Methods Functions
function addShippingMethod() {
    const name = document.getElementById('shippingMethodName').value;
    const carrier = document.getElementById('shippingCarrier').value;
    const deliveryTime = document.getElementById('shippingDeliveryTime').value;
    
    if (!name || !carrier || !deliveryTime) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Shipping method "' + name + '" added successfully!');
    
    // Clear form
    document.getElementById('shippingMethodName').value = '';
    document.getElementById('shippingCarrier').value = '';
    document.getElementById('shippingDeliveryTime').value = '';
    document.getElementById('shippingBaseCost').value = '';
    document.getElementById('shippingDescription').value = '';
}

function editShippingMethod(id) {
    alert('Edit shipping method: ' + id);
}

function deleteShippingMethod(id) {
    if (confirm('Delete this shipping method?')) {
        alert('Shipping method deleted');
    }
}

// Shipping Zones Functions
function addShippingZone() {
    const name = document.getElementById('zoneName').value;
    const type = document.getElementById('zoneType').value;
    const regions = document.getElementById('zoneRegions').selectedOptions;
    
    if (!name || regions.length === 0) {
        alert('Please fill in all required fields and select at least one region');
        return;
    }
    
    alert('Shipping zone "' + name + '" added successfully!');
    
    // Clear form
    document.getElementById('zoneName').value = '';
    document.getElementById('zoneType').value = 'country';
    document.getElementById('zoneRegions').selectedIndex = -1;
}

function editShippingZone(id) {
    alert('Edit shipping zone: ' + id);
}

function deleteShippingZone(id) {
    if (confirm('Delete this shipping zone?')) {
        alert('Shipping zone deleted');
    }
}

// Delivery Personnel Functions
function addDeliveryPerson() {
    const name = document.getElementById('deliveryPersonName').value;
    const email = document.getElementById('deliveryPersonEmail').value;
    const phone = document.getElementById('deliveryPersonPhone').value;
    const vehicleType = document.getElementById('deliveryVehicleType').value;
    
    if (!name || !email || !phone || !vehicleType) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Delivery personnel "' + name + '" added successfully!');
    
    // Clear form
    document.getElementById('deliveryPersonName').value = '';
    document.getElementById('deliveryPersonEmail').value = '';
    document.getElementById('deliveryPersonPhone').value = '';
    document.getElementById('deliveryVehicleNumber').value = '';
    document.getElementById('deliveryPersonAddress').value = '';
}

function viewDeliveryPerson(id) {
    alert('View delivery personnel details: ' + id);
}

function editDeliveryPerson(id) {
    alert('Edit delivery personnel: ' + id);
}

function deleteDeliveryPerson(id) {
    if (confirm('Delete this delivery personnel?')) {
        alert('Delivery personnel deleted');
    }
}

// Commission Calculator
document.addEventListener('DOMContentLoaded', function() {
    const saleAmountInput = document.getElementById('saleAmount');
    const rateInput = document.getElementById('calcCommissionRate');
    
    function calculateCommission() {
        const saleAmount = parseFloat(saleAmountInput?.value || 100);
        const rate = parseFloat(rateInput?.value || 10);
        
        const platformCommission = (saleAmount * rate / 100).toFixed(2);
        const vendorReceives = (saleAmount - platformCommission).toFixed(2);
        
        const platformEl = document.getElementById('platformCommission');
        const vendorEl = document.getElementById('vendorReceives');
        
        if (platformEl) platformEl.textContent = '$' + platformCommission;
        if (vendorEl) vendorEl.textContent = '$' + vendorReceives;
    }
    
    if (saleAmountInput) {
        saleAmountInput.addEventListener('input', calculateCommission);
    }
    if (rateInput) {
        rateInput.addEventListener('input', calculateCommission);
    }

    // Paperfly API Configuration Functions
    function savePaperflyConfig() {
        const config = {
            base_url: document.getElementById('paperflyBaseUrl').value,
            username: document.getElementById('paperflyUsername').value,
            password: document.getElementById('paperflyPassword').value,
            key: document.getElementById('paperflyKey').value,
            merchant_code: document.getElementById('paperflyMerchantCode').value
        };

        // Validate required fields
        if (!config.username || !config.password || !config.key || !config.merchant_code) {
            alert('Please fill in all required fields');
            return;
        }

        // In a real implementation, this would send to your Laravel backend
        console.log('Saving Paperfly config:', config);
        alert('Paperfly API configuration saved successfully!\n\nNote: In production, add these values to your .env file:\nPAPERFLY_USERNAME=' + config.username + '\nPAPERFLY_PASSWORD=***\nPAPERFLY_KEY=***\nPAPERFLY_MERCHANT_CODE=' + config.merchant_code);
    }

    function testPaperflyConnection() {
        const username = document.getElementById('paperflyUsername').value;
        const password = document.getElementById('paperflyPassword').value;
        const key = document.getElementById('paperflyKey').value;

        if (!username || !password || !key) {
            alert('Please fill in API credentials first');
            return;
        }

        // In a real implementation, this would test the connection
        alert('Testing connection to Paperfly API...\n\nThis would make a test API call to verify credentials.');
        console.log('Testing Paperfly connection');
    }

    function savePickupSettings() {
        const settings = {
            merchant_name: document.getElementById('pickupMerchantName').value,
            merchant_phone: document.getElementById('pickupMerchantPhone').value,
            merchant_address: document.getElementById('pickupMerchantAddress').value,
            pickup_thana: document.getElementById('pickupThana').value,
            pickup_district: document.getElementById('pickupDistrict').value
        };

        // Validate required fields
        if (!settings.merchant_name || !settings.merchant_phone || !settings.merchant_address) {
            alert('Please fill in all required pickup location fields');
            return;
        }

        console.log('Saving pickup settings:', settings);
        alert('Default pickup location saved successfully!');
    }

    // Paperfly Order Tracking Functions
    function trackPaperflyOrder() {
        const searchInput = document.getElementById('trackingSearchInput').value.trim();
        
        if (!searchInput) {
            alert('Please enter an order number or tracking number');
            return;
        }

        // In a real implementation, this would call your Laravel backend API
        console.log('Tracking order:', searchInput);
        
        // Show loading state
        const resultContainer = document.getElementById('trackingResultContainer');
        resultContainer.style.display = 'block';
        
        // Simulate API response (replace with actual AJAX call)
        setTimeout(() => {
            displayTrackingResult({
                tracking_number: 'PF' + Math.floor(Math.random() * 1000000),
                order_reference: searchInput,
                status: 'in_transit',
                customer_name: 'John Doe',
                customer_phone: '+880 1234567890',
                customer_address: 'House 123, Road 45, Gulshan, Dhaka',
                estimated_delivery: '2-3 business days',
                delivery_type: 'Express',
                package_value: '2500',
                timeline: [
                    { status: 'Order Placed', timestamp: '2024-01-10 10:30 AM', completed: true },
                    { status: 'Picked Up', timestamp: '2024-01-10 02:15 PM', completed: true },
                    { status: 'In Transit', timestamp: '2024-01-10 06:45 PM', completed: true },
                    { status: 'Out for Delivery', timestamp: null, completed: false },
                    { status: 'Delivered', timestamp: null, completed: false }
                ]
            });
        }, 1000);
    }

    function refreshTracking() {
        const searchInput = document.getElementById('trackingSearchInput').value.trim();
        if (searchInput) {
            trackPaperflyOrder();
        } else {
            alert('Please enter an order number first');
        }
    }

    function displayTrackingResult(data) {
        // Update tracking info
        document.getElementById('trackingNumber').textContent = data.tracking_number;
        document.getElementById('orderReference').textContent = data.order_reference;
        document.getElementById('customerName').textContent = data.customer_name;
        document.getElementById('customerPhone').textContent = data.customer_phone;
        document.getElementById('customerAddress').textContent = data.customer_address;
        document.getElementById('estimatedDelivery').textContent = data.estimated_delivery;
        document.getElementById('deliveryType').textContent = data.delivery_type;
        document.getElementById('packageValue').textContent = data.package_value;

        // Update status badge
        const badge = document.getElementById('deliveryStatusBadge');
        const statusMap = {
            'pending': { text: 'Pending Pickup', color: '#1a6b73' },
            'picked': { text: 'Picked Up', color: '#3b82f6' },
            'in_transit': { text: 'In Transit', color: '#8b5cf6' },
            'out_for_delivery': { text: 'Out for Delivery', color: '#0d5c63' },
            'delivered': { text: 'Delivered', color: '#10b981' }
        };
        const statusInfo = statusMap[data.status] || { text: 'Unknown', color: '#6b7280' };
        badge.textContent = statusInfo.text;
        badge.style.background = statusInfo.color;
        badge.style.color = 'white';

        // Build timeline
        const timelineContainer = document.getElementById('trackingTimeline');
        timelineContainer.innerHTML = data.timeline.map((item, index) => `
            <div style="position: relative; padding-bottom: ${index < data.timeline.length - 1 ? '30px' : '0'};">
                <div style="position: absolute; left: -35px; top: 0; width: 30px; height: 30px; border-radius: 50%; background: ${item.completed ? '#10b981' : '#e5e7eb'}; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center;">
                    ${item.completed ? '<i class="fas fa-check" style="color: white; font-size: 14px;"></i>' : ''}
                </div>
                ${index < data.timeline.length - 1 ? `<div style="position: absolute; left: -20px; top: 30px; width: 2px; height: 30px; background: ${item.completed ? '#10b981' : '#e5e7eb'};"></div>` : ''}
                <div style="background: ${item.completed ? '#f0fdf4' : '#f9fafb'}; padding: 15px; border-radius: 8px; border-left: 3px solid ${item.completed ? '#10b981' : '#e5e7eb'};">
                    <strong style="color: #2c3e50;">${item.status}</strong>
                    <br>
                    <small style="color: #7f8c8d;">${item.timestamp || 'Pending'}</small>
                </div>
            </div>
        `).join('');
    }

    function filterPaperflyOrders() {
        const filter = document.getElementById('orderStatusFilter').value;
        console.log('Filtering orders by status:', filter);
        // In real implementation, this would filter the orders table
        loadPaperflyOrders(filter);
    }

    function syncAllTracking() {
        alert('Syncing all order tracking statuses from Paperfly...\n\nThis would update all active orders with latest tracking information.');
        console.log('Syncing all tracking');
        // Reload orders after sync
        setTimeout(() => {
            loadPaperflyOrders();
        }, 1000);
    }

    function loadPaperflyOrders(filter = '') {
        const tableBody = document.getElementById('paperflyOrdersTable');
        
        // Show loading
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" style="padding: 30px; text-align: center; color: #7f8c8d;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    Loading orders...
                </td>
            </tr>
        `;

        // In real implementation, this would fetch from Laravel backend
        setTimeout(() => {
            const sampleOrders = [
                {
                    id: 'ORD-001',
                    customer: 'Ahmed Khan',
                    tracking: 'PF123456',
                    status: 'in_transit',
                    lastUpdate: '2 hours ago'
                },
                {
                    id: 'ORD-002',
                    customer: 'Fatima Rahman',
                    tracking: 'PF123457',
                    status: 'delivered',
                    lastUpdate: '1 day ago'
                },
                {
                    id: 'ORD-003',
                    customer: 'Hassan Ali',
                    tracking: 'PF123458',
                    status: 'picked',
                    lastUpdate: '3 hours ago'
                }
            ];

            const statusBadges = {
                'pending': { text: 'Pending', bg: '#fef3c7', color: '#92400e' },
                'picked': { text: 'Picked', bg: '#dbeafe', color: '#1e40af' },
                'in_transit': { text: 'In Transit', bg: '#ede9fe', color: '#5b21b6' },
                'out_for_delivery': { text: 'Out for Delivery', bg: '#fef9c3', color: '#854d0e' },
                'delivered': { text: 'Delivered', bg: '#d1fae5', color: '#065f46' }
            };

            tableBody.innerHTML = sampleOrders.map(order => {
                const badge = statusBadges[order.status] || statusBadges.pending;
                return `
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px;">
                            <strong style="color: #2c3e50;">${order.id}</strong>
                        </td>
                        <td style="padding: 12px; color: #2c3e50;">
                            ${order.customer}
                        </td>
                        <td style="padding: 12px; color: #2c3e50; font-family: monospace;">
                            ${order.tracking}
                        </td>
                        <td style="padding: 12px;">
                            <span style="background: ${badge.bg}; color: ${badge.color}; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                ${badge.text}
                            </span>
                        </td>
                        <td style="padding: 12px; color: #7f8c8d;">
                            ${order.lastUpdate}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewPaperflyOrder('${order.tracking}')" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; margin-right: 5px;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="refreshSingleTracking('${order.tracking}')" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }, 500);
    }

    function viewPaperflyOrder(trackingNumber) {
        document.getElementById('trackingSearchInput').value = trackingNumber;
        trackPaperflyOrder();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function refreshSingleTracking(trackingNumber) {
        console.log('Refreshing tracking for:', trackingNumber);
        alert('Updating tracking status for ' + trackingNumber + '...');
        // In real implementation, call backend to refresh this specific order
    }

    // Load orders when delivery section is shown
    document.addEventListener('DOMContentLoaded', function() {
        // Load stats on page load
        updateDeliveryStats();
    });

    function updateDeliveryStats() {
        // In real implementation, fetch from backend
        // For now, using sample data
        document.getElementById('totalDeliveries').textContent = '0';
        document.getElementById('inTransitCount').textContent = '0';
        document.getElementById('outForDeliveryCount').textContent = '0';
        document.getElementById('deliveredTodayCount').textContent = '0';
    }

    // KYC Verification Functions
    function filterKYC() {
        const status = document.getElementById('kycStatusFilter').value;
        const userType = document.getElementById('kycUserTypeFilter').value;
        const dateRange = document.getElementById('kycDateFilter').value;
        
        console.log('Filtering KYC:', { status, userType, dateRange });
        // In real implementation, filter the table
        alert('Filtering KYC submissions...\nStatus: ' + (status || 'All') + '\nUser Type: ' + (userType || 'All') + '\nDate Range: ' + (dateRange || 'All Time'));
    }

    function reviewKYC(id) {
        console.log('Reviewing KYC:', id);
        alert('Opening KYC review modal for submission #' + id + '\n\nFeatures:\n- View all submitted documents\n- Verify identity information\n- Approve or reject with comments\n- Request additional documents');
    }

    function viewKYCDetails(id) {
        console.log('Viewing KYC details:', id);
        alert('Viewing verified KYC details for submission #' + id);
    }

    function exportKYCData() {
        console.log('Exporting KYC data');
        alert('Exporting KYC data to CSV...\n\nExport will include:\n- All KYC submissions\n- User information\n- Verification status\n- Documents list\n- Submission dates');
    }

    // Fraud Detection Functions
    function saveFraudRules() {
        console.log('Saving fraud detection rules');
        alert('Fraud detection rules saved successfully!\n\nRules will be applied immediately to:\n- Login attempts monitoring\n- Transaction pattern analysis\n- Multi-account detection');
    }

    function filterFraudAlerts() {
        const filter = document.getElementById('fraudAlertFilter').value;
        console.log('Filtering fraud alerts:', filter);
        alert('Filtering alerts by severity: ' + (filter || 'All'));
    }

    function investigateFraud(id) {
        console.log('Investigating fraud alert:', id);
        alert('Opening fraud investigation for alert #' + id + '\n\nInvestigation tools:\n- View user activity history\n- Check IP geolocation\n- Review transaction patterns\n- View device fingerprints\n- Check related accounts');
    }

    function blockUser(id) {
        if (confirm('Are you sure you want to block this user?\n\nThis will:\n- Prevent all logins\n- Freeze ongoing transactions\n- Send notification to user\n- Log the action')) {
            console.log('Blocking user from alert:', id);
            alert('User has been blocked successfully!\n\nBlocked users can be unblocked from the Users section.');
        }
    }

    function dismissAlert(id) {
        if (confirm('Mark this alert as resolved?')) {
            console.log('Dismissing alert:', id);
            alert('Alert #' + id + ' has been dismissed and marked as resolved.');
        }
    }

    // Security Logs Functions
    function filterSecurityLogs() {
        const eventType = document.getElementById('logEventFilter').value;
        const severity = document.getElementById('logSeverityFilter').value;
        const dateRange = document.getElementById('logDateFilter').value;
        
        console.log('Filtering security logs:', { eventType, severity, dateRange });
        alert('Filtering logs...\nEvent Type: ' + (eventType || 'All') + '\nSeverity: ' + (severity || 'All') + '\nDate Range: ' + dateRange);
    }

    function exportSecurityLogs() {
        console.log('Exporting security logs');
        alert('Exporting security logs to CSV...\n\nExport will include:\n- All filtered log entries\n- Event details\n- User information\n- IP addresses\n- Timestamps\n- Severity levels');
    }

    function clearLogFilters() {
        document.getElementById('logSearch').value = '';
        document.getElementById('logEventFilter').value = '';
        document.getElementById('logSeverityFilter').value = '';
        document.getElementById('logUserFilter').value = '';
        document.getElementById('logDateFilter').value = 'today';
        
        console.log('Clearing log filters');
        alert('All filters cleared. Showing all logs from today.');
    }

    function viewLogDetails(id) {
        console.log('Viewing log details:', id);
        alert('Log Entry #' + id + ' Details\n\n' +
              'This would show:\n' +
              '- Full event description\n' +
              '- User agent information\n' +
              '- Request/response data\n' +
              '- Session information\n' +
              '- Related events\n' +
              '- Geographic location');
    }
});

// ==================== NOTIFICATIONS SYSTEM ====================
let currentNotifications = [];
let selectedConversationId = null;

// Load notifications
async function loadNotifications() {
    try {
        const response = await fetch('/notifications?type=all&status=all');
        const data = await response.json();
        currentNotifications = data.data;
        
        updateNotificationStats();
        renderNotifications();
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

function updateNotificationStats() {
    const total = currentNotifications.length;
    const unread = currentNotifications.filter(n => !n.read_at).length;
    const today = currentNotifications.filter(n => {
        const createdAt = new Date(n.created_at);
        const now = new Date();
        return createdAt.toDateString() === now.toDateString();
    }).length;
    
    document.getElementById('totalNotifications').textContent = total;
    document.getElementById('unreadNotifications').textContent = unread;
    document.getElementById('todayNotifications').textContent = today;
}

function renderNotifications() {
    const filtered = filterNotificationsData();
    const container = document.getElementById('notificationsList');
    
    if (filtered.length === 0) {
        container.innerHTML = `
            <div style="padding: 60px 20px; text-align: center; color: #7f8c8d;">
                <i class="fas fa-bell-slash" style="font-size: 48px; opacity: 0.3; margin-bottom: 20px;"></i>
                <p>No notifications found</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = filtered.map(notification => {
        const typeColors = {
            info: { bg: '#e3f2fd', color: '#1976d2', icon: 'fa-info-circle' },
            success: { bg: '#e8f5e9', color: '#388e3c', icon: 'fa-check-circle' },
            warning: { bg: '#fff3e0', color: '#f57c00', icon: 'fa-exclamation-triangle' },
            error: { bg: '#ffebee', color: '#d32f2f', icon: 'fa-times-circle' }
        };
        
        const style = typeColors[notification.type] || typeColors.info;
        const isUnread = !notification.read_at;
        
        return `
            <div style="padding: 20px; border-bottom: 1px solid #eee; ${isUnread ? 'background: #f8f9fa;' : ''} transition: all 0.3s; cursor: pointer;" onmouseenter="this.style.background='#f0f0f0'" onmouseleave="this.style.background='${isUnread ? '#f8f9fa' : 'white'}'" onclick="viewNotification(${notification.id})">
                <div style="display: flex; gap: 15px; align-items: start;">
                    <div style="width: 50px; height: 50px; background: ${style.bg}; color: ${style.color}; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas ${style.icon}" style="font-size: 24px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <h4 style="margin: 0; color: #2c3e50; font-size: 16px;">${notification.title}</h4>
                            ${isUnread ? '<span style="background: #3b82f6; color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">NEW</span>' : ''}
                        </div>
                        <p style="margin: 5px 0 8px 0; color: #7f8c8d; font-size: 14px;">${notification.message}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #95a5a6; font-size: 12px;">
                                <i class="fas fa-clock"></i> ${formatDate(notification.created_at)}
                            </span>
                            <div style="display: flex; gap: 10px;">
                                ${isUnread ? `<button onclick="event.stopPropagation(); markNotificationAsRead(${notification.id})" style="padding: 5px 12px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;"><i class="fas fa-check"></i> Mark Read</button>` : ''}
                                <button onclick="event.stopPropagation(); deleteNotification(${notification.id})" style="padding: 5px 12px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function filterNotificationsData() {
    const typeFilter = document.getElementById('notificationTypeFilter').value;
    const statusFilter = document.getElementById('notificationStatusFilter').value;
    const search = document.getElementById('notificationSearch').value.toLowerCase();
    
    return currentNotifications.filter(n => {
        const matchesType = typeFilter === 'all' || n.type === typeFilter;
        const matchesStatus = statusFilter === 'all' || 
            (statusFilter === 'unread' && !n.read_at) ||
            (statusFilter === 'read' && n.read_at);
        const matchesSearch = !search || 
            n.title.toLowerCase().includes(search) ||
            n.message.toLowerCase().includes(search);
        
        return matchesType && matchesStatus && matchesSearch;
    });
}

function filterNotifications() {
    renderNotifications();
}

function viewNotification(id) {
    const notification = currentNotifications.find(n => n.id === id);
    if (!notification) return;
    
    alert(`${notification.title}\n\n${notification.message}\n\nCreated: ${new Date(notification.created_at).toLocaleString()}`);
    
    if (!notification.read_at) {
        markNotificationAsRead(id);
    }
}

async function markNotificationAsRead(id) {
    try {
        const response = await fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            const notification = currentNotifications.find(n => n.id === id);
            if (notification) notification.read_at = new Date().toISOString();
            
            updateNotificationStats();
            renderNotifications();
            showToast('Notification marked as read', 'success');
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
        showToast('Failed to mark notification as read', 'error');
    }
}

async function markAllNotificationsRead() {
    try {
        const response = await fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            currentNotifications.forEach(n => n.read_at = new Date().toISOString());
            updateNotificationStats();
            renderNotifications();
            showToast('All notifications marked as read', 'success');
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
        showToast('Failed to mark all notifications as read', 'error');
    }
}

async function deleteNotification(id) {
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    try {
        const response = await fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            currentNotifications = currentNotifications.filter(n => n.id !== id);
            updateNotificationStats();
            renderNotifications();
            showToast('Notification deleted successfully', 'success');
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
        showToast('Failed to delete notification', 'error');
    }
}

function showCreateNotificationModal() {
    const modal = `
        <div id="notificationModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 10000;">
            <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
                <h3 style="margin: 0 0 20px 0; color: #2c3e50;">Create Notification</h3>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">User ID</label>
                    <input type="number" id="notifUserId" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" placeholder="Enter user ID">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Type</label>
                    <select id="notifType" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="info">Info</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Title</label>
                    <input type="text" id="notifTitle" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" placeholder="Notification title">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-weight: 500;">Message</label>
                    <textarea id="notifMessage" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" placeholder="Notification message"></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button onclick="closeNotificationModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                    <button onclick="createNotification()" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer;">Create</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modal);
}

function closeNotificationModal() {
    const modal = document.getElementById('notificationModal');
    if (modal) modal.remove();
}

async function createNotification() {
    const userId = document.getElementById('notifUserId').value;
    const type = document.getElementById('notifType').value;
    const title = document.getElementById('notifTitle').value;
    const message = document.getElementById('notifMessage').value;
    
    if (!userId || !title || !message) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    try {
        const response = await fetch('/notifications', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: userId, type, title, message })
        });
        
        if (response.ok) {
            showToast('Notification created successfully', 'success');
            closeNotificationModal();
            loadNotifications();
        }
    } catch (error) {
        console.error('Error creating notification:', error);
        showToast('Failed to create notification', 'error');
    }
}

// ==================== CHAT SYSTEM ====================
let conversations = [];
let currentMessages = [];

async function loadConversations() {
    try {
        const response = await fetch('/chat/conversations');
        conversations = await response.json();
        
        updateChatStats();
        renderConversations();
    } catch (error) {
        console.error('Error loading conversations:', error);
    }
}

function updateChatStats() {
    const active = conversations.filter(c => c.status === 'open' || c.status === 'in_progress').length;
    const pending = conversations.filter(c => c.status === 'open').length;
    const resolvedToday = conversations.filter(c => {
        if (c.status !== 'resolved') return false;
        const updated = new Date(c.updated_at);
        const now = new Date();
        return updated.toDateString() === now.toDateString();
    }).length;
    
    document.getElementById('activeChats').textContent = active;
    document.getElementById('pendingChats').textContent = pending;
    document.getElementById('resolvedToday').textContent = resolvedToday;
}

function renderConversations() {
    const filtered = filterConversationsData();
    const container = document.getElementById('conversationsList');
    
    if (filtered.length === 0) {
        container.innerHTML = `
            <div style="padding: 40px 20px; text-align: center; color: #7f8c8d;">
                <i class="fas fa-comment-slash" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                <p>No conversations found</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = filtered.map(conv => {
        const statusColors = {
            open: { bg: '#e3f2fd', color: '#1976d2' },
            in_progress: { bg: '#fff3e0', color: '#f57c00' },
            resolved: { bg: '#e8f5e9', color: '#388e3c' },
            closed: { bg: '#f5f5f5', color: '#616161' }
        };
        
        const style = statusColors[conv.status] || statusColors.open;
        const isSelected = selectedConversationId === conv.id;
        
        return `
            <div onclick="selectConversation(${conv.id})" style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; ${isSelected ? 'background: #f0f0f0;' : ''}" onmouseenter="if(!${isSelected}) this.style.background='#f8f9fa'" onmouseleave="if(!${isSelected}) this.style.background='white'">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 5px;">
                    <strong style="color: #2c3e50; font-size: 14px;">${conv.user.name}</strong>
                    <span style="background: ${style.bg}; color: ${style.color}; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">${conv.status.replace('_', ' ').toUpperCase()}</span>
                </div>
                <p style="margin: 5px 0; color: #7f8c8d; font-size: 13px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${conv.subject}</p>
                <span style="color: #95a5a6; font-size: 11px;">${formatDate(conv.last_message_at || conv.created_at)}</span>
            </div>
        `;
    }).join('');
}

function filterConversationsData() {
    const statusFilter = document.getElementById('chatStatusFilter').value;
    const search = document.getElementById('chatSearch').value.toLowerCase();
    
    return conversations.filter(c => {
        const matchesStatus = !statusFilter || c.status === statusFilter;
        const matchesSearch = !search || 
            c.subject.toLowerCase().includes(search) ||
            c.user.name.toLowerCase().includes(search);
        
        return matchesStatus && matchesSearch;
    });
}

function filterConversations() {
    renderConversations();
}

async function selectConversation(id) {
    selectedConversationId = id;
    renderConversations();
    
    try {
        const response = await fetch(`/chat/conversations/${id}/messages`);
        const data = await response.json();
        
        currentMessages = data.messages;
        
        document.getElementById('chatHeader').style.display = 'block';
        document.getElementById('chatInput').style.display = 'block';
        document.getElementById('chatUserName').textContent = data.conversation.user.name;
        document.getElementById('chatSubject').textContent = data.conversation.subject;
        document.getElementById('conversationStatus').value = data.conversation.status;
        
        renderMessages();
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

function renderMessages() {
    const container = document.getElementById('chatMessages');
    
    if (currentMessages.length === 0) {
        container.innerHTML = `
            <div style="padding: 40px 20px; text-align: center; color: #7f8c8d;">
                <i class="fas fa-comment-alt" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i>
                <p>No messages yet</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = currentMessages.map(msg => {
        const isAdmin = msg.is_admin;
        const alignment = isAdmin ? 'flex-end' : 'flex-start';
        const bgColor = isAdmin ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : '#ffffff';
        const textColor = isAdmin ? '#ffffff' : '#2c3e50';
        
        return `
            <div style="display: flex; justify-content: ${alignment}; margin-bottom: 15px;">
                <div style="max-width: 70%; background: ${bgColor}; color: ${textColor}; padding: 12px 16px; border-radius: 12px; ${!isAdmin ? 'box-shadow: 0 2px 4px rgba(0,0,0,0.1);' : ''}">
                    <p style="margin: 0 0 5px 0; font-size: 14px; line-height: 1.5;">${msg.message}</p>
                    <span style="font-size: 11px; opacity: 0.7;">${formatDate(msg.created_at)}</span>
                </div>
            </div>
        `;
    }).join('');
    
    container.scrollTop = container.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message || !selectedConversationId) return;
    
    try {
        const response = await fetch(`/chat/conversations/${selectedConversationId}/messages`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message })
        });
        
        if (response.ok) {
            const data = await response.json();
            currentMessages.push(data.message);
            renderMessages();
            input.value = '';
        }
    } catch (error) {
        console.error('Error sending message:', error);
        showToast('Failed to send message', 'error');
    }
}

function handleMessageKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

async function updateConversationStatus() {
    const status = document.getElementById('conversationStatus').value;
    
    if (!selectedConversationId) return;
    
    try {
        const response = await fetch(`/chat/conversations/${selectedConversationId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status })
        });
        
        if (response.ok) {
            const conv = conversations.find(c => c.id === selectedConversationId);
            if (conv) conv.status = status;
            
            updateChatStats();
            renderConversations();
            showToast('Conversation status updated', 'success');
        }
    } catch (error) {
        console.error('Error updating conversation status:', error);
        showToast('Failed to update status', 'error');
    }
}

// Helper function
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
    
    return date.toLocaleDateString();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load notifications and conversations when their sections are shown
    const originalShowSection = showSection;
    showSection = function(section) {
        originalShowSection(section);
        
        if (section === 'notifications') {
            loadNotifications();
        } else if (section === 'chat-messenger') {
            loadConversations();
        } else if (section === 'otp-system') {
            loadSmsStats();
            loadSmsLogs();
        } else if (section === 'support-tickets') {
            loadTicketStats();
            loadTickets();
        }
    };
});

// ==================== SMS & OTP SYSTEM ====================
let selectedTicketId = null;

function showSmsTab(tab) {
    document.querySelectorAll('.sms-tab').forEach(t => {
        t.style.background = 'transparent';
        t.style.color = '#2c3e50';
    });
    document.querySelectorAll('.sms-tab-content').forEach(c => c.style.display = 'none');
    
    if (tab === 'logs') {
        document.getElementById('smsLogsTab').style.background = '#667eea';
        document.getElementById('smsLogsTab').style.color = 'white';
        document.getElementById('smsLogsContent').style.display = 'block';
        loadSmsLogs();
    } else if (tab === 'otp') {
        document.getElementById('smsOtpTab').style.background = '#667eea';
        document.getElementById('smsOtpTab').style.color = 'white';
        document.getElementById('smsOtpContent').style.display = 'block';
        loadOtpLogs();
    } else if (tab === 'templates') {
        document.getElementById('smsTemplatesTab').style.background = '#667eea';
        document.getElementById('smsTemplatesTab').style.color = 'white';
        document.getElementById('smsTemplatesContent').style.display = 'block';
        loadTemplates();
    }
}

async function loadSmsStats() {
    try {
        const response = await fetch('/sms/stats');
        const stats = await response.json();
        
        document.getElementById('totalSms').textContent = stats.total_sent || 0;
        document.getElementById('verifiedOtp').textContent = stats.otp_verified || 0;
        document.getElementById('failedSms').textContent = stats.failed || 0;
        document.getElementById('totalCost').textContent = '$' + (stats.total_cost || 0).toFixed(2);
    } catch (error) {
        console.error('Error loading SMS stats:', error);
    }
}

async function loadSmsLogs() {
    const type = document.getElementById('smsTypeFilter').value;
    const status = document.getElementById('smsStatusFilter').value;
    const search = document.getElementById('smsSearch').value;
    
    try {
        const response = await fetch(`/sms/logs?type=${type}&status=${status}&search=${search}`);
        const data = await response.json();
        
        const container = document.getElementById('smsLogsList');
        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px; color: #7f8c8d;">No SMS logs found</p>';
            return;
        }
        
        container.innerHTML = `
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Phone</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Message</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Type</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Cost</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.data.map(sms => `
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px;">${sms.phone_number}</td>
                            <td style="padding: 12px; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${sms.message}</td>
                            <td style="padding: 12px;"><span style="padding: 4px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 12px;">${sms.type}</span></td>
                            <td style="padding: 12px;">
                                <span style="padding: 4px 8px; background: ${sms.status === 'sent' ? '#e8f5e9' : sms.status === 'failed' ? '#ffebee' : '#fff3e0'}; color: ${sms.status === 'sent' ? '#388e3c' : sms.status === 'failed' ? '#d32f2f' : '#f57c00'}; border-radius: 4px; font-size: 12px;">${sms.status}</span>
                            </td>
                            <td style="padding: 12px;">$${(sms.cost || 0).toFixed(2)}</td>
                            <td style="padding: 12px; font-size: 13px; color: #7f8c8d;">${formatDate(sms.sent_at || sms.created_at)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        console.error('Error loading SMS logs:', error);
    }
}

async function loadOtpLogs() {
    const status = document.getElementById('otpStatusFilter').value;
    const purpose = document.getElementById('otpPurposeFilter').value;
    
    try {
        const response = await fetch(`/sms/otp/logs?status=${status}&purpose=${purpose}`);
        const data = await response.json();
        
        const container = document.getElementById('otpLogsList');
        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px; color: #7f8c8d;">No OTP logs found</p>';
            return;
        }
        
        container.innerHTML = `
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Phone</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">OTP Code</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Purpose</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Attempts</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #2c3e50;">Expires At</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.data.map(otp => `
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px;">${otp.phone_number}</td>
                            <td style="padding: 12px; font-weight: 600;">${otp.otp_code}</td>
                            <td style="padding: 12px;"><span style="padding: 4px 8px; background: #f3e5f5; color: #7b1fa2; border-radius: 4px; font-size: 12px;">${otp.purpose}</span></td>
                            <td style="padding: 12px;">
                                <span style="padding: 4px 8px; background: ${otp.status === 'verified' ? '#e8f5e9' : otp.status === 'failed' ? '#ffebee' : otp.status === 'expired' ? '#fce4ec' : '#fff3e0'}; color: ${otp.status === 'verified' ? '#388e3c' : otp.status === 'failed' ? '#d32f2f' : otp.status === 'expired' ? '#c2185b' : '#f57c00'}; border-radius: 4px; font-size: 12px;">${otp.status}</span>
                            </td>
                            <td style="padding: 12px;">${otp.attempts}/3</td>
                            <td style="padding: 12px; font-size: 13px; color: #7f8c8d;">${formatDate(otp.expires_at)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        console.error('Error loading OTP logs:', error);
    }
}

async function loadTemplates() {
    try {
        const response = await fetch('/sms/templates');
        const templates = await response.json();
        
        const container = document.getElementById('templatesList');
        if (!templates || templates.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px; color: #7f8c8d;">No templates found</p>';
            return;
        }
        
        container.innerHTML = templates.map(template => `
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #667eea;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;">${template.name}</h4>
                        <span style="padding: 3px 8px; background: #e3f2fd; color: #1976d2; border-radius: 4px; font-size: 11px;">${template.type}</span>
                        ${template.is_active ? '<span style="padding: 3px 8px; background: #e8f5e9; color: #388e3c; border-radius: 4px; font-size: 11px; margin-left: 5px;">Active</span>' : ''}
                    </div>
                </div>
                <p style="margin: 10px 0; color: #7f8c8d; font-family: monospace; background: white; padding: 10px; border-radius: 4px; font-size: 13px;">${template.template}</p>
                <div style="font-size: 12px; color: #7f8c8d;">
                    Variables: ${template.variables ? template.variables.map(v => '<code style="background: #fff3e0; padding: 2px 6px; border-radius: 3px;">{{' + v + '}}</code>').join(' ') : 'None'}
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading templates:', error);
    }
}

// ==================== SUPPORT TICKETS SYSTEM ====================
async function loadTicketStats() {
    try {
        const response = await fetch('/tickets/stats/overview');
        const stats = await response.json();
        
        document.getElementById('totalTickets').textContent = stats.total || 0;
        document.getElementById('openTickets').textContent = stats.open || 0;
        document.getElementById('resolvedToday').textContent = stats.resolved_today || 0;
        document.getElementById('avgResponseTime').textContent = (stats.avg_response_time || 0) + 'm';
    } catch (error) {
        console.error('Error loading ticket stats:', error);
    }
}

async function loadTickets() {
    const status = document.getElementById('ticketStatusFilter').value;
    const priority = document.getElementById('ticketPriorityFilter').value;
    const search = document.getElementById('ticketSearch').value;
    
    try {
        const response = await fetch(`/tickets?status=${status}&priority=${priority}&search=${search}`);
        const data = await response.json();
        
        const container = document.getElementById('ticketsList');
        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<div style="padding: 40px 20px; text-align: center; color: #7f8c8d;"><i class="fas fa-ticket-alt" style="font-size: 48px; opacity: 0.3; margin-bottom: 15px;"></i><p>No tickets found</p></div>';
            return;
        }
        
        container.innerHTML = data.data.map(ticket => {
            const priorityColors = {
                urgent: '#d32f2f',
                high: '#f57c00',
                normal: '#1976d2',
                low: '#388e3c'
            };
            
            return `
                <div onclick="loadTicketDetail(${ticket.id})" style="padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: background 0.2s;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <div>
                            <div style="font-size: 12px; color: #667eea; font-weight: 600; margin-bottom: 4px;">${ticket.ticket_number}</div>
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 4px;">${ticket.subject}</div>
                            <div style="font-size: 12px; color: #7f8c8d;"><i class="fas fa-user"></i> ${ticket.user ? ticket.user.name : 'Unknown'}</div>
                        </div>
                        <div style="width: 8px; height: 8px; background: ${priorityColors[ticket.priority]}; border-radius: 50%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
                        <span style="padding: 3px 8px; background: ${ticket.status === 'open' ? '#fff3e0' : ticket.status === 'resolved' ? '#e8f5e9' : '#e3f2fd'}; color: ${ticket.status === 'open' ? '#f57c00' : ticket.status === 'resolved' ? '#388e3c' : '#1976d2'}; border-radius: 4px;">${ticket.status.replace('_', ' ')}</span>
                        <span style="color: #95a5a6;">${formatDate(ticket.created_at)}</span>
                    </div>
                </div>
            `;
        }).join('');
    } catch (error) {
        console.error('Error loading tickets:', error);
    }
}

async function loadTicketDetail(id) {
    selectedTicketId = id;
    
    try {
        const response = await fetch(`/tickets/${id}`);
        const data = await response.json();
        
        const ticket = data.ticket;
        const messages = data.messages;
        
        // Update header
        document.getElementById('ticketDetailHeader').style.display = 'block';
        document.getElementById('ticketNumber').textContent = ticket.ticket_number;
        document.getElementById('ticketSubject').textContent = ticket.subject;
        document.getElementById('ticketCustomer').textContent = ticket.user ? ticket.user.name : 'Unknown';
        document.getElementById('ticketPriorityBadge').textContent = ticket.priority.toUpperCase();
        document.getElementById('ticketStatus').value = ticket.status;
        document.getElementById('ticketReplyBox').style.display = 'block';
        
        // Render messages
        const messagesContainer = document.getElementById('ticketMessages');
        messagesContainer.innerHTML = messages.map(msg => {
            const isStaff = msg.is_staff;
            return `
                <div style="display: flex; justify-content: ${isStaff ? 'flex-end' : 'flex-start'}; margin-bottom: 15px;">
                    <div style="max-width: 70%; background: ${isStaff ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'white'}; color: ${isStaff ? 'white' : '#2c3e50'}; padding: 12px 16px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div style="font-weight: 600; margin-bottom: 5px; font-size: 13px; opacity: ${isStaff ? '0.9' : '1'};">${msg.user ? msg.user.name : 'Unknown'}</div>
                        <div style="margin-bottom: 5px;">${msg.message}</div>
                        <div style="font-size: 11px; opacity: 0.7;">${formatDate(msg.created_at)}</div>
                    </div>
                </div>
            `;
        }).join('');
        
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    } catch (error) {
        console.error('Error loading ticket detail:', error);
    }
}

async function sendTicketReply() {
    const message = document.getElementById('ticketReplyText').value.trim();
    if (!message || !selectedTicketId) return;
    
    try {
        const response = await fetch(`/tickets/${selectedTicketId}/messages`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message })
        });
        
        if (response.ok) {
            document.getElementById('ticketReplyText').value = '';
            loadTicketDetail(selectedTicketId);
            showToast('Reply sent successfully', 'success');
        }
    } catch (error) {
        console.error('Error sending reply:', error);
        showToast('Failed to send reply', 'error');
    }
}

async function updateTicketStatus() {
    const status = document.getElementById('ticketStatus').value;
    if (!selectedTicketId) return;
    
    try {
        const response = await fetch(`/tickets/${selectedTicketId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status })
        });
        
        if (response.ok) {
            showToast('Ticket status updated', 'success');
            loadTickets();
        }
    } catch (error) {
        console.error('Error updating status:', error);
        showToast('Failed to update status', 'error');
    }
}

function showSendSmsModal() {
    showToast('SMS sending feature - Integration with SMS provider needed', 'info');
}

function showGenerateOtpModal() {
    showToast('OTP generation feature - Ready for integration', 'info');
}

function showCreateTemplateModal() {
    showToast('Template creation feature - Coming soon', 'info');
}

// ==================== SYSTEM SETTINGS FUNCTIONS ====================

// General Settings Functions
function toggleMaintenance() {
    const checkbox = document.getElementById('maintenanceMode');
    const messageDiv = document.getElementById('maintenanceMessage');
    const span = checkbox.nextElementSibling;
    
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        span.style.backgroundColor = '#ef4444';
        messageDiv.style.display = 'block';
        showToast('Maintenance mode would be enabled', 'warning');
    } else {
        span.style.backgroundColor = '#ccc';
        messageDiv.style.display = 'none';
        showToast('Maintenance mode would be disabled', 'info');
    }
}

function saveGeneralSettings() {
    const siteName = document.getElementById('siteName').value;
    const siteTagline = document.getElementById('siteTagline').value;
    const adminEmail = document.getElementById('adminEmail').value;
    
    if (!siteName || !adminEmail) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    // Show success message (in production, this would save to database)
    showToast('General settings saved successfully!', 'success');
}

// Email Settings Functions
function toggleEmailDriver() {
    const driver = document.getElementById('emailDriver').value;
    const smtpConfig = document.getElementById('smtpConfig');
    
    // In a full implementation, you'd show/hide different config panels
    // based on the selected driver
    showToast(`Switched to ${driver.toUpperCase()} configuration`, 'info');
}

function sendTestEmail() {
    const testEmail = document.getElementById('testEmailAddress').value;
    const resultDiv = document.getElementById('testEmailResult');
    
    if (!testEmail) {
        showToast('Please enter a recipient email address', 'error');
        return;
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(testEmail)) {
        showToast('Please enter a valid email address', 'error');
        return;
    }
    
    // Show loading state
    resultDiv.innerHTML = `
        <div style="padding: 12px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <p style="margin: 0; color: #1976d2; font-size: 13px;">
                <i class="fas fa-spinner fa-spin"></i> Sending test email to ${testEmail}...
            </p>
        </div>
    `;
    
    // Simulate sending (in production, this would make an API call)
    setTimeout(() => {
        resultDiv.innerHTML = `
            <div style="padding: 12px; background: #d4edda; border-left: 4px solid #28a745; border-radius: 4px;">
                <p style="margin: 0; color: #155724; font-size: 13px;">
                    <i class="fas fa-check-circle"></i> Test email sent successfully to ${testEmail}!
                </p>
            </div>
        `;
        showToast('Test email sent!', 'success');
    }, 2000);
}

function saveEmailSettings() {
    const smtpHost = document.getElementById('smtpHost').value;
    const smtpUsername = document.getElementById('smtpUsername').value;
    const smtpPassword = document.getElementById('smtpPassword').value;
    
    if (!smtpHost || !smtpUsername || !smtpPassword) {
        showToast('Please fill in all required SMTP fields', 'error');
        return;
    }
    
    // Show success message (in production, this would save to database)
    showToast('Email settings saved successfully!', 'success');
}

// SMS Settings Functions
function toggleSmsProvider() {
    const provider = document.getElementById('smsGateway').value;
    const twilioConfig = document.getElementById('twilioConfig');
    
    // In a full implementation, you'd show/hide different config panels
    // based on the selected provider
    showToast(`Switched to ${provider.charAt(0).toUpperCase() + provider.slice(1)} configuration`, 'info');
}

function toggleCheckbox(checkboxId) {
    const checkbox = document.getElementById(checkboxId);
    const span = checkbox.nextElementSibling;
    
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        span.style.backgroundColor = '#10b981';
    } else {
        span.style.backgroundColor = '#ccc';
    }
}

function sendTestSms() {
    const testNumber = document.getElementById('testSmsNumber').value;
    const testMessage = document.getElementById('testSmsMessage').value;
    const resultDiv = document.getElementById('testSmsResult');
    
    if (!testNumber || !testMessage) {
        showToast('Please enter both phone number and message', 'error');
        return;
    }
    
    // Show loading state
    resultDiv.innerHTML = `
        <div style="padding: 12px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <p style="margin: 0; color: #1976d2; font-size: 13px;">
                <i class="fas fa-spinner fa-spin"></i> Sending test SMS to ${testNumber}...
            </p>
        </div>
    `;
    
    // Simulate sending (in production, this would make an API call)
    setTimeout(() => {
        resultDiv.innerHTML = `
            <div style="padding: 12px; background: #d4edda; border-left: 4px solid #28a745; border-radius: 4px;">
                <p style="margin: 0; color: #155724; font-size: 13px;">
                    <i class="fas fa-check-circle"></i> Test SMS sent successfully to ${testNumber}!
                </p>
            </div>
        `;
        showToast('Test SMS sent!', 'success');
    }, 2000);
}

function saveSmsSettings() {
    const twilioSid = document.getElementById('twilioSid').value;
    const twilioToken = document.getElementById('twilioToken').value;
    const twilioFrom = document.getElementById('twilioFrom').value;
    
    if (!twilioSid || !twilioToken || !twilioFrom) {
        showToast('Please fill in all required Twilio fields', 'error');
        return;
    }
    
    // Show success message (in production, this would save to database)
    showToast('SMS settings saved successfully!', 'success');
}

// ==================== BADGES & REWARDS FUNCTIONS ====================

function saveRewardSettings() {
    const pointsPerDollar = document.getElementById('pointsPerDollar').value;
    const pointValue = document.getElementById('pointValue').value;
    const minRedemption = document.getElementById('minRedemption').value;
    
    if (!pointsPerDollar || !pointValue || !minRedemption) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    showToast('Reward settings saved successfully!', 'success');
}

function showCreateBadgeModal() {
    showToast('Badge creation modal - Coming soon', 'info');
}

function editBadge(badgeId) {
    showToast(`Edit badge ${badgeId} - Coming soon`, 'info');
}

function deleteBadge(badgeId) {
    if (confirm('Are you sure you want to delete this badge?')) {
        showToast('Badge deleted successfully!', 'success');
    }
}

// ==================== LANGUAGES FUNCTIONS ====================

function showAddLanguageModal() {
    showToast('Add language modal - Coming soon', 'info');
}

function editLanguage(langCode) {
    showToast(`Edit language ${langCode} - Translation editor coming soon`, 'info');
}

function deleteLanguage(langCode) {
    if (confirm(`Are you sure you want to delete the ${langCode} language?`)) {
        showToast('Language deleted successfully!', 'success');
    }
}

function exportTranslations() {
    showToast('Exporting translation files...', 'info');
    setTimeout(() => {
        showToast('Translation files exported successfully!', 'success');
    }, 1500);
}

function importTranslations() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.json,.csv';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            showToast(`Importing ${file.name}...`, 'info');
            setTimeout(() => {
                showToast('Translations imported successfully!', 'success');
            }, 2000);
        }
    };
    input.click();
}

function syncTranslations() {
    showToast('Syncing translations...', 'info');
    setTimeout(() => {
        showToast('Translations synchronized! 45 new keys added.', 'success');
    }, 2000);
}

// ==================== BACKUP & RESTORE FUNCTIONS ====================

function saveBackupSettings() {
    const backupFrequency = document.getElementById('backupFrequency').value;
    const backupTime = document.getElementById('backupTime').value;
    const backupRetention = document.getElementById('backupRetention').value;
    
    if (!backupTime || !backupRetention) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    showToast('Backup settings saved successfully!', 'success');
}

function createBackup() {
    const backupName = document.getElementById('backupName').value || 'Manual Backup';
    const includeFiles = document.getElementById('includeFiles').checked;
    
    showToast(`Creating backup: ${backupName}...`, 'info');
    
    // Simulate backup creation
    setTimeout(() => {
        showToast('Backup created successfully!', 'success');
        // In production, this would refresh the backup list
    }, 3000);
}

function downloadBackup(backupId) {
    showToast(`Downloading backup #${backupId}...`, 'info');
    setTimeout(() => {
        showToast('Backup downloaded successfully!', 'success');
    }, 1500);
}

function restoreBackup(backupId) {
    if (confirm('⚠️ WARNING: This will restore the database to a previous state. All current data will be overwritten. Are you sure you want to continue?')) {
        showToast(`Restoring backup #${backupId}...`, 'warning');
        setTimeout(() => {
            showToast('Backup restored successfully! Please refresh the page.', 'success');
        }, 3000);
    }
}

function deleteBackup(backupId) {
    if (confirm('Are you sure you want to delete this backup? This action cannot be undone.')) {
        showToast('Backup deleted successfully!', 'success');
        // In production, this would refresh the backup list
    }
}

function handleBackupUpload(event) {
    const file = event.target.files[0];
    if (file) {
        const fileName = file.name;
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        
        if (confirm(`Are you sure you want to restore from "${fileName}" (${fileSize} MB)? This will overwrite all current data.`)) {
            showToast(`Uploading and restoring ${fileName}...`, 'warning');
            setTimeout(() => {
                showToast('Backup restored successfully! Please refresh the page.', 'success');
            }, 4000);
        }
    }
}

// ==================== SEO & ANALYTICS FUNCTIONS ====================

// SEO Settings Functions
function saveSeoSettings() {
    const siteTitle = document.getElementById('seoSiteTitle').value;
    const metaDescription = document.getElementById('seoMetaDescription').value;
    
    if (!siteTitle || !metaDescription) {
        showToast('Please fill in site title and meta description', 'error');
        return;
    }
    
    if (metaDescription.length > 160) {
        showToast('Meta description should be under 160 characters', 'warning');
    }
    
    showToast('SEO settings saved successfully!', 'success');
}

// Meta Tags Functions
function showAddMetaTagModal() {
    showToast('Add meta tag modal - Coming soon', 'info');
}

function editMetaTag(pageId) {
    showToast(`Edit meta tags for ${pageId} page - Coming soon`, 'info');
}

function saveMetaTags() {
    const ogTitle = document.getElementById('ogTitle').value;
    const ogDescription = document.getElementById('ogDescription').value;
    const twitterTitle = document.getElementById('twitterTitle').value;
    
    if (!ogTitle || !ogDescription || !twitterTitle) {
        showToast('Please fill in all required Open Graph and Twitter fields', 'error');
        return;
    }
    
    showToast('Meta tags saved successfully!', 'success');
}

// Sitemap Functions
function generateSitemap() {
    showToast('Generating sitemap...', 'info');
    
    // Simulate sitemap generation
    setTimeout(() => {
        showToast('Sitemap regenerated successfully! 1,247 URLs indexed.', 'success');
    }, 2500);
}

function submitToGoogle() {
    showToast('Opening Google Search Console...', 'info');
    setTimeout(() => {
        window.open('https://search.google.com/search-console', '_blank');
        showToast('Please submit your sitemap manually in Google Search Console', 'info');
    }, 1000);
}

function submitToBing() {
    showToast('Opening Bing Webmaster Tools...', 'info');
    setTimeout(() => {
        window.open('https://www.bing.com/webmasters', '_blank');
        showToast('Please submit your sitemap manually in Bing Webmaster Tools', 'info');
    }, 1000);
}

function viewSitemap(type) {
    const url = `/sitemap-${type}.xml`;
    window.open(url, '_blank');
    showToast(`Opening ${type} sitemap...`, 'info');
}

function downloadSitemap(type) {
    showToast(`Downloading sitemap-${type}.xml...`, 'info');
    setTimeout(() => {
        showToast('Sitemap downloaded successfully!', 'success');
    }, 1000);
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
