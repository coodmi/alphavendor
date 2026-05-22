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

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-300 rounded-lg">
            <p class="font-semibold text-red-700 mb-2">অর্ডার ফর্মে তথ্য ঠিক করুন:</p>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-300 rounded-lg flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-700">অর্ডার সম্পন্ন হয়নি</p>
                <p class="text-red-600 text-sm mt-1">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Advance Payment Required Warning --}}
    @if(!empty($hasAdvanceRequired) && $hasAdvanceRequired)
    <div class="mb-6 p-5 bg-amber-50 border-2 border-amber-400 rounded-xl">
        <div class="flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-amber-500 text-2xl mt-0.5"></i>
            <div class="flex-1">
                <h3 class="font-bold text-amber-800 text-lg">অ্যাডভান্স পেমেন্ট প্রয়োজন</h3>
                <p class="text-amber-700 text-sm mt-1">
                    আপনার কার্টে Wholesaler / Importer পণ্য রয়েছে। এই পণ্যগুলো অর্ডার করতে হলে আগে <strong>অ্যাডভান্স পেমেন্ট</strong> করতে হবে।
                    অ্যাডভান্স পেমেন্ট ছাড়া অর্ডার সম্পন্ন হবে না।
                </p>
                <div class="mt-3 space-y-2">
                    @foreach($advanceRequiredItems as $item)
                    <div class="flex items-center justify-between bg-white border border-amber-200 rounded-lg px-4 py-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-box text-amber-500"></i>
                            <span class="text-sm font-medium text-gray-800">{{ $item['name'] }}</span>
                        </div>
                        <a href="{{ route('product.show', $item['id']) }}"
                           class="text-xs bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-money-check-alt mr-1"></i> Pay Advance
                        </a>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-amber-600 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    অ্যাডভান্স পেমেন্ট অনুমোদিত হলে আপনি অর্ডার দিতে পারবেন। অ্যাডভান্স পেমেন্টের পরিমাণ: মোট অর্ডারের <strong>{{ $advanceSettings->advance_percentage }}%</strong>।
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Information</h2>

                    @if($addresses->count() > 0)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i> Select Saved Address
                            </label>
                            <select id="savedAddressSelect" class="w-full border rounded px-3 py-2 mb-3" style="background-color: white; color: #333;">
                                <option value="">-- Select an address or enter new below --</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}" 
                                            data-first-name="{{ $address->first_name }}"
                                            data-last-name="{{ $address->last_name }}"
                                            data-address="{{ $address->address }}"
                                            data-city="{{ $address->city }}"
                                            data-state="{{ $address->state }}"
                                            data-district="{{ $address->district }}"
                                            data-phone="{{ $address->phone }}"
                                            {{ $address->is_default ? 'selected' : '' }}>
                                        {{ $address->label ? $address->label . ' - ' : '' }}{{ $address->first_name }} {{ $address->last_name }} ({{ $address->district }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle"></i> Select a saved address to auto-fill the form below
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="shipping_first_name" id="first_name" required class="w-full border rounded px-3 py-2">
                                @error('shipping_first_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="shipping_last_name" id="last_name" required class="w-full border rounded px-3 py-2">
                                @error('shipping_last_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Street Address *</label>
                            <textarea name="shipping_address" id="address" required class="w-full border rounded px-3 py-2" rows="2"></textarea>
                            @error('shipping_address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Division *</label>
                            <select name="shipping_state" id="shipping_division" required class="w-full border rounded px-3 py-2" style="background-color: white; color: #333;">
                                <option value="">Select Division</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chattogram">Chattogram</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Barisal">Barisal</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                            @error('shipping_state')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">District *</label>
                            <select name="shipping_district" id="shipping_district" required class="w-full border rounded px-3 py-2" style="background-color: white; color: #333;">
                                <option value="">Select Division First</option>
                            </select>
                            @error('shipping_district')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" required class="w-full border rounded px-3 py-2">
                            @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        <!-- Hidden fields for required data -->
                        <input type="hidden" name="shipping_country" value="Bangladesh">
                        <input type="hidden" name="shipping_city" id="shipping_city_hidden" value="">
                        {{-- Note: shipping_district is submitted via the visible <select> above; no duplicate hidden field --}}
                        <input type="hidden" name="delivery_charge" id="delivery_charge" value="0">
                    @push('scripts')
                    <script>
                    // Bangladesh divisions and their districts
                    const divisionDistricts = {
                        'Dhaka': ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail'],
                        'Chattogram': ['Bandarban', 'Brahmanbaria', 'Chandpur', 'Chattogram', 'Comilla', "Cox's Bazar", 'Feni', 'Khagrachari', 'Lakshmipur', 'Noakhali', 'Rangamati'],
                        'Khulna': ['Bagerhat', 'Chuadanga', 'Jessore', 'Jhenaidah', 'Khulna', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira'],
                        'Rajshahi': ['Bogra', 'Chapainawabganj', 'Joypurhat', 'Naogaon', 'Natore', 'Pabna', 'Rajshahi', 'Sirajganj'],
                        'Barisal': ['Barguna', 'Barisal', 'Bhola', 'Jhalokathi', 'Patuakhali', 'Pirojpur'],
                        'Sylhet': ['Habiganj', 'Moulvibazar', 'Sunamganj', 'Sylhet'],
                        'Rangpur': ['Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Rangpur', 'Thakurgaon'],
                        'Mymensingh': ['Jamalpur', 'Mymensingh', 'Netrokona', 'Sherpur']
                    };

                    const cartShipping = @json(collect($cart)->map(fn ($item) => [
                        'inside' => $item['shipping_charge_inside_dhaka'] ?? null,
                        'outside' => $item['shipping_charge_outside_dhaka'] ?? null,
                    ])->values());
                    const subtotalAmount = {{ $total }};

                    function setDistrictValue(districtSelect, district) {
                        if (!district) return false;
                        const normalized = district.trim().toLowerCase();
                        for (const opt of districtSelect.options) {
                            if (opt.value && opt.value.toLowerCase() === normalized) {
                                districtSelect.value = opt.value;
                                return true;
                            }
                        }
                        const opt = document.createElement('option');
                        opt.value = district;
                        opt.textContent = district;
                        districtSelect.appendChild(opt);
                        districtSelect.value = district;
                        return true;
                    }

                    function calcShippingCharge(district) {
                        const inside = String(district || '').trim().toLowerCase() === 'dhaka';
                        let charge = 0;
                        let hasCharge = false;
                        cartShipping.forEach(item => {
                            const raw = inside ? item.inside : item.outside;
                            if (raw !== null && raw !== '' && !isNaN(parseFloat(raw))) {
                                hasCharge = true;
                                charge = Math.max(charge, parseFloat(raw));
                            }
                        });
                        return { charge, hasCharge, inside };
                    }

                    function updateShippingSummary() {
                        const district = document.getElementById('shipping_district')?.value || '';
                        const deliveryInput = document.getElementById('delivery_charge');
                        const shippingDisplay = document.getElementById('shippingChargeDisplay');
                        const zoneLabel = document.getElementById('shippingZoneLabel');
                        const grandTotal = document.getElementById('orderGrandTotal');
                        const { charge, hasCharge, inside } = calcShippingCharge(district);

                        if (!district) {
                            if (shippingDisplay) shippingDisplay.textContent = 'Select district';
                            if (zoneLabel) zoneLabel.textContent = '';
                            if (deliveryInput) deliveryInput.value = '0';
                            if (grandTotal) grandTotal.textContent = '৳' + subtotalAmount.toFixed(2);
                            return;
                        }

                        if (zoneLabel) {
                            zoneLabel.textContent = inside ? '(Inside Dhaka)' : '(Outside Dhaka)';
                        }

                        if (!hasCharge) {
                            if (shippingDisplay) shippingDisplay.textContent = '—';
                            if (deliveryInput) deliveryInput.value = '0';
                        } else if (charge <= 0) {
                            if (shippingDisplay) shippingDisplay.textContent = 'Free';
                            if (deliveryInput) deliveryInput.value = '0';
                        } else {
                            if (shippingDisplay) shippingDisplay.textContent = '৳' + charge.toFixed(2);
                            if (deliveryInput) deliveryInput.value = charge.toFixed(2);
                        }

                        const shipping = hasCharge ? Math.max(0, charge) : 0;
                        if (grandTotal) grandTotal.textContent = '৳' + (subtotalAmount + shipping).toFixed(2);
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        const divisionSelect = document.getElementById('shipping_division');
                        const districtSelect = document.getElementById('shipping_district');
                        const cityHidden = document.getElementById('shipping_city_hidden');
                        const savedAddressSelect = document.getElementById('savedAddressSelect');

                        function applySavedAddressOption(selectedOption) {
                            const firstName = selectedOption.getAttribute('data-first-name');
                            const lastName = selectedOption.getAttribute('data-last-name');
                            const address = selectedOption.getAttribute('data-address');
                            const state = selectedOption.getAttribute('data-state');
                            const district = selectedOption.getAttribute('data-district');
                            const phone = selectedOption.getAttribute('data-phone');

                            document.getElementById('first_name').value = firstName || '';
                            document.getElementById('last_name').value = lastName || '';
                            document.getElementById('address').value = address || '';
                            document.getElementById('phone').value = phone || '';

                            if (state) {
                                divisionSelect.value = state;
                                divisionSelect.dispatchEvent(new Event('change'));
                                setTimeout(function() {
                                    if (district && setDistrictValue(districtSelect, district)) {
                                        districtSelect.dispatchEvent(new Event('change'));
                                    }
                                    updateShippingSummary();
                                }, 150);
                            } else {
                                updateShippingSummary();
                            }
                        }

                        // Handle saved address selection
                        if (savedAddressSelect) {
                            savedAddressSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                
                                if (this.value) {
                                    applySavedAddressOption(selectedOption);
                                } else {
                                    // Clear form if "Select an address" is chosen
                                    document.getElementById('first_name').value = '';
                                    document.getElementById('last_name').value = '';
                                    document.getElementById('address').value = '';
                                    document.getElementById('phone').value = '';
                                    divisionSelect.value = '';
                                    districtSelect.innerHTML = '<option value="">Select Division First</option>';
                                    districtSelect.style.opacity = '0.5';
                                    districtSelect.style.pointerEvents = 'none';
                                    cityHidden.value = '';
                                    updateShippingSummary();
                                }
                            });
                            
                            // Auto-fill if default address is selected on page load
                            if (savedAddressSelect.value) {
                                applySavedAddressOption(savedAddressSelect.options[savedAddressSelect.selectedIndex]);
                            }
                        }

                        divisionSelect.addEventListener('change', function() {
                            const selectedDivision = this.value;
                            const districts = divisionDistricts[selectedDivision] || [];
                            
                            // Clear existing options
                            districtSelect.innerHTML = '<option value="">Select District</option>';
                            
                            // Add districts for selected division
                            districts.forEach(function(district) {
                                const option = document.createElement('option');
                                option.value = district;
                                option.textContent = district;
                                districtSelect.appendChild(option);
                            });
                            
                            // Enable/disable district select based on division selection
                            if (selectedDivision) {
                                districtSelect.disabled = false;
                                districtSelect.style.opacity = '1';
                            } else {
                                // Don't truly disable — use pointer-events so value still submits
                                districtSelect.innerHTML = '<option value="">Select Division First</option>';
                                districtSelect.style.opacity = '0.5';
                                districtSelect.style.pointerEvents = 'none';
                            }
                        });

                        // Ensure district is never truly disabled on form submit (disabled fields don't submit)
                        const checkoutForm = districtSelect.closest('form');
                        if (checkoutForm) {
                            checkoutForm.addEventListener('submit', function() {
                                districtSelect.disabled = false;
                            });
                        }

                        // Update hidden city field when district changes
                        districtSelect.addEventListener('change', function() {
                            cityHidden.value = this.value;
                            districtSelect.disabled = false;
                            updateShippingSummary();
                        });

                        updateShippingSummary();
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
                                <span class="bg-teal-600 text-white px-2 py-1 rounded text-xs font-bold">Nagad</span>
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
                    <div id="nagad-instructions" class="mt-4 p-4 bg-teal-50 rounded-lg border border-orange-200" style="display: none;">
                        <h3 class="font-bold text-teal-700 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            Nagad Payment Instructions
                        </h3>
                        <div class="bg-white p-3 rounded mb-3">
                            <p class="text-sm text-gray-600 mb-2">Send money to:</p>
                            <p class="text-2xl font-bold text-teal-700">{{ $paymentSettings['nagad_number'] }}</p>
                            @if($paymentSettings['nagad_name'])
                                <p class="text-sm text-gray-500">Account Name: {{ $paymentSettings['nagad_name'] }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Account Type: {{ ucfirst($paymentSettings['nagad_type']) }}</p>
                        </div>
                        <ol class="text-sm text-gray-700 space-y-1 mb-4">
                            <li>1. Open Nagad App or Dial *167#</li>
                            <li>2. Select "Send Money"</li>
                            <li>3. Enter the number above</li>
                            <li>4. Enter amount: <strong class="text-teal-700">৳{{ number_format($total, 2) }}</strong></li>
                            <li>5. Enter your PIN and confirm</li>
                            <li>6. Note down the Transaction ID</li>
                        </ol>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Your Nagad Number *</label>
                                <input type="text" name="sender_number" class="w-full border border-teal-400 rounded px-3 py-2 focus:ring-teal-600 focus:border-teal-600 nagad-input" placeholder="01XXXXXXXXX">
                                @error('sender_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Transaction ID *</label>
                                <input type="text" name="transaction_id" class="w-full border border-teal-400 rounded px-3 py-2 focus:ring-teal-600 focus:border-teal-600 nagad-input" placeholder="e.g., NAG123456789">
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
                                        <span class="text-gray-600"> {{ currency($item['price']) }} each</span>
                                    </div>
                                    <div class="mt-1 text-right">
                                        <span class="font-bold text-teal-600"> {{ currency($item['price'] * $item['quantity']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2 mb-6">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold"> {{ currency($total) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping <span id="shippingZoneLabel" class="text-gray-400 text-xs"></span></span>
                            <span class="font-semibold" id="shippingChargeDisplay">Select district</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span>Total</span>
                            <span class="text-teal-600" id="orderGrandTotal"> {{ currency($total) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded hover:bg-teal-700 font-semibold transition-all duration-300 hover:shadow-lg
                        @if(!empty($hasAdvanceRequired) && $hasAdvanceRequired) opacity-50 cursor-not-allowed @endif"
                        @if(!empty($hasAdvanceRequired) && $hasAdvanceRequired) disabled title="অ্যাডভান্স পেমেন্ট করুন তারপর অর্ডার দিন" @endif>
                        @if(!empty($hasAdvanceRequired) && $hasAdvanceRequired)
                            <i class="fas fa-lock mr-2"></i> অ্যাডভান্স পেমেন্ট প্রয়োজন
                        @else
                            Place Order
                        @endif
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
