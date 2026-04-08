@extends('layouts.dashboard')

@section('title', 'OTP & API Configuration')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">OTP & API Configuration</h1>
            <p class="text-gray-600">Configure MiMSMS API credentials and OTP settings</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.otp-settings.update') }}" method="POST" id="otpSettingsForm">
        @csrf

        <!-- MiMSMS API Configuration -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    MiMSMS API Configuration
                </h2>
            </div>

            <div class="p-6 space-y-4">
                <!-- API Key -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        MiMSMS API Key <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="mimsms_apikey" value="{{ $settings['mimsms_apikey'] }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="VTSJEXXXXXXXXXX9CL" required>
                    <p class="mt-1 text-sm text-gray-500">Your MiMSMS API key from the provider</p>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        MiMSMS Username (Email) <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="mimsms_username" value="{{ $settings['mimsms_username'] }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="your@email.com" required>
                    <p class="mt-1 text-sm text-gray-500">Your MiMSMS account email</p>
                </div>

                <!-- Sender Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sender Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="mimsms_sender_name" value="{{ $settings['mimsms_sender_name'] }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="iSMS" required>
                    <p class="mt-1 text-sm text-gray-500">Approved sender name from MiMSMS (e.g., iSMS)</p>
                </div>

                <!-- Campaign Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Campaign Name
                    </label>
                    <input type="text" name="mimsms_campaign_name" value="{{ $settings['mimsms_campaign_name'] }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="armarketbd">
                    <p class="mt-1 text-sm text-gray-500">Optional campaign identifier</p>
                </div>

                <!-- SMS Message Template -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        OTP SMS Message Template <span class="text-red-500">*</span>
                    </label>
                    <textarea name="otp_sms_template" rows="3"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="Your OTP is: {otp}. Valid for 15 minutes. Do not share this code." required>{{ $settings['otp_sms_template'] ?? 'Your OTP is: {otp}. Valid for 15 minutes. Do not share this code.' }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        <span class="font-semibold">Use {otp} placeholder</span> where the OTP code should appear. 
                        Example: "Your OTP is: {otp}. Valid for 15 minutes."
                    </p>
                </div>
            </div>
        </div>

        <!-- OTP Configuration -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    OTP Configuration
                </h2>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- OTP Expiry -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            OTP Expiry (minutes) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="otp_expiry_minutes" value="{{ $settings['otp_expiry_minutes'] }}" 
                               min="1" max="60"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               required>
                        <p class="mt-1 text-sm text-gray-500">How long OTP remains valid (1-60 minutes)</p>
                    </div>

                    <!-- Max Attempts -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Maximum Attempts <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="otp_max_attempts" value="{{ $settings['otp_max_attempts'] }}" 
                               min="1" max="10"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               required>
                        <p class="mt-1 text-sm text-gray-500">Maximum verification attempts allowed (1-10)</p>
                    </div>
                </div>

                <!-- Test Mode -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_test_mode" value="1" {{ $settings['sms_test_mode'] ? 'checked' : '' }} 
                               class="w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                        <span class="ml-3">
                            <span class="text-sm font-medium text-gray-900">Enable Test Mode</span>
                            <span class="block text-sm text-gray-600">When enabled, OTP will be shown on screen instead of sending SMS (for development/testing)</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Test API Connection -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-teal-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Test API Connection
                </h2>
            </div>

            <div class="p-6">
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Test Mobile Number
                        </label>
                        <input type="text" id="test_mobile" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                               placeholder="01XXXXXXXXX or 8801XXXXXXXXX">
                        <p class="mt-1 text-sm text-gray-500">Enter a mobile number to receive test OTP</p>
                    </div>
                    <button type="button" id="testApiBtn" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Test API
                    </button>
                </div>

                <!-- Test Result -->
                <div id="testResult" class="mt-4 hidden"></div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end gap-4">
            <button type="submit" 
                    class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('testApiBtn').addEventListener('click', function() {
    const mobile = document.getElementById('test_mobile').value;
    const resultDiv = document.getElementById('testResult');
    const btn = this;
    
    if (!mobile) {
        resultDiv.className = 'mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
        resultDiv.innerHTML = 'Please enter a mobile number';
        resultDiv.classList.remove('hidden');
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';
    
    fetch('{{ route("admin.otp-settings.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ test_mobile: mobile })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.className = 'mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded';
            resultDiv.innerHTML = `
                <p class="font-semibold">✅ ${data.message}</p>
                <p class="text-sm mt-2">OTP: <span class="font-mono font-bold">${data.otp}</span></p>
                <p class="text-sm">SMS Message: <span class="italic">"${data.sms_message}"</span></p>
                <p class="text-sm">Transaction ID: ${data.transaction_id}</p>
            `;
        } else {
            resultDiv.className = 'mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
            resultDiv.innerHTML = `<p class="font-semibold">❌ ${data.message}</p>`;
        }
        resultDiv.classList.remove('hidden');
    })
    .catch(error => {
        resultDiv.className = 'mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
        resultDiv.innerHTML = `<p class="font-semibold">❌ Connection failed: ${error.message}</p>`;
        resultDiv.classList.remove('hidden');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Test API';
    });
});
</script>
@endsection
