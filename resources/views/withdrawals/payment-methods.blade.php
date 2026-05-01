@extends('layouts.dashboard')

@section('title', 'Payment Methods')
@section('page-title', 'Payment Methods')

@section('sidebar-menu')
    @php $userRole = auth()->user()->role; @endphp
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
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Add New Payment Method -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-plus-circle text-teal-600 mr-2"></i>Add Payment Method
        </h2>

        <form action="{{ route('withdrawals.payment-methods.store') }}" method="POST" id="paymentMethodForm">
            @csrf

            <!-- Step 1: Choose Method Type -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-3">Select Payment Method <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    {{-- bKash --}}
                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="bkash" class="hidden payment-type-radio" checked>
                        <div class="border-2 border-pink-400 bg-pink-50 rounded-xl p-5 text-center transition-all payment-type-option" data-color="pink">
                            <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-content-center mx-auto mb-3 flex items-center justify-center">
                                <span class="text-white font-bold text-lg">b</span>
                            </div>
                            <p class="font-bold text-gray-800">bKash</p>
                            <p class="text-xs text-gray-500 mt-1">Mobile Banking</p>
                        </div>
                    </label>

                    {{-- Nagad --}}
                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="nagad" class="hidden payment-type-radio">
                        <div class="border-2 border-gray-200 rounded-xl p-5 text-center transition-all payment-type-option" data-color="orange">
                            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-white font-bold text-lg">N</span>
                            </div>
                            <p class="font-bold text-gray-800">Nagad</p>
                            <p class="text-xs text-gray-500 mt-1">Mobile Banking</p>
                        </div>
                    </label>

                    {{-- Rocket --}}
                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="rocket" class="hidden payment-type-radio">
                        <div class="border-2 border-gray-200 rounded-xl p-5 text-center transition-all payment-type-option" data-color="purple">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-rocket text-white text-lg"></i>
                            </div>
                            <p class="font-bold text-gray-800">Rocket</p>
                            <p class="text-xs text-gray-500 mt-1">Mobile Banking</p>
                        </div>
                    </label>

                    {{-- Bank --}}
                    <label class="payment-type-card cursor-pointer">
                        <input type="radio" name="type" value="bank" class="hidden payment-type-radio">
                        <div class="border-2 border-gray-200 rounded-xl p-5 text-center transition-all payment-type-option" data-color="blue">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-university text-white text-lg"></i>
                            </div>
                            <p class="font-bold text-gray-800">Bank</p>
                            <p class="text-xs text-gray-500 mt-1">Bank Transfer</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Step 2: Account Type (Personal / Merchant) — only for mobile wallets -->
            <div id="account_type_section" class="mb-6">
                <label class="block text-gray-700 font-semibold mb-3">Account Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4 max-w-md">
                    <label class="cursor-pointer">
                        <input type="radio" name="account_type" value="personal" class="hidden account-type-radio" checked>
                        <div class="border-2 border-teal-500 bg-teal-50 rounded-lg p-4 text-center account-type-option">
                            <i class="fas fa-user text-2xl text-teal-600 mb-2"></i>
                            <p class="font-semibold text-gray-800">Personal</p>
                            <p class="text-xs text-gray-500 mt-1">Manual transfer</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="account_type" value="merchant" class="hidden account-type-radio">
                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center account-type-option">
                            <i class="fas fa-store text-2xl text-gray-400 mb-2"></i>
                            <p class="font-semibold text-gray-800">Merchant</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Auto-pay
                                <span class="block text-xs text-orange-500 font-semibold mt-1">(Coming Soon)</span>
                            </p>
                        </div>
                    </label>
                </div>
                <p id="merchant_note" class="hidden mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Merchant account auto-pay is currently under development. Your method will be saved but payments will be processed manually until this feature is enabled.
                </p>
            </div>

            <!-- Account Holder Name -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Account Holder Name <span class="text-red-500">*</span></label>
                <input type="text" name="account_name" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                       placeholder="Enter account holder name">
            </div>

            <!-- bKash Fields -->
            <div id="bkash_fields" class="payment-fields">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">bKash Number <span class="text-red-500">*</span></label>
                    <input type="text" name="bkash_number"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                           placeholder="01XXXXXXXXX">
                </div>
            </div>

            <!-- Nagad Fields -->
            <div id="nagad_fields" class="payment-fields hidden">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nagad Number <span class="text-red-500">*</span></label>
                    <input type="text" name="nagad_number"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                           placeholder="01XXXXXXXXX">
                </div>
            </div>

            <!-- Rocket Fields -->
            <div id="rocket_fields" class="payment-fields hidden">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Rocket Number <span class="text-red-500">*</span></label>
                    <input type="text" name="rocket_number"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                           placeholder="01XXXXXXXXX">
                </div>
            </div>

            <!-- Bank Fields -->
            <div id="bank_fields" class="payment-fields hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Bank Name <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_name"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                               placeholder="e.g., Dutch-Bangla Bank">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Branch Name</label>
                        <input type="text" name="branch_name"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                               placeholder="e.g., Gulshan Branch">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Account Number <span class="text-red-500">*</span></label>
                        <input type="text" name="account_number"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                               placeholder="Enter account number">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Routing Number</label>
                        <input type="text" name="routing_number"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                               placeholder="9-digit routing number">
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Additional Details <span class="text-gray-400 font-normal">(Optional)</span></label>
                <textarea name="additional_details" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-teal-600"
                          placeholder="Any additional information..."></textarea>
            </div>

            <!-- Set as Default -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer gap-3">
                    <input type="checkbox" name="is_default" value="1" class="w-5 h-5 text-teal-600 rounded">
                    <span class="text-gray-700 font-semibold">Set as default payment method</span>
                </label>
            </div>

            <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-lg hover:bg-teal-700 font-semibold shadow-md transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Payment Method
            </button>
        </form>
    </div>

    <!-- Saved Payment Methods -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Saved Payment Methods</h2>
        </div>

        @if($methods->count() > 0)
            <div class="divide-y">
                @foreach($methods as $method)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @if($method->type === 'bkash')
                                    <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center">
                                        <span class="text-pink-600 font-bold text-2xl">b</span>
                                    </div>
                                @elseif($method->type === 'nagad')
                                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                                        <span class="text-orange-600 font-bold text-2xl">N</span>
                                    </div>
                                @elseif($method->type === 'rocket')
                                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-rocket text-purple-600 text-xl"></i>
                                    </div>
                                @else
                                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-university text-blue-600 text-xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <h3 class="text-lg font-bold text-gray-800">
                                        {{ ucfirst($method->type) }}
                                        @if($method->type !== 'bank')
                                            <span class="text-sm font-normal text-gray-500">
                                                — {{ $method->account_type === 'merchant' ? 'Merchant Account' : 'Personal Account' }}
                                            </span>
                                        @endif
                                    </h3>
                                    @if($method->account_type === 'merchant')
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-700">
                                            <i class="fas fa-store mr-1"></i>Merchant
                                        </span>
                                    @endif
                                    @if($method->is_default)
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                            <i class="fas fa-check mr-1"></i>Default
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-600 space-y-0.5">
                                    <p><span class="font-semibold">Name:</span> {{ $method->account_name }}</p>
                                    @if($method->type === 'bkash' && $method->bkash_number)
                                        <p><span class="font-semibold">Number:</span> {{ $method->bkash_number }}</p>
                                    @elseif($method->type === 'nagad' && $method->nagad_number)
                                        <p><span class="font-semibold">Number:</span> {{ $method->nagad_number }}</p>
                                    @elseif($method->type === 'rocket' && $method->rocket_number)
                                        <p><span class="font-semibold">Number:</span> {{ $method->rocket_number }}</p>
                                    @elseif($method->type === 'bank')
                                        @if($method->bank_name)<p><span class="font-semibold">Bank:</span> {{ $method->bank_name }}</p>@endif
                                        @if($method->account_number)<p><span class="font-semibold">Account:</span> {{ $method->account_number }}</p>@endif
                                        @if($method->branch_name)<p><span class="font-semibold">Branch:</span> {{ $method->branch_name }}</p>@endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Delete -->
                        <form action="{{ route('withdrawals.payment-methods.delete', $method->id) }}" method="POST"
                              onsubmit="return confirm('Delete this payment method?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-credit-card text-5xl mb-4 text-gray-300"></i>
                <p class="text-lg font-semibold">No payment methods added yet</p>
                <p class="text-sm mt-2">Add a payment method to start receiving withdrawals</p>
            </div>
        @endif
    </div>
