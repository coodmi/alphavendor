@extends('layouts.dashboard')
@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- ── Header ── --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Analytics & Reports</h2>
            <p class="text-gray-500 text-sm mt-0.5">Filter by date range to see detailed statistics</p>
        </div>

        {{-- Date Filter --}}
        <form method="GET" action="{{ route('admin.analytics') }}"
              class="flex flex-wrap items-end gap-3 bg-white border border-gray-200 rounded-xl px-5 py-3 shadow-sm">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">From</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">To</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-semibold transition flex items-center gap-1.5">
                    <i class="fas fa-filter"></i> Apply
                </button>
                {{-- Quick presets --}}
                <a href="{{ route('admin.analytics', ['from' => now()->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-semibold transition">Today</a>
                <a href="{{ route('admin.analytics', ['from' => now()->subDays(6)->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-semibold transition">7 Days</a>
                <a href="{{ route('admin.analytics', ['from' => now()->startOfMonth()->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}"
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-semibold transition">This Month</a>
            </div>
        </form>
    </div>

    {{-- ── Selected Range Banner ── --}}
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl px-6 py-3 flex items-center gap-3 text-white text-sm">
        <i class="fas fa-calendar-alt"></i>
        <span>Showing data from <strong>{{ $from->format('M d, Y') }}</strong> to <strong>{{ $to->format('M d, Y') }}</strong></span>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 1 — SELECTED DATE RANGE
    ══════════════════════════════════════════════════════════════════════ --}}
    <div>
        <h3 class="text-base font-bold text-gray-700 mb-3 flex items-center gap-2">
            <span class="w-1 h-5 bg-teal-500 rounded-full inline-block"></span>
            Selected Range — Orders & New Members
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
            @php
            $rangeCards = [
                ['label'=>'Total Orders',       'value'=>$rangeStats['total_orders'],      'icon'=>'fa-shopping-cart',  'from'=>'from-blue-500',   'to'=>'to-blue-600'],
                ['label'=>'Retail Orders',      'value'=>$rangeStats['retailer_orders'],   'icon'=>'fa-shopping-bag',   'from'=>'from-teal-500',   'to'=>'to-teal-600'],
                ['label'=>'Wholesale Orders',   'value'=>$rangeStats['wholesaler_orders'], 'icon'=>'fa-boxes',          'from'=>'from-indigo-500', 'to'=>'to-indigo-600'],
                ['label'=>'Import Orders',      'value'=>$rangeStats['importer_orders'],   'icon'=>'fa-globe',          'from'=>'from-purple-500', 'to'=>'to-purple-600'],
                ['label'=>'New Users',          'value'=>$rangeStats['new_users'],         'icon'=>'fa-user-plus',      'from'=>'from-cyan-500',   'to'=>'to-cyan-600'],
                ['label'=>'New Retailers',      'value'=>$rangeStats['new_retailers'],     'icon'=>'fa-store',          'from'=>'from-emerald-500','to'=>'to-emerald-600'],
                ['label'=>'New Wholesalers',    'value'=>$rangeStats['new_wholesalers'],   'icon'=>'fa-warehouse',      'from'=>'from-violet-500', 'to'=>'to-violet-600'],
                ['label'=>'New Importers',      'value'=>$rangeStats['new_importers'],     'icon'=>'fa-ship',           'from'=>'from-sky-500',    'to'=>'to-sky-600'],
                ['label'=>'Returns & Refunds',  'value'=>$rangeStats['returns']+$rangeStats['refunds'], 'icon'=>'fa-undo-alt', 'from'=>'from-orange-500','to'=>'to-orange-600'],
                ['label'=>'Cancelled',          'value'=>$rangeStats['cancelled'],         'icon'=>'fa-times-circle',   'from'=>'from-red-500',    'to'=>'to-red-600'],
                ['label'=>'Exchange',           'value'=>$rangeStats['exchange'],          'icon'=>'fa-exchange-alt',   'from'=>'from-pink-500',   'to'=>'to-pink-600'],
            ];
            @endphp
            @foreach($rangeCards as $card)
            <div class="bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} rounded-xl p-5 text-white shadow">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-white/80 text-xs font-medium">{{ $card['label'] }}</p>
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas {{ $card['icon'] }} text-sm"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 2 — TODAY vs YESTERDAY side by side
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- TODAY --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-5 py-3 flex items-center gap-2">
                <i class="fas fa-sun text-white"></i>
                <h3 class="font-bold text-white">Today — {{ now()->format('M d, Y') }}</h3>
            </div>
            <div class="p-5 grid grid-cols-2 gap-3">
                @php
                $todayRows = [
                    ['Retail Orders',    $todayStats['retailer_orders'],   'fa-shopping-bag',  'text-teal-600',   'bg-teal-50'],
                    ['Wholesale Orders', $todayStats['wholesaler_orders'], 'fa-boxes',         'text-indigo-600', 'bg-indigo-50'],
                    ['Import Orders',    $todayStats['importer_orders'],   'fa-globe',         'text-purple-600', 'bg-purple-50'],
                    ['New Users',        $todayStats['new_users'],         'fa-user-plus',     'text-blue-600',   'bg-blue-50'],
                    ['New Retailers',    $todayStats['new_retailers'],     'fa-store',         'text-emerald-600','bg-emerald-50'],
                    ['New Wholesalers',  $todayStats['new_wholesalers'],   'fa-warehouse',     'text-violet-600', 'bg-violet-50'],
                    ['New Importers',    $todayStats['new_importers'],     'fa-ship',          'text-sky-600',    'bg-sky-50'],
                    ['Returns & Refunds',$todayStats['returns']+$todayStats['refunds'], 'fa-undo-alt','text-orange-600','bg-orange-50'],
                    ['Cancelled',        $todayStats['cancelled'],         'fa-times-circle',  'text-red-600',    'bg-red-50'],
                    ['Exchange',         $todayStats['exchange'],          'fa-exchange-alt',  'text-pink-600',   'bg-pink-50'],
                ];
                @endphp
                @foreach($todayRows as [$label, $val, $icon, $color, $bg])
                <div class="flex items-center gap-3 p-3 {{ $bg }} rounded-lg">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                        <i class="fas {{ $icon }} {{ $color }} text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="text-xl font-bold {{ $color }}">{{ $val }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- YESTERDAY --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-500 to-slate-600 px-5 py-3 flex items-center gap-2">
                <i class="fas fa-moon text-white"></i>
                <h3 class="font-bold text-white">Yesterday — {{ now()->subDay()->format('M d, Y') }}</h3>
            </div>
            <div class="p-5 grid grid-cols-2 gap-3">
                @php
                $yestRows = [
                    ['Retail Orders',    $yesterdayStats['retailer_orders'],   'fa-shopping-bag',  'text-teal-600',   'bg-teal-50'],
                    ['Wholesale Orders', $yesterdayStats['wholesaler_orders'], 'fa-boxes',         'text-indigo-600', 'bg-indigo-50'],
                    ['Import Orders',    $yesterdayStats['importer_orders'],   'fa-globe',         'text-purple-600', 'bg-purple-50'],
                    ['New Users',        $yesterdayStats['new_users'],         'fa-user-plus',     'text-blue-600',   'bg-blue-50'],
                    ['New Retailers',    $yesterdayStats['new_retailers'],     'fa-store',         'text-emerald-600','bg-emerald-50'],
                    ['New Wholesalers',  $yesterdayStats['new_wholesalers'],   'fa-warehouse',     'text-violet-600', 'bg-violet-50'],
                    ['New Importers',    $yesterdayStats['new_importers'],     'fa-ship',          'text-sky-600',    'bg-sky-50'],
                    ['Returns & Refunds',$yesterdayStats['returns']+$yesterdayStats['refunds'],'fa-undo-alt','text-orange-600','bg-orange-50'],
                    ['Cancelled',        $yesterdayStats['cancelled'],         'fa-times-circle',  'text-red-600',    'bg-red-50'],
                    ['Exchange',         $yesterdayStats['exchange'],          'fa-exchange-alt',  'text-pink-600',   'bg-pink-50'],
                ];
                @endphp
                @foreach($yestRows as [$label, $val, $icon, $color, $bg])
                <div class="flex items-center gap-3 p-3 {{ $bg }} rounded-lg">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                        <i class="fas {{ $icon }} {{ $color }} text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p class="text-xl font-bold {{ $color }}">{{ $val }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 3 — ALL TIME TOTALS
    ══════════════════════════════════════════════════════════════════════ --}}
    <div>
        <h3 class="text-base font-bold text-gray-700 mb-3 flex items-center gap-2">
            <span class="w-1 h-5 bg-slate-500 rounded-full inline-block"></span>
            All Time Totals
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
            $allTimeCards = [
                ['Total Orders',     $allTime['total_orders'],      'fa-shopping-cart', 'bg-blue-600'],
                ['Total Returns',    $allTime['total_returns'],     'fa-undo-alt',      'bg-orange-500'],
                ['Total Refunds',    $allTime['total_refunds'],     'fa-money-bill-wave','bg-yellow-500'],
                ['Total Cancelled',  $allTime['total_cancelled'],   'fa-times-circle',  'bg-red-500'],
                ['Total Exchange',   $allTime['total_exchange'],    'fa-exchange-alt',  'bg-pink-500'],
                ['Total Retailers',  $allTime['total_retailers'],   'fa-store',         'bg-teal-600'],
                ['Total Wholesalers',$allTime['total_wholesalers'], 'fa-boxes',         'bg-indigo-600'],
                ['Total Importers',  $allTime['total_importers'],   'fa-globe',         'bg-purple-600'],
                ['Total Users',      $allTime['total_users'],       'fa-users',         'bg-cyan-600'],
            ];
            @endphp
            @foreach($allTimeCards as [$label, $val, $icon, $bg])
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                <div class="w-10 h-10 {{ $bg }} rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $icon }} text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $val }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 4 — CHART
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Daily Orders by Vendor Type</h3>
        <canvas id="ordersChart" height="80"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Retail',
                data: @json($chartRetailer),
                backgroundColor: 'rgba(20,184,166,0.7)',
                borderRadius: 4,
            },
            {
                label: 'Wholesale',
                data: @json($chartWholesaler),
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderRadius: 4,
            },
            {
                label: 'Import',
                data: @json($chartImporter),
                backgroundColor: 'rgba(168,85,247,0.7)',
                borderRadius: 4,
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            x: { stacked: false, grid: { display: false } },
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endsection
