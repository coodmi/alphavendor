@extends('layouts.dashboard')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add New Payment Method -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Add Payment Method</h2>
        <form action="{{ route('withdrawals.payment-methods.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Type *</label>
                    <select name="type" id="payment_type" required
                            class="w-full border border-gray-300 rounded px-3 py-2"
                            onchange="togglePaymentFields()">
                        <option value="bank">Bank Transfer</option>
                        <option value="paypal">PayPal</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Bank Fields -->
            <div id="bank_fields" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Account Name *</label>
                        <input type="text" name="account_name" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Account Number *</label>
                        <input type="text" name="account_number" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Routing Number</label>
                        <input type="text" name="routing_number" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">SWIFT Code</label>
                        <input type="text" name="swift_code" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- PayPal Fields -->
            <div id="paypal_fields" style="display:none;">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">PayPal Email *</label>
                    <input type="email" name="paypal_email" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Additional Details</label>
                <textarea name="additional_details" rows="2" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_default" value="1" class="mr-2">
                    <span class="text-gray-700">Set as default payment method</span>
                </label>
            </div>

            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600">
                <i class="fas fa-plus mr-2"></i>Add Payment Method
            </button>
        </form>
    </div>

    <!-- Saved Payment Methods -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">Saved Payment Methods</h2>
        </div>

        <div class="p-6">
            @forelse($methods as $method)
                <div class="border rounded-lg p-4 mb-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-block px-3 py-1 rounded bg-blue-100 text-blue-800 text-sm font-semibold">
                                    {{ ucfirst($method->type) }}
                                </span>
                                @if($method->is_default)
                                    <span class="inline-block px-3 py-1 rounded bg-green-100 text-green-800 text-sm font-semibold">
                                        Default
                                    </span>
                                @endif
                            </div>

                            @if($method->type === 'bank')
                                <p class="text-gray-700"><strong>Account Name:</strong> {{ $method->account_name }}</p>
                                <p class="text-gray-700"><strong>Account Number:</strong> ****{{ substr($method->account_number, -4) }}</p>
                                @if($method->bank_name)
                                    <p class="text-gray-700"><strong>Bank:</strong> {{ $method->bank_name }}</p>
                                @endif
                            @elseif($method->type === 'paypal')
                                <p class="text-gray-700"><strong>Email:</strong> {{ $method->paypal_email }}</p>
                            @endif

                            @if($method->additional_details)
                                <p class="text-gray-600 text-sm mt-2">{{ $method->additional_details }}</p>
                            @endif
                        </div>

                        <form action="{{ route('withdrawals.payment-methods.delete', $method->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-credit-card text-4xl mb-2 opacity-50"></i>
                    <p>No payment methods added yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function togglePaymentFields() {
    const type = document.getElementById('payment_type').value;
    document.getElementById('bank_fields').style.display = type === 'bank' ? 'block' : 'none';
    document.getElementById('paypal_fields').style.display = type === 'paypal' ? 'block' : 'none';
}
</script>
@endsection
