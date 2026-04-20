@extends('layouts.admin')

@section('title', 'Withdrawal Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Withdrawal Management</h1>
            <p class="text-gray-100 mt-1">Manage vendor withdrawal requests</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-teal-50 border-l-4 border-teal-600 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-700 text-sm font-semibold uppercase">Pending</p>
                    <p class="text-3xl font-bold text-teal-700 mt-2">{{ $stats['pending'] }}</p>
                    <p class="text-teal-700 text-sm mt-1">{{ currency_symbol() }}{{ number_format($stats['total_pending_amount'], 2) }}</p>
                </div>
                <i class="fas fa-clock text-4xl text-teal-300"></i>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-semibold uppercase">Approved</p>
                    <p class="text-3xl font-bold text-blue-700 mt-2">{{ $stats['approved'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-blue-300"></i>
            </div>
        </div>

        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-semibold uppercase">Completed</p>
                    <p class="text-3xl font-bold text-green-700 mt-2">{{ $stats['completed'] }}</p>
                    <p class="text-green-600 text-sm mt-1">{{ currency_symbol() }}{{ number_format($stats['total_completed_amount'], 2) }}</p>
                </div>
                <i class="fas fa-check-double text-4xl text-green-300"></i>
            </div>
        </div>

        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-semibold uppercase">Rejected</p>
                    <p class="text-3xl font-bold text-red-700 mt-2">{{ $stats['rejected'] }}</p>
                </div>
                <i class="fas fa-times-circle text-4xl text-red-300"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-white mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Payment Method</label>
                <select name="payment_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600">
                    <option value="">All Methods</option>
                    <option value="bank" {{ request('payment_type') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="bkash" {{ request('payment_type') == 'bkash' ? 'selected' : '' }}>bKash</option>
                    <option value="nagad" {{ request('payment_type') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Vendor</label>
                <select name="vendor_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600">
                    <option value="">All Vendors</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }} ({{ ucfirst($vendor->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Search</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Withdrawal #" 
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600">
                    <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Withdrawals Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Withdrawal #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Vendor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-100 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="withdrawal_ids[]" value="{{ $withdrawal->id }}" class="withdrawal-checkbox rounded">
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-white">{{ $withdrawal->withdrawal_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $withdrawal->vendor->name }}</p>
                                    <p class="text-xs text-gray-200">{{ ucfirst($withdrawal->vendor->role) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-white">{{ currency_symbol() }}{{ number_format($withdrawal->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($withdrawal->withdrawalMethod)
                                    <div class="flex items-center gap-2">
                                        @if($withdrawal->withdrawalMethod->type == 'bank')
                                            <i class="fas fa-university text-blue-500"></i>
                                            <span class="text-sm">Bank</span>
                                        @elseif($withdrawal->withdrawalMethod->type == 'bkash')
                                            <i class="fas fa-mobile-alt text-pink-500"></i>
                                            <span class="text-sm">bKash</span>
                                        @elseif($withdrawal->withdrawalMethod->type == 'nagad')
                                            <i class="fas fa-mobile-alt text-teal-600"></i>
                                            <span class="text-sm">Nagad</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($withdrawal->status == 'pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-800">Pending</span>
                                @elseif($withdrawal->status == 'approved')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @elseif($withdrawal->status == 'completed')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                @elseif($withdrawal->status == 'rejected')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-100">
                                {{ $withdrawal->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" 
                                   class="text-teal-700 hover:text-teal-900 font-semibold">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-200">
                                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                <p>No withdrawal requests found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>

<script>
// Select all checkboxes
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.withdrawal-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endsection
