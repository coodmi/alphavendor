@extends('layouts.dashboard')

@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}">
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
        <a href="{{ route('admin.products') }}" class="menu-item">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="menu-item">
            <i class="fas fa-tags"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('admin.brands') }}" class="menu-item">
            <i class="fas fa-copyright"></i>
            <span>Brands</span>
        </a>
        <a href="#" class="menu-item" style="opacity:0.5;pointer-events:none;">
            <i class="fas fa-star"></i>
            <span>Reviews & Ratings</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Sales & Orders</div>
        <a href="javascript:void(0)" onclick="showSection('orders')" class="menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('admin.manual-payments.index') }}" class="menu-item">
            <i class="fas fa-mobile-alt"></i>
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
            <i class="fas fa-truck"></i>
            <span>Shipments</span>
        </a>
        <a href="javascript:void(0)" onclick="showSection('refunds')" class="menu-item">
            <i class="fas fa-undo"></i>
            <span>Refunds & Returns</span>
        </a>
    </div>
@endsection

@section('content')
<div class="content-area" style="background: #f6f8fb; min-height: 100vh;">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-800">Website Analytics & Reports</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-blue-500 to-blue-300 text-white">
            <div class="mr-4"><i class="fas fa-users fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Users</div>
                <div class="text-3xl font-extrabold">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-green-500 to-green-300 text-white">
            <div class="mr-4"><i class="fas fa-shopping-cart fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Orders</div>
                <div class="text-3xl font-extrabold">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-pink-500 to-pink-300 text-white">
            <div class="mr-4"><i class="fas fa-coins fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Total Sales</div>
                <div class="text-3xl font-extrabold">৳{{ number_format($totalSales, 2) }}</div>
            </div>
        </div>
        <div class="rounded-xl shadow-lg p-6 flex items-center bg-gradient-to-tr from-yellow-500 to-yellow-300 text-white">
            <div class="mr-4"><i class="fas fa-user-tie fa-2x"></i></div>
            <div>
                <div class="text-lg font-semibold">Active Vendors</div>
                <div class="text-3xl font-extrabold">{{ $activeVendors }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
        <h3 class="font-bold text-xl mb-6 text-gray-700">Orders & Sales Overview</h3>
        <canvas id="ordersChart" height="80"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 mb-10">
        <h3 class="font-bold text-xl mb-6 text-gray-700">Recent Orders</h3>
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-left py-2 px-4">Order ID</th>
                    <th class="text-left py-2 px-4">User</th>
                    <th class="text-left py-2 px-4">Amount</th>
                    <th class="text-left py-2 px-4">Status</th>
                    <th class="text-left py-2 px-4">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 px-4 font-semibold text-blue-600">#{{ $order->id }}</td>
                    <td class="py-2 px-4">{{ $order->user->name }}</td>
                    <td class="py-2 px-4">৳{{ number_format($order->total, 2) }}</td>
                    <td class="py-2 px-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($order->status == 'delivered' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="py-2 px-4">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
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
@endsection
