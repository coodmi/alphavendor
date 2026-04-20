@extends('layouts.dashboard')

@section('title', 'Manual Payment Verification')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manual Payment Verification</h1>
            <p class="text-gray-600">Verify bKash, Nagad, and other manual payments</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Pending Payments</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending_count'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Pending Amount</p>
                    <p class="text-2xl font-bold text-yellow-700">৳{{ number_format($stats['pending_amount'], 2) }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Verified Today</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['verified_today'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Verified Amount Today</p>
                    <p class="text-2xl font-bold text-green-700">৳{{ number_format($stats['verified_today_amount'], 2) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Pending Payments Table -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                Pending Payments ({{ $pendingPayments->total() }})
            </h2>
        </div>

        @if($pendingPayments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-blue-600 hover:underline font-medium">
                                        {{ $payment->order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($payment->payment_method === 'bkash')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-pink-100 text-pink-800">bKash</span>
                                    @elseif($payment->payment_method === 'nagad')
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-teal-100 text-teal-900">Nagad</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">{{ ucfirst($payment->payment_method) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm">{{ $payment->sender_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $payment->transaction_id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900">৳{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->created_at->format('M d, Y') }}<br>
                                    <span class="text-xs">{{ $payment->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <!-- Verify Button -->
                                        <button onclick="openVerifyModal({{ $payment->id }}, '{{ $payment->order->order_number }}', '{{ $payment->transaction_id }}', {{ $payment->amount }})" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition">
                                            ✓ Verify
                                        </button>
                                        <!-- Reject Button -->
                                        <button onclick="openRejectModal({{ $payment->id }}, '{{ $payment->order->order_number }}')" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition">
                                            ✕ Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingPayments->links() }}
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg font-medium">No pending payments</p>
                <p class="text-sm">All payments have been processed</p>
            </div>
        @endif
    </div>

    <!-- Recent Verified Payments -->
    @if($verifiedPayments->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    Recently Verified
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($verifiedPayments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-blue-600 hover:underline">
                                        {{ $payment->order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $payment->payment_method === 'bkash' ? 'bg-pink-100 text-pink-800' : 'bg-teal-100 text-teal-900' }}">
                                        {{ $payment->payment_method_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $payment->transaction_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">৳{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $payment->verifier->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->verified_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Recent Rejected Payments -->
    @if($rejectedPayments->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    Recently Rejected
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rejectedPayments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->order->order_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $payment->transaction_id }}</td>
                                <td class="px-6 py-4 text-sm text-red-600">{{ $payment->admin_note }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->verified_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Verify Payment</h3>
        </div>
        <form id="verifyForm" method="POST">
            @csrf
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    You are about to verify payment for order <strong id="verifyOrderNumber"></strong>
                </p>
                <div class="bg-green-50 border border-green-200 rounded p-3 mb-4">
                    <p class="text-sm text-gray-600">Transaction ID: <strong id="verifyTxnId" class="font-mono"></strong></p>
                    <p class="text-sm text-gray-600">Amount: <strong id="verifyAmount"></strong></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Note (Optional)</label>
                    <textarea name="admin_note" rows="2" class="w-full border rounded px-3 py-2" placeholder="Add any notes..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Confirm Verification</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Reject Payment</h3>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    You are about to reject payment for order <strong id="rejectOrderNumber"></strong>
                </p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection *</label>
                    <textarea name="admin_note" rows="3" required class="w-full border rounded px-3 py-2" placeholder="e.g., Transaction ID not found, Amount mismatch..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<script>
function openVerifyModal(paymentId, orderNumber, txnId, amount) {
    document.getElementById('verifyForm').action = `/admin/manual-payments/${paymentId}/verify`;
    document.getElementById('verifyOrderNumber').textContent = orderNumber;
    document.getElementById('verifyTxnId').textContent = txnId;
    document.getElementById('verifyAmount').textContent = '৳' + parseFloat(amount).toFixed(2);
    document.getElementById('verifyModal').classList.remove('hidden');
    document.getElementById('verifyModal').classList.add('flex');
}

function closeVerifyModal() {
    document.getElementById('verifyModal').classList.add('hidden');
    document.getElementById('verifyModal').classList.remove('flex');
}

function openRejectModal(paymentId, orderNumber) {
    document.getElementById('rejectForm').action = `/admin/manual-payments/${paymentId}/reject`;
    document.getElementById('rejectOrderNumber').textContent = orderNumber;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

// Close modals when clicking outside
document.getElementById('verifyModal').addEventListener('click', function(e) {
    if (e.target === this) closeVerifyModal();
});
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endsection
