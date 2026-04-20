@extends('layouts.dashboard')

@section('title', 'Payment Settings')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Payment Settings</h1>
            <p class="text-gray-600">Configure your bKash, Nagad, and Rocket payment numbers</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.payment-settings.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- bKash Settings -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-pink-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-pink-500 text-white p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">bKash</h2>
                                <p class="text-sm text-gray-500">Personal/Agent/Merchant Account</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="bkash_enabled" value="1" {{ $settings['bkash_enabled'] == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Enabled</span>
                        </label>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">bKash Number *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input type="text" name="bkash_number" value="{{ $settings['bkash_number'] }}" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" 
                                   placeholder="01XXXXXXXXX">
                        </div>
                        @error('bkash_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                        <input type="text" name="bkash_name" value="{{ $settings['bkash_name'] }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" 
                               placeholder="e.g., Alpha Vendor">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                        <select name="bkash_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            <option value="personal" {{ $settings['bkash_type'] == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="agent" {{ $settings['bkash_type'] == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="merchant" {{ $settings['bkash_type'] == 'merchant' ? 'selected' : '' }}>Merchant</option>
                        </select>
                    </div>

                    @if($settings['bkash_number'])
                        <div class="bg-pink-50 border border-pink-200 rounded-lg p-3">
                            <p class="text-sm text-gray-600">Customers will see:</p>
                            <p class="text-lg font-bold text-pink-600">{{ $settings['bkash_number'] }}</p>
                            @if($settings['bkash_name'])
                                <p class="text-sm text-gray-500">{{ $settings['bkash_name'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Nagad Settings -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-teal-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-teal-600 text-white p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Nagad</h2>
                                <p class="text-sm text-gray-500">Personal/Agent/Merchant Account</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="nagad_enabled" value="1" {{ $settings['nagad_enabled'] == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-400 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Enabled</span>
                        </label>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nagad Number *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input type="text" name="nagad_number" value="{{ $settings['nagad_number'] }}" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-600 focus:border-teal-600" 
                                   placeholder="01XXXXXXXXX">
                        </div>
                        @error('nagad_number')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                        <input type="text" name="nagad_name" value="{{ $settings['nagad_name'] }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-600 focus:border-teal-600" 
                               placeholder="e.g., Alpha Vendor">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                        <select name="nagad_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-600 focus:border-teal-600">
                            <option value="personal" {{ $settings['nagad_type'] == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="agent" {{ $settings['nagad_type'] == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="merchant" {{ $settings['nagad_type'] == 'merchant' ? 'selected' : '' }}>Merchant</option>
                        </select>
                    </div>

                    @if($settings['nagad_number'])
                        <div class="bg-teal-50 border border-orange-200 rounded-lg p-3">
                            <p class="text-sm text-gray-600">Customers will see:</p>
                            <p class="text-lg font-bold text-teal-700">{{ $settings['nagad_number'] }}</p>
                            @if($settings['nagad_name'])
                                <p class="text-sm text-gray-500">{{ $settings['nagad_name'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rocket Settings -->
            <div class="bg-white rounded-lg shadow overflow-hidden lg:col-span-2">
                <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="bg-purple-500 text-white p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Rocket (DBBL)</h2>
                                <p class="text-sm text-gray-500">Dutch Bangla Mobile Banking</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="rocket_enabled" value="1" {{ $settings['rocket_enabled'] == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Enabled</span>
                        </label>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rocket Number</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <input type="text" name="rocket_number" value="{{ $settings['rocket_number'] }}" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" 
                                       placeholder="01XXXXXXXXX">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                            <input type="text" name="rocket_name" value="{{ $settings['rocket_name'] }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" 
                                   placeholder="e.g., Alpha Vendor">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Payment Settings
            </button>
        </div>
    </form>

    <!-- Help Section -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            How Manual Payment Works
        </h3>
        <ol class="text-sm text-blue-700 space-y-2">
            <li><strong>1.</strong> Enter your personal bKash/Nagad numbers above</li>
            <li><strong>2.</strong> Customers will see these numbers at checkout and send money manually</li>
            <li><strong>3.</strong> After payment, customers enter their Transaction ID on the order form</li>
            <li><strong>4.</strong> You verify payments in <a href="{{ route('admin.manual-payments.index') }}" class="underline font-medium">Payment Verification</a> section</li>
            <li><strong>5.</strong> Once verified, order status updates to "Paid"</li>
        </ol>
    </div>
</div>
@endsection
