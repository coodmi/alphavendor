@extends('layouts.dashboard')

@section('title', 'User Activity Logs')
@section('page-title', 'User Activity Logs')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Activity Logs</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor user activities and system events</p>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-5">
        <i class="fas fa-check-circle text-green-500"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.user-activity') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
        <div class="flex flex-wrap gap-3 items-end">
            <!-- Search -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ $searchFilter }}" placeholder="Name, email, description..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            <!-- Action Filter -->
            <div class="min-w-[160px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Action</label>
                <select name="action" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none bg-white">
                    <option value="">All Actions</option>
                    <option value="order_placed"    {{ $actionFilter === 'order_placed'    ? 'selected' : '' }}>Order Placed</option>
                    <option value="product_created" {{ $actionFilter === 'product_created' ? 'selected' : '' }}>Product Created</option>
                    <option value="user_registered" {{ $actionFilter === 'user_registered' ? 'selected' : '' }}>User Registered</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div class="min-w-[160px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Date</label>
                <input type="date" name="date" value="{{ $dateFilter }}"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.user-activity') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </div>
    </form>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-list text-indigo-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500">Total Activities</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['last_24h'] }}</p>
                <p class="text-xs text-gray-500">Last 24 Hours</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-check text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['active_users'] }}</p>
                <p class="text-xs text-gray-500">Active Users</p>
            </div>
        </div>
    </div>

    <!-- Activity List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">
                Activity Log
                @if($actionFilter || $dateFilter || $searchFilter)
                    <span class="ml-2 text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full font-semibold">Filtered</span>
                @endif
            </h3>
            <span class="text-xs text-gray-400">{{ $activities->count() }} records</span>
        </div>

        @if($activities->isEmpty())
        <div class="text-center py-16">
            <i class="fas fa-search text-4xl text-gray-200 mb-3 block"></i>
            <p class="text-gray-400 font-medium">No activities found</p>
            <p class="text-gray-300 text-sm mt-1">Try adjusting your filters</p>
        </div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($activities->take(100) as $activity)
            @php
                $actionConfig = [
                    'order_placed'    => ['icon' => 'fa-shopping-cart', 'bg' => 'bg-yellow-100', 'color' => 'text-yellow-600', 'badge' => 'bg-yellow-100 text-yellow-700'],
                    'product_created' => ['icon' => 'fa-box',           'bg' => 'bg-blue-100',   'color' => 'text-blue-600',   'badge' => 'bg-blue-100 text-blue-700'],
                    'user_registered' => ['icon' => 'fa-user-plus',     'bg' => 'bg-green-100',  'color' => 'text-green-600',  'badge' => 'bg-green-100 text-green-700'],
                    'login'           => ['icon' => 'fa-sign-in-alt',   'bg' => 'bg-teal-100',   'color' => 'text-teal-600',   'badge' => 'bg-teal-100 text-teal-700'],
                ];
                $cfg = $actionConfig[$activity['action']] ?? ['icon' => 'fa-info-circle', 'bg' => 'bg-gray-100', 'color' => 'text-gray-600', 'badge' => 'bg-gray-100 text-gray-600'];
            @endphp
            <div class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition">
                <!-- Icon -->
                <div class="w-10 h-10 {{ $cfg['bg'] }} rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas {{ $cfg['icon'] }} {{ $cfg['color'] }} text-sm"></i>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-gray-800 text-sm">{{ $activity['user_name'] }}</span>
                        @if($activity['user_email'])
                            <span class="text-xs text-gray-400">{{ $activity['user_email'] }}</span>
                        @endif
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $cfg['badge'] }}">
                            {{ $activity['action_label'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-0.5">{{ $activity['description'] }}</p>
                    @if($activity['ip_address'] !== '—')
                        <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-map-marker-alt mr-1"></i>{{ $activity['ip_address'] }}</p>
                    @endif
                </div>

                <!-- Time -->
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-400">{{ $activity['created_at']?->diffForHumans() }}</p>
                    <p class="text-xs text-gray-300 mt-0.5">{{ $activity['created_at']?->format('M d, Y') }}</p>
                    <a href="{{ route('admin.user-activity.details', $activity['user_id']) }}"
                        class="inline-flex items-center gap-1 mt-1 text-xs text-indigo-500 hover:text-indigo-700 font-medium">
                        <i class="fas fa-eye"></i> Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
