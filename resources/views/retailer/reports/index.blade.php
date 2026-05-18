@extends('layouts.dashboard')

@section('title', 'Report Analysis')
@section('page-title', 'Report Analysis')

@section('sidebar-menu')
    @include('dashboards.partials.retailer-sidebar')
@endsection

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }
    
    .stat-card.blue { border-left: 4px solid #3b82f6; }
    .stat-card.green { border-left: 4px solid #10b981; }
    .stat-card.orange { border-left: 4px solid #f59e0b; }
    .stat-card.red { border-left: 4px solid #ef4444; }
    .stat-card.purple { border-left: 4px solid #8b5cf6; }
    .stat-card.teal { border-left: 4px solid #14b8a6; }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .stat-icon.blue { background: #dbeafe; color: #3b82f6; }
    .stat-icon.green { background: #d1fae5; color: #10b981; }
    .stat-icon.orange { background: #fed7aa; color: #f59e0b; }
    .stat-icon.red { background: #fee2e2; color: #ef4444; }
    .stat-icon.purple { background: #ede9fe; color: #8b5cf6; }
    .stat-icon.teal { background: #ccfbf1; color: #14b8a6; }
    
    .stat-title {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }
    
    .stat-subtitle {
        font-size: 13px;
        color: #9ca3af;
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }
    
    .filter-form {
        display: flex;
        gap: 15px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .filter-group input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .btn-filter {
        padding: 10px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .top-products-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .top-products-table th {
        background: #f9fafb;
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .top-products-table td {
        padding: 12px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }
    
    .top-products-table tr:hover {
        background: #f9fafb;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
    }
</style>

<div class="container" style="max-width: 1400px; margin: 0 auto;">
    <!-- Date Filter -->
    <div class="filter-section">
        <form action="{{ route('retailer.reports.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" required>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" required>
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Total Orders -->
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="stat-subtitle">
                        Today: {{ $todayOrders }} | Yesterday: {{ $yesterdayOrders }}
                    </div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <!-- Product Sales -->
        <div class="stat-card green">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Net Product Sold</div>
                    <div class="stat-value">{{ number_format($productSales) }}</div>
                    <div class="stat-subtitle">Gross: {{ number_format($productSalesGross) }} − Returned: {{ number_format($returnedQuantity) }} units</div>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>

        <!-- Net Revenue (after refunds) -->
        <div class="stat-card purple">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Net Sales Revenue</div>
                    <div class="stat-value">{{ currency($netRevenue) }}</div>
                    <div class="stat-subtitle">
                        Gross: {{ currency($grossRevenue) }} &minus; Refunded: {{ currency($totalRefundAmount) }}
                    </div>
                </div>
                <div class="stat-icon purple">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <!-- Product Wishlist -->
        <div class="stat-card purple">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Product Wishlist</div>
                    <div class="stat-value">{{ number_format($productWishlist) }}</div>
                    <div class="stat-subtitle">Total wishlisted items</div>
                </div>
                <div class="stat-icon purple">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
        </div>

        <!-- Product Stock -->
        <div class="stat-card teal">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Product Stock</div>
                    <div class="stat-value">{{ number_format($totalStock) }}</div>
                    <div class="stat-subtitle">Low stock items: {{ $lowStock }}</div>
                </div>
                <div class="stat-icon teal">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>
        </div>

        <!-- Total Returns & Refunds -->
        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Return & Refund</div>
                    <div class="stat-value">{{ number_format($totalReturns) }}</div>
                    <div class="stat-subtitle">
                        Today: {{ $todayReturns }} | Completed: {{ $completedReturns }}
                    </div>
                </div>
                <div class="stat-icon orange">
                    <i class="fas fa-undo"></i>
                </div>
            </div>
        </div>

        <!-- Total Cancelled Orders -->
        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Cancel Order</div>
                    <div class="stat-value">{{ number_format($totalCancelled) }}</div>
                    <div class="stat-subtitle">Today: {{ $todayCancelled }}</div>
                </div>
                <div class="stat-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- Total Exchange -->
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Exchange</div>
                    <div class="stat-value">{{ number_format($totalExchange) }}</div>
                    <div class="stat-subtitle">Today: {{ $todayExchange }}</div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Orders by Date Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-line"></i> Orders Over Time</div>
            </div>
            <canvas id="ordersLineChart" height="300"></canvas>
        </div>

        <!-- Orders by Status Pie Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Orders by Status</div>
            </div>
            <canvas id="ordersPieChart" height="300"></canvas>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="chart-container">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-trophy"></i> Top 10 Net Selling Products (after returns)</div>
        </div>
        <table class="top-products-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Net Sold</th>
                    <th>Returned</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $index => $product)
                <tr>
                    <td><strong>{{ $index + 1 }}</strong></td>
                    <td>{{ $product->name }}</td>
                    <td><span style="background: #dbeafe; color: #3b82f6; padding: 4px 12px; border-radius: 12px; font-weight: 600;">{{ number_format($product->total_sold) }} units</span></td>
                    <td>{{ number_format($product->returned ?? 0) }} units</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: #9ca3af;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        No sales data available for the selected period
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Orders Line Chart
const ordersData = @json($ordersByDate);
const ordersLineCtx = document.getElementById('ordersLineChart').getContext('2d');
new Chart(ordersLineCtx, {
    type: 'line',
    data: {
        labels: ordersData.map(d => d.date),
        datasets: [{
            label: 'Orders',
            data: ordersData.map(d => d.count),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Orders Pie Chart
const statusData = @json($ordersByStatus);
const statusColors = {
    'pending': '#f59e0b',
    'pending_advance_payment': '#f59e0b',
    'advance_paid': '#3b82f6',
    'order_confirmed': '#3b82f6',
    'processing': '#8b5cf6',
    'shipped': '#14b8a6',
    'delivered': '#10b981',
    'cancelled': '#ef4444'
};

const ordersPieCtx = document.getElementById('ordersPieChart').getContext('2d');
new Chart(ordersPieCtx, {
    type: 'pie',
    data: {
        labels: statusData.map(d => d.status.replace(/_/g, ' ').toUpperCase()),
        datasets: [{
            data: statusData.map(d => d.count),
            backgroundColor: statusData.map(d => statusColors[d.status] || '#6b7280'),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});
</script>
@endsection
