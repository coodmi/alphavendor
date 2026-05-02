@extends('layouts.dashboard')

@section('title', 'Advance Payments Management')
@section('page-title', 'Advance Payments')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Total Requests</p>
                <h3 class="text-3xl font-bold">{{ $stats['total'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-invoice-dollar text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm mb-1">Pending</p>
                <h3 class="text-3xl font-bold">{{ $stats['pending'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm mb-1">Approved</p>
                <h3 class="text-3xl font-bold">{{ $stats['approved'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Paid</p>
                <h3 class="text-3xl font-bold">{{ $stats['paid'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-money-check-alt text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Completed</p>
                <h3 class="text-3xl font-bold">{{ $stats['completed'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-double text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm mb-1">Total Amount</p>
                <h3 class="text-2xl font-bold"> {{ currency($stats['total_amount']) }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Advance Payments Table -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Advance Payments Management</h2>
            <p class="text-gray-500 mt-1">Manage all advance payment requests</p>
        </div>
        <div class="flex gap-3">
            <select id="statusFilter" onchange="filterByStatus()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="paid">Paid</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select id="paymentMethodFilter" onchange="filterByPaymentMethod()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Payment Methods</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="rocket">Rocket</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>
    </div>

    @if($payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Advance</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">#{{ $payment->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-gray-800">{{ $payment->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->contact_number }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-gray-800">{{ Str::limit($payment->product->name, 30) }}</div>
                                <div class="text-xs text-gray-500">Qty: {{ $payment->quantity }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">{{ $payment->vendor->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800"> {{ currency($payment->total_amount) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-semibold text-green-600"> {{ currency($payment->advance_amount) }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->advance_percentage }}%</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded text-xs font-medium
                                {{ $payment->payment_method === 'bkash' ? 'bg-pink-100 text-pink-700' : '' }}
                                {{ $payment->payment_method === 'nagad' ? 'bg-teal-100 text-teal-800' : '' }}
                                {{ $payment->payment_method === 'rocket' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $payment->payment_method === 'bank_transfer' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $payment->status_badge }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.advance-payments.show', $payment) }}" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-file-invoice-dollar text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-500 text-lg">No advance payment requests found</p>
        </div>
    @endif
</div>

<script>
function filterByStatus() {
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

function filterByPaymentMethod() {
    const method = document.getElementById('paymentMethodFilter').value;
    const url = new URL(window.location.href);
    if (method) {
        url.searchParams.set('payment_method', method);
    } else {
        url.searchParams.delete('payment_method');
    }
    window.location.href = url.toString();
}
</script>
@endsection
