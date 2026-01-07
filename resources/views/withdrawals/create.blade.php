@extends('layouts.dashboard')

@section('title', 'New Withdrawal Request')
@section('page-title', 'Request Withdrawal')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('withdrawals.index') }}" class="text-orange-500 hover:text-orange-600">
            <i class="fas fa-arrow-left mr-2"></i>Back to Withdrawals
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Wallet Balance -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 mb-6">
        <h3 class="text-sm opacity-90 mb-2">Available Balance</h3>
        <p class="text-4xl font-bold">${{ number_format($wallet->balance ?? 0, 2) }}</p>
        <p class="text-sm opacity-75 mt-2">Minimum withdrawal: $10.00</p>
    </div>

    @if(!$paymentMethods || $paymentMethods->isEmpty())
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex items-start gap-4">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                <div>
                    <h3 class="font-bold text-yellow-800 mb-2">Payment Method Required</h3>
                    <p class="text-yellow-700 mb-4">Please add a payment method before requesting a withdrawal.</p>
                    <a href="{{ route('withdrawals.payment-methods') }}" class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Add Payment Method
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('withdrawals.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Payment Method *</label>
                    <select name="withdrawal_method_id" required class="w-full border border-gray-300 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">Select Payment Method</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">
                                {{ ucfirst($method->type) }} -
                                @if($method->type === 'bank')
                                    {{ $method->account_name }} ({{ substr($method->account_number, -4) }})
                                @elseif($method->type === 'paypal')
                                    {{ $method->paypal_email }}
                                @else
                                    {{ $method->account_name }}
                                @endif
                                @if($method->is_default)
                                    <span class="text-green-600">(Default)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('withdrawal_method_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Amount ($) *</label>
                    <input type="number" name="amount" min="10" max="{{ $wallet->balance ?? 0 }}" step="0.01" required
                           class="w-full border border-gray-300 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500"
                           placeholder="Enter amount">
                    @error('amount')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    <p class="text-sm text-gray-600 mt-1">Maximum: ${{ number_format($wallet->balance ?? 0, 2) }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-300 rounded px-4 py-3 focus:ring-2 focus:ring-orange-500"
                              placeholder="Any additional notes for this withdrawal..."></textarea>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2">Important Information:</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Withdrawal requests are processed within 3-5 business days</li>
                        <li>• You will be notified once your request is approved</li>
                        <li>• The amount will be deducted from your available balance immediately</li>
                    </ul>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-orange-500 text-white py-3 rounded hover:bg-orange-600 font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Request
                    </button>
                    <a href="{{ route('withdrawals.index') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded hover:bg-gray-300 text-center font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