</div>

<script>
const colorMap = {
    bkash:  { border: 'border-pink-400',   bg: 'bg-pink-50'   },
    nagad:  { border: 'border-orange-400', bg: 'bg-orange-50' },
    rocket: { border: 'border-purple-500', bg: 'bg-purple-50' },
    bank:   { border: 'border-blue-500',   bg: 'bg-blue-50'   },
};

function updatePaymentType(val) {
    // Reset all cards
    document.querySelectorAll('.payment-type-option').forEach(opt => {
        opt.className = 'border-2 border-gray-200 rounded-xl p-5 text-center transition-all payment-type-option';
    });
    // Highlight selected
    const selected = document.querySelector(`.payment-type-radio[value="${val}"]`)
        ?.parentElement?.querySelector('.payment-type-option');
    if (selected && colorMap[val]) {
        selected.classList.add(colorMap[val].border, colorMap[val].bg);
    }

    // Show/hide fields
    document.querySelectorAll('.payment-fields').forEach(f => f.classList.add('hidden'));
    const fields = document.getElementById(val + '_fields');
    if (fields) fields.classList.remove('hidden');

    // Show account type only for mobile wallets
    const accountSection = document.getElementById('account_type_section');
    accountSection.style.display = val === 'bank' ? 'none' : 'block';
    if (val === 'bank') {
        document.querySelector('.account-type-radio[value="personal"]').checked = true;
    }
}

document.querySelectorAll('.payment-type-radio').forEach(r => {
    r.addEventListener('change', () => updatePaymentType(r.value));
});

// Account type toggle
document.querySelectorAll('.account-type-radio').forEach(r => {
    r.addEventListener('change', function() {
        document.querySelectorAll('.account-type-option').forEach(o => {
            o.className = 'border-2 border-gray-200 rounded-lg p-4 text-center account-type-option';
        });
        this.parentElement.querySelector('.account-type-option')
            .classList.replace('border-gray-200', 'border-teal-500');
        this.parentElement.querySelector('.account-type-option')
            .classList.add('bg-teal-50');

        document.getElementById('merchant_note').classList.toggle('hidden', this.value !== 'merchant');
    });
});

// Init
updatePaymentType('bkash');
</script>
@endsection
