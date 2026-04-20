@extends('layouts.dashboard')

@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.analytics') }}" class="menu-item active">
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
        <a href="{{ route('admin.special-offers.index') }}" class="menu-item">
            <i class="fas fa-tag"></i>
            <span>Special Offers</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Orders & Sales</div>
        <a href="{{ route('admin.orders') }}" class="menu-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">User Management</div>
        <a href="{{ route('admin.users') }}" class="menu-item">
            <i class="fas fa-users-cog"></i>
            <span>All Users</span>
        </a>
        <a href="{{ route('admin.vendors') }}" class="menu-item">
            <i class="fas fa-store"></i>
            <span>Vendors</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Marketing</div>
        <a href="{{ route('admin.coupons') }}" class="menu-item">
            <i class="fas fa-ticket-alt"></i>
            <span>Coupons</span>
        </a>
    </div>
@endsection

@section('content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold text-white mb-2">Analytics & Reports</h1>
    <p class="text-gray-100">Comprehensive business insights and performance metrics</p>
</div>

        <!-- Today vs Yesterday Comparison -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-4">Today vs Yesterday</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Retailer Orders -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Retailer Orders</h3>
                        <i class="fas fa-store text-green-500 text-2xl"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-200">Today</p>
                            <p class="text-3xl font-bold text-green-600">{{ $todayStats['retailer_orders_today'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ currency_symbol() }}{{ number_format($todayStats['retailer_sales_today'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-200">Yesterday</p>
                            <p class="text-3xl font-bold text-gray-100">{{ $yesterdayStats['retailer_orders_yesterday'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ currency_symbol() }}{{ number_format($yesterdayStats['retailer_sales_yesterday'], 2) }}</p>
                        </div>
                    </div>
                    @php
                        $change = $yesterdayStats['retailer_orders_yesterday'] > 0 
                            ? (($todayStats['retailer_orders_today'] - $yesterdayStats['retailer_orders_yesterday']) / $yesterdayStats['retailer_orders_yesterday']) * 100 
                            : 0;
                    @endphp
                    <div class="mt-4 flex items-center">
                        @if($change > 0)
                            <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                            <span class="text-sm text-green-600 font-semibold">+{{ number_format($change, 1) }}%</span>
                        @elseif($change < 0)
                            <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                            <span class="text-sm text-red-600 font-semibold">{{ number_format($change, 1) }}%</span>
                        @else
                            <span class="text-sm text-gray-200">No change</span>
                        @endif
                    </div>
                </div>

                <!-- Wholesaler Orders -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Wholesaler Orders</h3>
                        <i class="fas fa-warehouse text-blue-500 text-2xl"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-200">Today</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $todayStats['wholesaler_orders_today'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ currency_symbol() }}{{ number_format($todayStats['wholesaler_sales_today'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-200">Yesterday</p>
                            <p class="text-3xl font-bold text-gray-100">{{ $yesterdayStats['wholesaler_orders_yesterday'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ currency_symbol() }}{{ number_format($yesterdayStats['wholesaler_sales_yesterday'], 2) }}</p>
                        </div>
                    </div>
                    @php
                        $change = $yesterdayStats['wholesaler_orders_yesterday'] > 0 
                            ? (($todayStats['wholesaler_orders_today'] - $yesterdayStats['wholesaler_orders_yesterday']) / $yesterdayStats['wholesaler_orders_yesterday']) * 100 
                            : 0;
                    @endphp
                    <div class="mt-4 flex items-center">
                        @if($change > 0)
                            <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                            <span class="text-sm text-green-600 font-semibold">+{{ number_format($change, 1) }}%</span>
                        @elseif($change < 0)
                            <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                            <span class="text-sm text-red-600 font-semibold">{{ number_format($change, 1) }}%</span>
                        @else
                            <span class="text-sm text-gray-200">No change</span>
                        @endif
                    </div>
                </div>

                <!-- Importer Orders -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Importer Orders</h3>
                        <i class="fas fa-globe text-purple-500 text-2xl"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-200">Today</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $todayStats['importer_orders_today'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ currency_symbol() }}{{ number_format($todayStats['importer_sales_today'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-200">Yesterday</p>
                            <p class="text-3xl font-bold text-gray-100">{{ $yesterdayStats['importer_orders_yesterday'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ currency_symbol() }}{{ number_format($yesterdayStats['importer_sales_yesterday'], 2) }}</p>
                        </div>
                    </div>
                    @php
                        $change = $yesterdayStats['importer_orders_yesterday'] > 0 
                            ? (($todayStats['importer_orders_today'] - $yesterdayStats['importer_orders_yesterday']) / $yesterdayStats['importer_orders_yesterday']) * 100 
                            : 0;
                    @endphp
                    <div class="mt-4 flex items-center">
                        @if($change > 0)
                            <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                            <span class="text-sm text-green-600 font-semibold">+{{ number_format($change, 1) }}%</span>
                        @elseif($change < 0)
                            <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                            <span class="text-sm text-red-600 font-semibold">{{ number_format($change, 1) }}%</span>
                        @else
                            <span class="text-sm text-gray-200">No change</span>
                        @endif
                    </div>
                </div>

                <!-- Returns & Refunds -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-teal-600">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Returns & Refunds</h3>
                        <i class="fas fa-undo text-teal-600 text-2xl"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-200">Today</p>
                            <p class="text-3xl font-bold text-teal-700">{{ $todayStats['returns_today'] + $todayStats['refunds_today'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ $todayStats['returns_today'] }} returns, {{ $todayStats['refunds_today'] }} refunds</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-200">Yesterday</p>
                            <p class="text-3xl font-bold text-gray-100">{{ $yesterdayStats['returns_yesterday'] + $yesterdayStats['refunds_yesterday'] }}</p>
                            <p class="text-xs text-gray-200 mt-1">{{ $yesterdayStats['returns_yesterday'] }} returns, {{ $yesterdayStats['refunds_yesterday'] }} refunds</p>
                        </div>
                    </div>
                </div>

                <!-- Cancelled Orders -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Cancelled Orders</h3>
                        <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-200">Today</p>
                            <p class="text-3xl font-bold text-red-600">{{ $todayStats['cancelled_today'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-200">Yesterday</p>
                            <p class="text-3xl font-bold text-gray-100">{{ $yesterdayStats['cancelled_yesterday'] }}</p>
                        </div>
                    </div>
                    @php
                        $change = $yesterdayStats['cancelled_yesterday'] > 0 
                            ? (($todayStats['cancelled_today'] - $yesterdayStats['cancelled_yesterday']) / $yesterdayStats['cancelled_yesterday']) * 100 
                            : 0;
                    @endphp
                    <div class="mt-4 flex items-center">
                        @if($change > 0)
                            <i class="fas fa-arrow-up text-red-500 mr-2"></i>
                            <span class="text-sm text-red-600 font-semibold">+{{ number_format($change, 1) }}%</span>
                        @elseif($change < 0)
                            <i class="fas fa-arrow-down text-green-500 mr-2"></i>
                            <span class="text-sm text-green-600 font-semibold">{{ number_format($change, 1) }}%</span>
                        @else
                            <span class="text-sm text-gray-200">No change</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Statistics -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-4">Overall Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Sales -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold">Total Sales</h3>
                        <i class="fas fa-dollar-sign text-3xl opacity-50"></i>
                    </div>
                    <p class="text-4xl font-bold">{{ currency_symbol() }}{{ number_format($overallStats['total_sales'], 2) }}</p>
                    <p class="text-sm opacity-75 mt-2">{{ $overallStats['total_delivered'] }} completed orders</p>
                </div>

                <!-- Total Orders -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold">Total Orders</h3>
                        <i class="fas fa-shopping-cart text-3xl opacity-50"></i>
                    </div>
                    <p class="text-4xl font-bold">{{ number_format($overallStats['total_orders']) }}</p>
                    <p class="text-sm opacity-75 mt-2">{{ $overallStats['total_pending'] }} pending</p>
                </div>

                <!-- Active Vendors -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold">Active Vendors</h3>
                        <i class="fas fa-users text-3xl opacity-50"></i>
                    </div>
                    <p class="text-4xl font-bold">{{ $overallStats['total_retailers'] + $overallStats['total_wholesalers'] + $overallStats['total_importers'] }}</p>
                    <p class="text-sm opacity-75 mt-2">{{ $overallStats['total_retailers'] }}R / {{ $overallStats['total_wholesalers'] }}W / {{ $overallStats['total_importers'] }}I</p>
                </div>

                <!-- Total Products -->
                <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold">Total Products</h3>
                        <i class="fas fa-box text-3xl opacity-50"></i>
                    </div>
                    <p class="text-4xl font-bold">{{ number_format($overallStats['total_products']) }}</p>
                    <p class="text-sm opacity-75 mt-2">{{ $overallStats['active_products'] }} active, {{ $overallStats['out_of_stock'] }} out of stock</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Sales Chart -->
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
                <h3 class="text-base font-semibold text-white mb-3">Sales Trend (Last 7 Days)</h3>
                <div style="height: 200px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Orders Chart -->
            <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
                <h3 class="text-base font-semibold text-white mb-3">Orders Trend (Last 7 Days)</h3>
                <div style="height: 200px;">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Vendors -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-white mb-4">Top Performing Vendors</h3>
                <div class="space-y-4">
                    @forelse($topVendors as $index => $vendor)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">{{ $vendor->name }}</p>
                                    <p class="text-sm text-gray-200">{{ ucfirst($vendor->role) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">{{ currency_symbol() }}{{ number_format($vendor->total_sales ?? 0, 2) }}</p>
                                <p class="text-sm text-gray-200">{{ $vendor->total_orders }} orders</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-200 text-center py-8">No vendor data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-white mb-4">Top Selling Products</h3>
                <div class="space-y-4">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-600 to-red-500 flex items-center justify-center text-white font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">{{ Str::limit($product->name, 30) }}</p>
                                    <p class="text-sm text-gray-200">{{ $product->category->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">{{ currency_symbol() }}{{ number_format($product->total_revenue ?? 0, 2) }}</p>
                                <p class="text-sm text-gray-200">{{ $product->total_sold }} sold</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-200 text-center py-8">No product data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Recent Orders</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Order ID</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Customer</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Vendor</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Amount</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-white">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-white">#{{ $order->id }}</td>
                                <td class="px-4 py-3 text-sm text-white">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-white">{{ $order->vendor->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-white">{{ currency_symbol() }}{{ number_format($order->total, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($order->status == 'delivered' || $order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'pending') bg-teal-100 text-teal-800
                                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-white
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-200">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-200">No recent orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Modern Chart Configuration
    Chart.defaults.font.family = "'Inter', 'system-ui', '-apple-system', 'sans-serif'";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#6B7280';

    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($last7Days),
            datasets: [{
                label: 'Sales',
                data: @json($salesData),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.08)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: 'rgb(34, 197, 94)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 12,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Sales: {{ currency_symbol() }}' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        padding: 8,
                        callback: function(value) {
                            return '{{ currency_symbol() }}' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        padding: 8
                    }
                }
            }
        }
    });

    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: @json($last7Days),
            datasets: [{
                label: 'Orders',
                data: @json($ordersData),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 0,
                borderRadius: 6,
                barThickness: 24
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 12,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Orders: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        padding: 8,
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        padding: 8
                    }
                }
            }
        }
    });
</script>
@endsection
