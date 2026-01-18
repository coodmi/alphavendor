@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@php
    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    // Get payment settings
    $paymentSettings = \App\Models\Setting::getPaymentSettings();
@endphp
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="shipping_first_name" required class="w-full border rounded px-3 py-2">
                                @error('shipping_first_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="shipping_last_name" required class="w-full border rounded px-3 py-2">
                                @error('shipping_last_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Street Address *</label>
                            <textarea name="shipping_address" required class="w-full border rounded px-3 py-2" rows="2"></textarea>
                            @error('shipping_address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">District *</label>
                            <select name="shipping_district" id="shipping_district" required class="w-full border rounded px-3 py-2">
                                <option value="">Select District</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chattogram">Chattogram</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Barisal">Barisal</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                            @error('shipping_district')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">City *</label>
                            <select name="shipping_city" id="shipping_city" required class="w-full border rounded px-3 py-2">
                                <option value="">Select City</option>
                            </select>
                            @error('shipping_city')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">ZIP/Postal Code *</label>
                            <input type="text" name="shipping_zip" required class="w-full border rounded px-3 py-2">
                            @error('shipping_zip')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required class="w-full border rounded px-3 py-2">
                            @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    @push('scripts')
                    <script>
                    // Bangladesh districts and cities
                    const bdDistricts = {
                        'Dhaka': ['Dhaka', 'Narayanganj', 'Savar', 'Keraniganj', 'Dhamrai'],
                        'Chattogram': ['Chattogram', 'Cox’s Bazar', 'Rangamati', 'Bandarban', 'Khagrachari'],
                        'Khulna': ['Khulna', 'Bagerhat', 'Satkhira', 'Jessore', 'Narail'],
                        'Rajshahi': ['Rajshahi', 'Natore', 'Pabna', 'Bogra', 'Naogaon'],
                        'Barisal': ['Barisal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Jhalokathi'],
                        'Sylhet': ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
                        'Rangpur': ['Rangpur', 'Dinajpur', 'Thakurgaon', 'Kurigram', 'Lalmonirhat'],
                        'Mymensingh': ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur'],
                    };

                    document.addEventListener('DOMContentLoaded', function() {
                        const districtSelect = document.getElementById('shipping_district');
                        const citySelect = document.getElementById('shipping_city');
                        districtSelect.addEventListener('change', function() {
                            const cities = bdDistricts[this.value] || [];
                            citySelect.innerHTML = '<option value="">Select City</option>';
                            cities.forEach(function(city) {
                                const opt = document.createElement('option');
                                opt.value = city;
                                opt.textContent = city;
                                citySelect.appendChild(opt);
                            });
                        });
                    });
                    </script>
                    @endpush
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Payment Method</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cod" checked class="mr-3">
                            <span class="font-medium">Cash on Delivery</span>
                        </label>
                        
                        @if($paymentSettings['bkash_enabled'] == '1' && $paymentSettings['bkash_number'])
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 border-pink-200">
                            <input type="radio" name="payment_method" value="bkash" class="mr-3">
                            <div class="flex items-center gap-2">
                                <span class="bg-pink-500 text-white px-2 py-1 rounded text-xs font-bold">bKash</span>
                                <span class="font-medium">bKash Payment</span>
                            </div>
                        </label>
                        @endif
                        
                        @if($paymentSettings['nagad_enabled'] == '1' && $paymentSettings['nagad_number'])
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 border-orange-200">
                            <input type="radio" name="payment_method" value="nagad" class="mr-3">
                            <div class="flex items-center gap-2">
                                <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs font-bold">Nagad</span>
                                <span class="font-medium">Nagad Payment</span>
                            </div>
                        </label>
                        @endif

                        @if($paymentSettings['rocket_enabled'] == '1' && $paymentSettings['rocket_number'])
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 border-purple-200">
                            <input type="radio" name="payment_method" value="rocket" class="mr-3">
                            <div class="flex items-center gap-2">
                                <span class="bg-purple-500 text-white px-2 py-1 rounded text-xs font-bold">Rocket</span>
                                <span class="font-medium">Rocket (DBBL)</span>
                            </div>
                        </label>
                        @endif
                        
                        <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3">
                            <span class="font-medium">Bank Transfer</span>
                        </label>
                    </div>

                    <!-- bKash Payment Instructions -->
                    @if($paymentSettings['bkash_enabled'] == '1' && $paymentSettings['bkash_number'])
                    <div id="bkash-instructions" class="mt-4 p-4 bg-pink-50 rounded-lg border border-pink-200" style="display: none;">
                        <h3 class="font-bold text-pink-600 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            bKash Payment Instructions
                        </h3>
                        <div class="bg-white p-3 rounded mb-3">
                            <p class="text-sm text-gray-600 mb-2">Send money to:</p>
                            <p class="text-2xl font-bold text-pink-600">{{ $paymentSettings['bkash_number'] }}</p>
                            @if($paymentSettings['bkash_name'])
                                <p class="text-sm text-gray-500">Account Name: {{ $paymentSettings['bkash_name'] }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Account Type: {{ ucfirst($paymentSettings['bkash_type']) }}</p>
                        </div>
                        <ol class="text-sm text-gray-700 space-y-1 mb-4">
                            <li>1. Open bKash App or Dial *247#</li>
                            <li>2. Select "Send Money"</li>
                            <li>3. Enter the number above</li>
                            <li>4. Enter amount: <strong class="text-pink-600">৳{{ number_format($total, 2) }}</strong></li>
                            <li>5. Enter your PIN and confirm</li>
                            <li>6. Note down the Transaction ID (TrxID)</li>
                        </ol>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Your bKash Number *</label>
                                <input type="text" name="sender_number" class="w-full border border-pink-300 rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500 bkash-input" placeholder="01XXXXXXXXX">
                                @error('sender_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Transaction ID (TrxID) *</label>
                                <input type="text" name="transaction_id" class="w-full border border-pink-300 rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500 bkash-input" placeholder="e.g., 8K7D3F2G1H">
                                @error('transaction_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Nagad Payment Instructions -->
                    @if($paymentSettings['nagad_enabled'] == '1' && $paymentSettings['nagad_number'])
                    <div id="nagad-instructions" class="mt-4 p-4 bg-orange-50 rounded-lg border border-orange-200" style="display: none;">
                        <h3 class="font-bold text-orange-600 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            Nagad Payment Instructions
                        </h3>
                        <div class="bg-white p-3 rounded mb-3">
                            <p class="text-sm text-gray-600 mb-2">Send money to:</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $paymentSettings['nagad_number'] }}</p>
                            @if($paymentSettings['nagad_name'])
                                <p class="text-sm text-gray-500">Account Name: {{ $paymentSettings['nagad_name'] }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Account Type: {{ ucfirst($paymentSettings['nagad_type']) }}</p>
                        </div>
                        <ol class="text-sm text-gray-700 space-y-1 mb-4">
                            <li>1. Open Nagad App or Dial *167#</li>
                            <li>2. Select "Send Money"</li>
                            <li>3. Enter the number above</li>
                            <li>4. Enter amount: <strong class="text-orange-600">৳{{ number_format($total, 2) }}</strong></li>
                            <li>5. Enter your PIN and confirm</li>
                            <li>6. Note down the Transaction ID</li>
                        </ol>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Your Nagad Number *</label>
                                <input type="text" name="sender_number" class="w-full border border-orange-300 rounded px-3 py-2 focus:ring-orange-500 focus:border-orange-500 nagad-input" placeholder="01XXXXXXXXX">
                                @error('sender_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Transaction ID *</label>
                                <input type="text" name="transaction_id" class="w-full border border-orange-300 rounded px-3 py-2 focus:ring-orange-500 focus:border-orange-500 nagad-input" placeholder="e.g., NAG123456789">
                                @error('transaction_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Rocket Payment Instructions -->
                    @if($paymentSettings['rocket_enabled'] == '1' && $paymentSettings['rocket_number'])
                    <div id="rocket-instructions" class="mt-4 p-4 bg-purple-50 rounded-lg border border-purple-200" style="display: none;">
                        <h3 class="font-bold text-purple-600 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            Rocket Payment Instructions
                        </h3>
                        <div class="bg-white p-3 rounded mb-3">
                            <p class="text-sm text-gray-600 mb-2">Send money to:</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $paymentSettings['rocket_number'] }}</p>
                            @if($paymentSettings['rocket_name'])
                                <p class="text-sm text-gray-500">Account Name: {{ $paymentSettings['rocket_name'] }}</p>
                            @endif
                        </div>
                        <ol class="text-sm text-gray-700 space-y-1 mb-4">
                            <li>1. Open Rocket App or Dial *322#</li>
                            <li>2. Select "Send Money"</li>
                            <li>3. Enter the number above</li>
                            <li>4. Enter amount: <strong class="text-purple-600">৳{{ number_format($total, 2) }}</strong></li>
                            <li>5. Enter your PIN and confirm</li>
                            <li>6. Note down the Transaction ID</li>
                        </ol>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Your Rocket Number *</label>
                                <input type="text" name="sender_number" class="w-full border border-purple-300 rounded px-3 py-2 focus:ring-purple-500 focus:border-purple-500 rocket-input" placeholder="01XXXXXXXXX">
                                @error('sender_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Transaction ID *</label>
                                <input type="text" name="transaction_id" class="w-full border border-purple-300 rounded px-3 py-2 focus:ring-purple-500 focus:border-purple-500 rocket-input" placeholder="e.g., RKT123456789">
                                @error('transaction_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <label class="block text-gray-700 mb-2">Order Notes (Optional)</label>
                        <textarea name="notes" class="w-full border rounded px-3 py-2" rows="3" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

                    <div class="space-y-4 mb-4 max-h-96 overflow-y-auto">
                        @foreach($cart as $item)
                            <div class="flex gap-3 pb-4 border-b">
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200">
                                    @if($item['image'])
                                        <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                             alt="{{ $item['name'] }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=200&fit=crop"
                                             alt="{{ $item['name'] }}"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2">{{ $item['name'] }}</h3>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Qty: {{ $item['quantity'] }}</span>
                                        <span class="text-gray-600">${{ number_format($item['price'], 2) }} each</span>
                                    </div>
                                    <div class="mt-1 text-right">
                                        <span class="font-bold text-orange-500">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2 mb-6">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span class="font-semibold">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span>Total</span>
                            <span class="text-orange-500">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded hover:bg-orange-600 font-semibold transition-all duration-300 hover:shadow-lg">
                        Place Order
                    </button>

                    <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-gray-600 hover:text-gray-800">
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const bkashInstructions = document.getElementById('bkash-instructions');
    const nagadInstructions = document.getElementById('nagad-instructions');
    const rocketInstructions = document.getElementById('rocket-instructions');

    function togglePaymentInstructions() {
        const selectedValue = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Hide all instructions first
        if (bkashInstructions) bkashInstructions.style.display = 'none';
        if (nagadInstructions) nagadInstructions.style.display = 'none';
        if (rocketInstructions) rocketInstructions.style.display = 'none';

        // Clear input values when switching
        if (selectedValue !== 'bkash' && selectedValue !== 'nagad' && selectedValue !== 'rocket') {
            document.querySelectorAll('input[name="sender_number"]').forEach(input => input.value = '');
            document.querySelectorAll('input[name="transaction_id"]').forEach(input => input.value = '');
        }

        // Show relevant instructions
        if (selectedValue === 'bkash' && bkashInstructions) {
            bkashInstructions.style.display = 'block';
        } else if (selectedValue === 'nagad' && nagadInstructions) {
            nagadInstructions.style.display = 'block';
        } else if (selectedValue === 'rocket' && rocketInstructions) {
            rocketInstructions.style.display = 'block';
        }
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', togglePaymentInstructions);
    });
});
</script>
@endsection
