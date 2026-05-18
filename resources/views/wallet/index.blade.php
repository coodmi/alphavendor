@extends('layouts.dashboard')

@section('title', 'My Wallet')
@section('page-title', 'My Wallet')

@section('sidebar-menu')
    @php
        $userRole = auth()->user()->role;
    @endphp
    @if($userRole === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter')
        @include('dashboards.partials.exporter-sidebar')
    @elseif($userRole === 'importer')
        @include('dashboards.partials.importer-sidebar')
    @endif
@endsection

@section('content')
<div class="space-y-6">
    <!-- Wallet Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Available Balance</h3>
                <i class="fas fa-wallet text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold"> {{ currency($wallet->balance) }}</p>
            <p class="text-xs opacity-75 mt-1">Ready to withdraw</p>
        </div>

        <div class="bg-gradient-to-br from-teal-600 to-teal-700 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Pending Balance</h3>
                <i class="fas fa-clock text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold"> {{ currency($wallet->pending_balance) }}</p>
            <p class="text-xs opacity-75 mt-1">{{ $pendingOrders }} pending orders</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Earned</h3>
                <i class="fas fa-chart-line text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold"> {{ currency($wallet->total_earned) }}</p>
            <p class="text-xs opacity-75 mt-1">Lifetime earnings</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Withdrawn</h3>
                <i class="fas fa-money-bill-wave text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold"> {{ currency($wallet->total_withdrawn) }}</p>
            <p class="text-xs opacity-75 mt-1">All time</p>
        </div>
    </div>

    <!-- Earnings Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Earnings Overview</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-green-50 rounded">
                    <div>
                        <p class="text-sm text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-green-600"> {{ currency($thisMonthEarnings) }}</p>
                    </div>
                    <i class="fas fa-arrow-up text-green-500 text-2xl"></i>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded">
                    <div>
                        <p class="text-sm text-gray-600">Last Month</p>
                        <p class="text-2xl font-bold text-gray-700"> {{ currency($lastMonthEarnings) }}</p>
                    </div>
                    <i class="fas fa-calendar text-gray-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('withdrawals.create') }}" class="block p-4 border-2 border-teal-600 text-teal-600 rounded hover:bg-teal-50 transition text-center font-semibold">
                    <i class="fas fa-hand-holding-usd mr-2"></i>Request Withdrawal
                </a>
                <a href="{{ route('vendor.orders') }}" class="block p-4 border-2 border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition text-center font-semibold">
                    <i class="fas fa-shopping-cart mr-2"></i>View Orders
                </a>
                <a href="{{ route('withdrawals.payment-methods') }}" class="block p-4 border-2 border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition text-center font-semibold">
                    <i class="fas fa-university mr-2"></i>Payment Methods
                </a>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Transaction History</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->transaction_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    @if($transaction->type === 'sale') bg-green-100 text-green-800
                                    @elseif($transaction->type === 'withdrawal') bg-blue-100 text-blue-800
                                    @elseif($transaction->type === 'refund') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $transaction->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold
                                @if($transaction->type === 'withdrawal' || $transaction->type === 'refund') text-red-600
                                @else text-green-600
                                @endif">
                                @if($transaction->type === 'withdrawal' || $transaction->type === 'refund')
                                    -{{ currency($transaction->amount) }}
                                @else
                                    +{{ currency($transaction->amount) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-teal-100 text-teal-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($transaction->order_id && $transaction->order)
                                    @php
                                        $invoiceRoute = match(auth()->user()->role) {
                                            'retailer' => 'retailer.orders.invoice',
                                            'wholesaler' => 'wholesaler.orders.invoice',
                                            default => 'orders.invoice',
                                        };
                                    @endphp
                                    <a href="{{ route($invoiceRoute, $transaction->order) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        <i class="fas fa-file-invoice"></i> View
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-receipt text-4xl mb-2 opacity-50"></i>
                                <p>No transactions yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
