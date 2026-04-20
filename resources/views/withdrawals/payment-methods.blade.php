@extends('layouts.dashboard')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods')

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
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Add New Payment Method -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Add Payment Method</h2>
        
        <form action="{{ route('withdrawals.payment-methods.store') }}" method="POST" id="paymentMethodForm">
            @csrf
            
            <!-- Payment Type Selection -->
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Select Payment Method <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="bank" class="hidden payment-type-radio" checked>
                        <div class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-all payment-type-option">
                            <i class="fas fa-university text-4xl text-blue-500 mb-3"></i>
                            <p class="font-semibold text-white">Bank Transfer</p>
                            <p class="text-xs text-gray-200 mt-1">Direct bank account</p>
                        </div>
                    </label>

                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="bkash" class="hidden payment-type-radio">
                        <div class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-pink-500 transition-all payment-type-option">
                            <i class="fas fa-mobile-alt text-4xl text-pink-500 mb-3"></i>
                            <p class="font-semibold text-white">bKash</p>
                            <p class="text-xs text-gray-200 mt-1">Mobile wallet</p>
                        </div>
                    </label>

                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="nagad" class="hidden payment-type-radio">
                        <div class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-teal-600 transition-all payment-type-option">
                            <i class="fas fa-mobile-alt text-4xl text-teal-600 mb-3"></i>
                            <p class="font-semibold text-white">Nagad</p>
                            <p class="text-xs text-gray-200 mt-1">Mobile wallet</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Common Field -->
            <div class="mb-4">
                <label class="block text-white font-semibold mb-2">Account Holder Name <span class="text-red-500">*</span></label>
                <input type="text" name="account_name" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                       placeholder="Enter account holder name">
            </div>

            <!-- Bank Fields -->
            <div id="bank_fields" class="payment-fields">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-white font-semibold mb-2">Bank Name <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_name" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                               placeholder="e.g., Dutch-Bangla Bank">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Branch Name</label>
                        <input type="text" name="branch_name" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                               placeholder="e.g., Gulshan Branch">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-white font-semibold mb-2">Account Number <span class="text-red-500">*</span></label>
                        <input type="text" name="account_number" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                               placeholder="Enter account number">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Routing Number</label>
                        <input type="text" name="routing_number" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                               placeholder="9-digit routing number">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">SWIFT Code (for international)</label>
                    <input type="text" name="swift_code" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                           placeholder="Enter SWIFT code">
                </div>
            </div>

            <!-- bKash Fields -->
            <div id="bkash_fields" class="payment-fields hidden">
                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">bKash Number <span class="text-red-500">*</span></label>
                    <input type="text" name="bkash_number" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                           placeholder="01XXXXXXXXX">
                    <p class="text-xs text-gray-200 mt-1">Enter your bKash registered mobile number</p>
                </div>
            </div>

            <!-- Nagad Fields -->
            <div id="nagad_fields" class="payment-fields hidden">
                <div class="mb-4">
                    <label class="block text-white font-semibold mb-2">Nagad Number <span class="text-red-500">*</span></label>
                    <input type="text" name="nagad_number" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                           placeholder="01XXXXXXXXX">
                    <p class="text-xs text-gray-200 mt-1">Enter your Nagad registered mobile number</p>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mb-4">
                <label class="block text-white font-semibold mb-2">Additional Details (Optional)</label>
                <textarea name="additional_details" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-600"
                          placeholder="Any additional information..."></textarea>
            </div>

            <!-- Set as Default -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" class="w-5 h-5 text-teal-600 rounded focus:ring-2 focus:ring-teal-600">
                    <span class="ml-3 text-white font-semibold">Set as default payment method</span>
                </label>
            </div>

            <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-lg hover:bg-teal-700 font-semibold shadow-md">
                <i class="fas fa-plus mr-2"></i>Add Payment Method
            </button>
        </form>
    </div>

    <!-- Saved Payment Methods -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-white">Saved Payment Methods</h2>
        </div>

        @if($methods->count() > 0)
            <div class="divide-y">
                @foreach($methods as $method)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4 flex-1">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @if($method->type == 'bank')
                                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-university text-2xl text-blue-500"></i>
                                        </div>
                                    @elseif($method->type == 'bkash')
                                        <div class="w-16 h-16 bg-pink-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-2xl text-pink-500"></i>
                                        </div>
                                    @elseif($method->type == 'nagad')
                                        <div class="w-16 h-16 bg-teal-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-2xl text-teal-600"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-white">
                                            @if($method->type == 'bank')
                                                Bank Transfer
                                            @elseif($method->type == 'bkash')
                                                bKash
                                            @elseif($method->type == 'nagad')
                                                Nagad
                                            @endif
                                        </h3>
                                        @if($method->is_default)
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Default
                                            </span>
                                        @endif
                                    </div>

                                    <div class="space-y-1 text-sm text-gray-100">
                                        <p><span class="font-semibold">Account Name:</span> {{ $method->account_name }}</p>
                                        
                                        @if($method->type == 'bank')
                                            @if($method->bank_name)
                                                <p><span class="font-semibold">Bank:</span> {{ $method->bank_name }}</p>
                                            @endif
                                            @if($method->branch_name)
                                                <p><span class="font-semibold">Branch:</span> {{ $method->branch_name }}</p>
                                            @endif
                                            @if($method->account_number)
                                                <p><span class="font-semibold">Account:</span> {{ $method->account_number }}</p>
                                            @endif
                                        @elseif($method->type == 'bkash')
                                            @if($method->bkash_number)
                                                <p><span class="font-semibold">Number:</span> {{ $method->bkash_number }}</p>
                                            @endif
                                        @elseif($method->type == 'nagad')
                                            @if($method->nagad_number)
                                                <p><span class="font-semibold">Number:</span> {{ $method->nagad_number }}</p>
                                            @endif
                                        @endif

                                        @if($method->additional_details)
                                            <p class="text-gray-200 italic">{{ $method->additional_details }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div>
                                <form action="{{ route('withdrawals.payment-methods.delete', $method->id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this payment method?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-200">
                <i class="fas fa-credit-card text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-semibold">No payment methods added yet</p>
                <p class="text-sm mt-2">Add a payment method to start receiving withdrawals</p>
            </div>
        @endif
    </div>
</div>

<script>
// Payment type selection
document.querySelectorAll('.payment-type-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update visual selection
        document.querySelectorAll('.payment-type-option').forEach(option => {
            option.classList.remove('border-blue-500', 'border-pink-500', 'border-teal-600', 'bg-blue-50', 'bg-pink-50', 'bg-teal-50');
            option.classList.add('border-gray-300');
        });
        
        const selectedOption = this.parentElement.querySelector('.payment-type-option');
        selectedOption.classList.remove('border-gray-300');
        
        if (this.value === 'bank') {
            selectedOption.classList.add('border-blue-500', 'bg-blue-50');
        } else if (this.value === 'bkash') {
            selectedOption.classList.add('border-pink-500', 'bg-pink-50');
        } else if (this.value === 'nagad') {
            selectedOption.classList.add('border-teal-600', 'bg-teal-50');
        }
        
        // Show/hide relevant fields
        document.querySelectorAll('.payment-fields').forEach(field => {
            field.classList.add('hidden');
        });
        
        document.getElementById(this.value + '_fields').classList.remove('hidden');
    });
});

// Trigger initial selection
document.querySelector('.payment-type-radio:checked').dispatchEvent(new Event('change'));
</script>
@endsection
