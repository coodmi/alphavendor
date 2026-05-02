@extends('layouts.dashboard')
@section('title', 'My Invoices')
@section('page-title', 'My Invoices')

@section('sidebar-menu')
    @php $role = auth()->user()->role; @endphp
    @if($role === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($role === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($role === 'exporter' || $role === 'importer')
        @include('dashboards.partials.exporter-sidebar')
    @else
        @include('dashboards.partials.user-sidebar')
    @endif
@endsection

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                @if(in_array($user->role, ['retailer','wholesaler','exporter','importer']))
                    Sales Invoices
                @else
                    My Purchase Invoices
                @endif
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                @if(in_array($user->role, ['retailer','wholesaler','exporter','importer']))
                    All orders placed with your store
                @else
                    All your order invoices in one place
                @endif
            </p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-5 text-white shadow">
            <p class="text-teal-100 text-xs mb-1">Total Invoices</p>
            <p class="text-3xl font-bold">{{ $invoices->total() }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow">
            <p class="text-green-100 text-xs mb-1">Delivered</p>
            <p class="text-3xl font-bold">{{ $stats['delivered'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-5 text-white shadow">
            <p class="text-yellow-100 text-xs mb-1">Pending</p>
            <p class="text-3xl font-bold">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-5 text-white shadow">
            <p class="text-blue-100 text-xs mb-1">Total Value</p>
            <p class="text-xl font-bold">{{ currency($stats['total_amount']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5">
        <form method="GET" action="{{ route('invoices.my') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Search Order #</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ORD-..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div class="min-w-[140px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Status</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">From</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">To</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('invoices.my') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Invoice Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Invoice / Order</th>
                    @if(in_array($user->role, ['retailer','wholesaler','exporter','importer']))
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Customer</th>
                    @else
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Vendor</th>
                    @endif
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Items</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Payment</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Invoice</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($invoices as $order)
                @php
                    $statusColors = [
                        'pending'    => 'bg-yellow-100 text-yellow-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'shipped'    => 'bg-indigo-100 text-indigo-700',
                        'delivered'  => 'bg-green-100 text-green-700',
                        'cancelled'  => 'bg-red-100 text-red-700',
                        'completed'  => 'bg-teal-100 text-teal-700',
                    ];
                    $payColors = [
                        'paid'                 => 'bg-green-100 text-green-700',
                        'unpaid'               => 'bg-red-100 text-red-600',
                        'pending_verification' => 'bg-yellow-100 text-yellow-700',
                        'refunded'             => 'bg-purple-100 text-purple-700',
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-teal-600 text-sm">{{ $order->order_number }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">#{{ $order->id }}</div>
                    </td>
                    <td class="px-5 py-4">
                        @if(in_array($user->role, ['retailer','wholesaler','exporter','importer']))
                            <div class="text-sm font-medium text-gray-800">{{ $order->user->name ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $order->phone }}</div>
                        @else
                            <div class="text-sm font-medium text-gray-800">{{ $order->vendor->name ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ ucfirst($order->vendor->role ?? '') }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-sm text-gray-700">{{ $order->items->count() }} item(s)</div>
                        <div class="text-xs text-gray-400">
                            {{ $order->items->take(2)->pluck('product_name')->implode(', ') }}
                            @if($order->items->count() > 2) +{{ $order->items->count() - 2 }} more @endif
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-bold text-gray-800">{{ currency($order->total) }}</div>
                        @if($order->delivery_charge > 0)
                        <div class="text-xs text-gray-400">+{{ currency($order->delivery_charge) }} delivery</div>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $payColors[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-sm text-gray-700">{{ $order->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold transition shadow-sm">
                            <i class="fas fa-file-invoice"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center">
                        <i class="fas fa-file-invoice text-5xl text-gray-200 mb-4 block"></i>
                        <p class="text-gray-400 font-medium">No invoices found</p>
                        <p class="text-gray-300 text-sm mt-1">Your invoices will appear here once you have orders</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($invoices->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $invoices->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
