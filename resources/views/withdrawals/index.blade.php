@extends('layouts.dashboard')

@section('title', 'Withdrawals')
@section('page-title', 'Withdrawal Requests')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Wallet Balance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
            <h3 class="text-sm text-gray-600 mb-2">Available Balance</h3>
            <p class="text-3xl font-bold text-green-600">${{ number_format($wallet->balance ?? 0, 2) }}</p>
        </div>
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
            <h3 class="text-sm text-gray-600 mb-2">Pending Balance</h3>
            <p class="text-3xl font-bold text-yellow-600">${{ number_format($wallet->pending_balance ?? 0, 2) }}</p>
        </div>
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h3 class="text-sm text-gray-600 mb-2">Total Withdrawn</h3>
            <p class="text-3xl font-bold text-blue-600">${{ number_format($wallet->total_withdrawn ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <a href="{{ route('withdrawals.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded hover:bg-orange-600 font-semibold">
            <i class="fas fa-plus mr-2"></i>New Withdrawal Request
        </a>
        <a href="{{ route('withdrawals.payment-methods') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded hover:bg-gray-300 font-semibold">
            <i class="fas fa-credit-card mr-2"></i>Payment Methods
        </a>
    </div>

    <!-- Withdrawal History -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold">Withdrawal History</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Withdrawal #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $withdrawal->withdrawal_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                ${{ number_format($withdrawal->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($withdrawal->withdrawalMethod)
                                    {{ ucfirst($withdrawal->withdrawalMethod->type) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded text-xs font-semibold
                                    @if($withdrawal->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($withdrawal->status === 'approved') bg-blue-100 text-blue-800
                                    @elseif($withdrawal->status === 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $withdrawal->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($withdrawal->admin_notes)
                                    <span class="text-red-600" title="{{ $withdrawal->admin_notes }}">
                                        <i class="fas fa-comment-dots"></i> Admin Note
                                    </span>
                                @elseif($withdrawal->notes)
                                    {{ Str::limit($withdrawal->notes, 30) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-money-bill-wave text-4xl mb-2 opacity-50"></i>
                                <p>No withdrawal requests yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
