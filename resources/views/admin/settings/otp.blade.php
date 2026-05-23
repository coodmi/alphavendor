@extends('layouts.dashboard')

@section('title', 'OTP/API Settings')
@section('page-title', 'OTP/API Settings')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-xl mx-auto">
        <h2 class="text-2xl font-bold mb-4">OTP & API Configuration</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.settings.otp.update') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">MiMSMS API Key</label>
                <input type="text" name="MIMSMS_APIKEY" value="{{ $settings['MIMSMS_APIKEY'] }}" class="w-full border rounded px-3 py-2" required>
                @error('MIMSMS_APIKEY')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">MiMSMS Username</label>
                <input type="text" name="MIMSMS_USERNAME" value="{{ $settings['MIMSMS_USERNAME'] }}" class="w-full border rounded px-3 py-2" required>
                @error('MIMSMS_USERNAME')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Sender Name</label>
                <input type="text" name="MIMSMS_SENDER" value="{{ $settings['MIMSMS_SENDER'] }}" class="w-full border rounded px-3 py-2" required>
                @error('MIMSMS_SENDER')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">OTP Expiry (minutes)</label>
                <input type="number" name="OTP_EXPIRY" value="{{ $settings['OTP_EXPIRY'] }}" class="w-full border rounded px-3 py-2" min="1" required>
                @error('OTP_EXPIRY')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">OTP SMS Template (সব OTP-তে একই)</label>
                <textarea name="OTP_TEMPLATE" rows="5" class="w-full border rounded px-3 py-2 font-mono text-sm" required>{{ $settings['OTP_TEMPLATE'] }}</textarea>
                <small class="text-gray-500">Use <code>{otp}</code> for the code. Optional: <code>{expiry}</code> for minutes.</small>
                @error('OTP_TEMPLATE')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700" id="saveBtn">Save Settings</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const saveBtn = document.getElementById('saveBtn');
    
    form.addEventListener('submit', function(e) {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        saveBtn.classList.add('opacity-50');
        
        // Re-enable button after 5 seconds in case of issues
        setTimeout(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Settings';
            saveBtn.classList.remove('opacity-50');
        }, 5000);
    });
});
</script>
@endsection
