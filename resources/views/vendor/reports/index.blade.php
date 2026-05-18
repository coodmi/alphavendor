@extends('layouts.dashboard')
@section('title', 'Report Analytics')
@section('page-title', 'Report Analytics')

@section('sidebar-menu')
    @php $userRole = auth()->user()->role; @endphp
    @if($userRole === 'retailer')       @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler') @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter')   @include('dashboards.partials.exporter-sidebar')
    @elseif($userRole === 'importer')   @include('dashboards.partials.importer-sidebar')
    @endif
@endsection

@section('content')
<div class="space-y-6">

    {{-- Date Filter --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm p-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">From</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}"
                   class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">To</label>
            <input type="date" name="date_to" value="{{ $dateTo }}"
                   class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        {{-- Quick filters --}}
        <a href="?date_from={{ now()->toDateString() }}&date_to={{ now()->toDateString() }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">Today</a>
        <a href="?date_from={{ now()->subDay()->toDateString() }}&date_to={{ now()->subDay()->toDateString() }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">Yesterday</a>
        <a href="?date_from={{ now()->startOfMonth()->toDateString() }}&date_to={{ now()->toDateString() }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">This Month</a>
        <a href="?date_from={{ now()->subDays(29)->toDateString() }}&date_to={{ now()->toDateString() }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">Last 30 Days</a>
    </form>

    {{-- Row 1: Orders --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php
        $cards1 = [
            ['label'=>'Today Total Orders',     'value'=>$stats['today_orders'],     'icon'=>'fa-shopping-cart',  'bg'=>'bg-blue-500'],
            ['label'=>'Yesterday Total Orders', 'value'=>$stats['yesterday_orders'], 'icon'=>'fa-calendar-day',   'bg'=>'bg-indigo-500'],
            ['label'=>'Total Orders',           'value'=>$stats['total_orders'],     'icon'=>'fa-boxes',          'bg'=>'bg-purple-500'],
        ];
        @endphp
        @foreach($cards1 as $c)
        <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                <i class="fas {{ $c['icon'] }} text-white text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $c['value'] }}</p>
                <p class="text-xs text-gray-500 font-medium">{{ $c['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Row 2: Products --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php
        $cards2 = [
            ['label'=>'Product Sell (Qty)',  'value'=>number_format($stats['product_sell']),     'icon'=>'fa-tag',        'bg'=>'bg-teal-500'],
            ['label'=>'Product Wishlist',    'value'=>number_format($stats['product_wishlist']), 'icon'=>'fa-heart',      'bg'=>'bg-pink-500'],
            ['label'=>'Total Stock (Qty)',   'value'=>number_format($stats['product_stock']),    'icon'=>'fa-warehouse',  'bg'=>'bg-orange-500'],
        ];
        @endphp
        @foreach($cards2 as $c)
        <div class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                <i class="fas {{ $c['icon'] }} text-white text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $c['value'] }}</p>
                <p class="text-xs text-gray-500 font-medium">{{ $c['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Row 3: Returns, Cancels, Exchange --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
        $cards3 = [
            ['label'=>'Total Return & Refund',  'value'=>$stats['total_return'],   'icon'=>'fa-undo',        'color'=>'text-red-600',    'bg'=>'bg-red-50'],
            ['label'=>'Today Return & Refund',  'value'=>$stats['today_return'],   'icon'=>'fa-undo-alt',    'color'=>'text-red-500',    'bg'=>'bg-red-50'],
            ['label'=>'Today Cancel Orders',    'value'=>$stats['today_cancel'],   'icon'=>'fa-times-circle','color'=>'text-orange-600', 'bg'=>'bg-orange-50'],
            ['label'=>'Total Cancel Orders',    'value'=>$stats['total_cancel'],   'icon'=>'fa-ban',         'color'=>'text-orange-500', 'bg'=>'bg-orange-50'],
            ['label'=>'Total Exchange',         'value'=>$stats['total_exchange'], 'icon'=>'fa-exchange-alt','color'=>'text-blue-600',   'bg'=>'bg-blue-50'],
            ['label'=>'Today Exchange',         'value'=>$stats['today_exchange'], 'icon'=>'fa-retweet',     'color'=>'text-blue-500',   'bg'=>'bg-blue-50'],
        ];
        @endphp
        @foreach($cards3 as $c)
        <div class="bg-white rounded-2xl shadow-sm p-4 text-center">
            <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} flex items-center justify-center mx-auto mb-2">
                <i class="fas {{ $c['icon'] }} {{ $c['color'] }}"></i>
            </div>
            <p class="text-xl font-bold text-gray-800">{{ $c['value'] }}</p>
            <p class="text-xs text-gray-500 leading-tight mt-0.5">{{ $c['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Line Chart: Orders per day --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
                Orders Per Day
                <span class="text-xs text-gray-400 font-normal ml-2">{{ $dateFrom }} → {{ $dateTo }}</span>
            </h3>
            <canvas id="ordersChart" height="100"></canvas>
        </div>

        {{-- Pie Chart: Order Status --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>Order Status
            </h3>
            <canvas id="statusChart" height="200"></canvas>
            {{-- Legend --}}
            <div class="mt-4 space-y-1.5">
                @php
                $statusColors = [
                    'pending'           => '#eab308',
                    'order_confirmed'   => '#6366f1',
                    'processing'        => '#06b6d4',
                    'shipped'           => '#a855f7',
                    'delivered'         => '#22c55e',
                    'cancelled'         => '#ef4444',
                ];
                @endphp
                @foreach($statusBreakdown as $status => $count)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full inline-block"
                              style="background:{{ $statusColors[$status] ?? '#94a3b8' }}"></span>
                        <span class="text-gray-600">{{ \App\Helpers\OrderStatus::label($status) }}</span>
                    </div>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Links to detailed reports --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
        $links = [
            ['label'=>'Product Sales',      'route'=>'vendor.reports.product-sales',      'icon'=>'fa-chart-bar',   'color'=>'bg-indigo-600'],
            ['label'=>'Wishlist Report',    'route'=>'vendor.reports.product-wishlist',   'icon'=>'fa-heart',       'color'=>'bg-pink-600'],
            ['label'=>'Stock Report',       'route'=>'vendor.reports.product-stock',      'icon'=>'fa-boxes',       'color'=>'bg-teal-600'],
            ['label'=>'Commission History', 'route'=>'vendor.reports.commission-history', 'icon'=>'fa-money-bill',  'color'=>'bg-green-600'],
        ];
        @endphp
        @foreach($links as $l)
        <a href="{{ route($l['route']) }}"
           class="{{ $l['color'] }} hover:opacity-90 text-white rounded-2xl p-4 flex items-center gap-3 transition">
            <i class="fas {{ $l['icon'] }} text-xl"></i>
            <span class="font-semibold text-sm">{{ $l['label'] }}</span>
        </a>
        @endforeach
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Line Chart: Orders per day ────────────────────────────────────────────────
const chartLabels = @json($chartData->keys());
const chartValues = @json($chartData->values());

new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Orders',
            data: chartValues,
            backgroundColor: 'rgba(99,102,241,0.15)',
            borderColor: '#6366f1',
            borderWidth: 2,
            borderRadius: 6,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } },
            x: { ticks: { maxTicksLimit: 10 } }
        }
    }
});

// ── Pie Chart: Order Status ───────────────────────────────────────────────────
const statusData   = @json($statusBreakdown);
const statusColors = {
    pending:         '#eab308',
    order_confirmed: '#6366f1',
    processing:      '#06b6d4',
    shipped:         '#a855f7',
    delivered:       '#22c55e',
    cancelled:       '#ef4444',
};

const pieLabels = Object.keys(statusData);
const pieValues = Object.values(statusData);
const pieColors = pieLabels.map(s => statusColors[s] || '#94a3b8');

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: pieLabels,
        datasets: [{
            data: pieValues,
            backgroundColor: pieColors,
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        cutout: '65%',
    }
});
</script>
@endsection
