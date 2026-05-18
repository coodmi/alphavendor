@extends('layouts.dashboard')

@section('title', 'Report Analysis')
@section('page-title', 'Report Analysis')

@section('sidebar-menu')
    @include('dashboards.partials.wholesaler-sidebar')
@endsection

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stats-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 8px 0 14px;
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
    
    .charts-row {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 24px;
        margin-bottom: 30px;
        align-items: stretch;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 20px 24px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-shrink: 0;
    }
    
    .chart-title {
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
    }

    .chart-title i {
        color: #6366f1;
        margin-right: 8px;
    }

    .chart-canvas-wrap {
        position: relative;
        width: 100%;
        height: 280px;
        flex: 1;
        min-height: 240px;
        max-height: 320px;
    }

    .chart-canvas-wrap--pie {
        height: 260px;
        max-height: 300px;
    }

    .chart-empty {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 280px;
        color: #9ca3af;
        text-align: center;
        padding: 24px;
    }

    .chart-empty.is-visible {
        display: flex;
    }

    .chart-empty i {
        font-size: 40px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    .chart-empty p {
        margin: 0;
        font-size: 14px;
        max-width: 220px;
        line-height: 1.5;
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
    
    @media (max-width: 1024px) {
        .charts-row {
            grid-template-columns: 1fr;
        }
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

        .chart-canvas-wrap,
        .chart-canvas-wrap--pie,
        .chart-empty {
            height: 240px;
            min-height: 220px;
            max-height: 260px;
        }
    }
</style>

<div class="container" style="max-width: 1400px; margin: 0 auto;">
    <!-- Date Filter -->
    <div class="filter-section">
        <form action="{{ route('wholesaler.reports.index') }}" method="GET" class="filter-form">
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
            <a href="{{ route('wholesaler.reports.index', ['start_date' => now()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="btn-filter" style="background:#e5e7eb;color:#374151;text-decoration:none;display:inline-flex;align-items:center;">Today</a>
            <a href="{{ route('wholesaler.reports.index', ['start_date' => now()->subDay()->toDateString(), 'end_date' => now()->subDay()->toDateString()]) }}" class="btn-filter" style="background:#e5e7eb;color:#374151;text-decoration:none;display:inline-flex;align-items:center;">Yesterday</a>
            <a href="{{ route('wholesaler.reports.index', ['start_date' => now()->subDays(29)->toDateString(), 'end_date' => now()->toDateString()]) }}" class="btn-filter" style="background:#e5e7eb;color:#374151;text-decoration:none;display:inline-flex;align-items:center;">Last 30 Days</a>
        </form>
    </div>

    <p class="stats-section-title">Orders ({{ $startDate }} to {{ $endDate }})</p>
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Today Total Orders</div>
                    <div class="stat-value">{{ number_format($todayOrders) }}</div>
                    <div class="stat-subtitle">{{ now()->format('d M Y') }}</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-calendar-day"></i></div>
            </div>
        </div>
        <div class="stat-card indigo" style="border-left-color:#6366f1;">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Yesterday Total Orders</div>
                    <div class="stat-value">{{ number_format($yesterdayOrders) }}</div>
                    <div class="stat-subtitle">{{ now()->subDay()->format('d M Y') }}</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-history"></i></div>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Orders (Filtered)</div>
                    <div class="stat-value">{{ number_format($totalOrders) }}</div>
                    <div class="stat-subtitle">In selected date range</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-shopping-cart"></i></div>
            </div>
        </div>
    </div>

    <p class="stats-section-title">Products</p>
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Product Sell</div>
                    <div class="stat-value">{{ number_format($productSales) }}</div>
                    <div class="stat-subtitle">Net units (after returns) in range</div>
                </div>
                <div class="stat-icon green"><i class="fas fa-box"></i></div>
            </div>
        </div>
        <div class="stat-card purple">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Product Wishlist</div>
                    <div class="stat-value">{{ number_format($productWishlist) }}</div>
                    <div class="stat-subtitle">Total wishlisted items</div>
                </div>
                <div class="stat-icon purple"><i class="fas fa-heart"></i></div>
            </div>
        </div>
        <div class="stat-card teal">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Product Stock</div>
                    <div class="stat-value">{{ number_format($totalStock) }}</div>
                    <div class="stat-subtitle">Low stock: {{ $lowStock }}</div>
                </div>
                <div class="stat-icon teal"><i class="fas fa-warehouse"></i></div>
            </div>
        </div>
    </div>

    <p class="stats-section-title">Returns, Cancels & Exchange</p>
    <div class="stats-grid">
        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Return & Refund</div>
                    <div class="stat-value">{{ number_format($totalReturns) }}</div>
                    <div class="stat-subtitle">In selected date range</div>
                </div>
                <div class="stat-icon orange"><i class="fas fa-undo"></i></div>
            </div>
        </div>
        <div class="stat-card orange">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Today Return & Refund</div>
                    <div class="stat-value">{{ number_format($todayReturns) }}</div>
                    <div class="stat-subtitle">Requests created today</div>
                </div>
                <div class="stat-icon orange"><i class="fas fa-undo-alt"></i></div>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Today Total Cancel Order</div>
                    <div class="stat-value">{{ number_format($todayCancelled) }}</div>
                    <div class="stat-subtitle">Cancelled today</div>
                </div>
                <div class="stat-icon red"><i class="fas fa-ban"></i></div>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Cancel Order</div>
                    <div class="stat-value">{{ number_format($totalCancelled) }}</div>
                    <div class="stat-subtitle">In selected date range</div>
                </div>
                <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Exchange</div>
                    <div class="stat-value">{{ number_format($totalExchange) }}</div>
                    <div class="stat-subtitle">In selected date range</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-exchange-alt"></i></div>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Today Total Exchange</div>
                    <div class="stat-value">{{ number_format($todayExchange) }}</div>
                    <div class="stat-subtitle">Exchange requests today</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-retweet"></i></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row">
        <!-- Orders by Date Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-line"></i> Orders by Date</div>
            </div>
            <div id="ordersLineEmpty" class="chart-empty">
                <i class="fas fa-chart-area"></i>
                <p>No orders in this date range. Try a wider filter.</p>
            </div>
            <div class="chart-canvas-wrap" id="ordersLineWrap">
                <canvas id="ordersLineChart"></canvas>
            </div>
        </div>

        <!-- Orders by Status Pie Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Orders by Status</div>
            </div>
            <div id="ordersPieEmpty" class="chart-empty">
                <i class="fas fa-chart-pie"></i>
                <p>No order status data for the selected period.</p>
            </div>
            <div class="chart-canvas-wrap chart-canvas-wrap--pie" id="ordersPieWrap">
                <canvas id="ordersPieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="chart-container" style="margin-bottom: 30px;">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ordersData = @json($ordersByDate);
    const statusData = @json($ordersByStatus);

    const statusColors = {
        pending: '#f59e0b',
        pending_advance_payment: '#f59e0b',
        advance_paid: '#3b82f6',
        order_confirmed: '#3b82f6',
        processing: '#8b5cf6',
        shipped: '#14b8a6',
        delivered: '#10b981',
        completed: '#10b981',
        cancelled: '#ef4444'
    };

    const chartFont = { family: "'Inter', system-ui, sans-serif", size: 12 };
    const gridColor = 'rgba(107, 114, 128, 0.12)';

    function toggleChart(wrapId, emptyId, hasData) {
        const wrap = document.getElementById(wrapId);
        const empty = document.getElementById(emptyId);
        if (!wrap || !empty) return;
        wrap.style.display = hasData ? 'block' : 'none';
        empty.classList.toggle('is-visible', !hasData);
    }

    function formatStatusLabel(status) {
        return status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    }

    const hasLineData = ordersData.length > 0 && ordersData.some(d => Number(d.count) > 0);
    toggleChart('ordersLineWrap', 'ordersLineEmpty', hasLineData);

    if (hasLineData) {
        new Chart(document.getElementById('ordersLineChart'), {
            type: 'line',
            data: {
                labels: ordersData.map(d => d.date),
                datasets: [{
                    label: 'Orders',
                    data: ordersData.map(d => Number(d.count)),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointRadius: ordersData.length > 14 ? 0 : 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} order${ctx.parsed.y === 1 ? '' : 's'}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: chartFont, maxRotation: 45, minRotation: 0, autoSkip: true, maxTicksLimit: 10 }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { font: chartFont, precision: 0, stepSize: 1 }
                    }
                }
            }
        });
    }

    const hasPieData = statusData.length > 0 && statusData.some(d => Number(d.count) > 0);
    toggleChart('ordersPieWrap', 'ordersPieEmpty', hasPieData);

    if (hasPieData) {
        new Chart(document.getElementById('ordersPieChart'), {
            type: 'doughnut',
            data: {
                labels: statusData.map(d => formatStatusLabel(d.status)),
                datasets: [{
                    data: statusData.map(d => Number(d.count)),
                    backgroundColor: statusData.map(d => statusColors[d.status] || '#94a3b8'),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: chartFont,
                            padding: 14,
                            boxWidth: 12,
                            boxHeight: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        callbacks: {
                            label: ctx => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total ? Math.round((ctx.parsed / total) * 100) : 0;
                                return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
})();
</script>
@endsection
