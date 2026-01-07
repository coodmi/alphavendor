@extends('layouts.dashboard')

@section('title', 'My Wallet')
@section('page-title', 'My Wallet')

@section('content')
<div class="space-y-6">
    <!-- Wallet Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Available Balance</h3>
                <i class="fas fa-wallet text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($wallet->balance, 2) }}</p>
            <p class="text-xs opacity-75 mt-1">Ready to withdraw</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Pending Balance</h3>
                <i class="fas fa-clock text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($wallet->pending_balance, 2) }}</p>
            <p class="text-xs opacity-75 mt-1">{{ $pendingOrders }} pending orders</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Earned</h3>
                <i class="fas fa-chart-line text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($wallet->total_earned, 2) }}</p>
            <p class="text-xs opacity-75 mt-1">Lifetime earnings</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Withdrawn</h3>
                <i class="fas fa-money-bill-wave text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">${{ number_format($wallet->total_withdrawn, 2) }}</p>
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
                        <p class="text-2xl font-bold text-green-600">${{ number_format($thisMonthEarnings, 2) }}</p>
                    </div>
                    <i class="fas fa-arrow-up text-green-500 text-2xl"></i>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded">
                    <div>
                        <p class="text-sm text-gray-600">Last Month</p>
                        <p class="text-2xl font-bold text-gray-700">${{ number_format($lastMonthEarnings, 2) }}</p>
                    </div>
                    <i class="fas fa-calendar text-gray-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('withdrawals.create') }}" class="block p-4 border-2 border-orange-500 text-orange-500 rounded hover:bg-orange-50 transition text-center font-semibold">
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
                                    -${{ number_format($transaction->amount, 2) }}
                                @else
                                    +${{ number_format($transaction->amount, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y') }}
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
