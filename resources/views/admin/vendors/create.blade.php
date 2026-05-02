@extends('layouts.dashboard')

@section('title', 'Add New Vendor')
@section('page-title', 'Add New Vendor')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Add New Vendor</h2>
            <p class="text-gray-500 text-sm mt-1">Create a vendor account directly without requiring registration</p>
        </div>
        <a href="{{ route('admin.vendors') }}"
           class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
            <i class="fas fa-arrow-left"></i> Back to Vendors
        </a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span class="font-semibold text-red-700">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vendors.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Account Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user text-teal-600"></i> Account Information
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Rahman Traders"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="vendor@example.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Mobile --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Mobile Number
                    </label>
                    <input type="text" name="mobile_number" value="{{ old('mobile_number') }}"
                           placeholder="e.g. 01700000000"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm @error('mobile_number') border-red-400 @enderror">
                    @error('mobile_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               placeholder="Minimum 8 characters"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm pr-10 @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('password', 'eye1')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="eye1"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               placeholder="Re-enter password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm pr-10">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- Vendor Settings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-store text-teal-600"></i> Vendor Settings
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Vendor Type <span class="text-red-500">*</span>
                    </label>
                    <select name="role" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm @error('role') border-red-400 @enderror">
                        <option value="">-- Select Type --</option>
                        <option value="retailer"   {{ old('role') == 'retailer'   ? 'selected' : '' }}>🛍️ Retailer</option>
                        <option value="wholesaler" {{ old('role') == 'wholesaler' ? 'selected' : '' }}>📦 Wholesaler</option>
                        <option value="exporter"   {{ old('role') == 'exporter'   ? 'selected' : '' }}>🌐 Importer / Exporter</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Account Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm @error('status') border-red-400 @enderror">
                        <option value="active"    {{ old('status', 'active') == 'active'    ? 'selected' : '' }}>✅ Active</option>
                        <option value="inactive"  {{ old('status') == 'inactive'  ? 'selected' : '' }}>⏸️ Inactive</option>
                        <option value="pending"   {{ old('status') == 'pending'   ? 'selected' : '' }}>⏳ Pending Approval</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>🚫 Suspended</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Verification Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Verification Status
                    </label>
                    <select name="verification_status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="verified"   {{ old('verification_status', 'verified') == 'verified'   ? 'selected' : '' }}>✅ Verified</option>
                        <option value="unverified" {{ old('verification_status') == 'unverified' ? 'selected' : '' }}>❓ Unverified</option>
                        <option value="pending"    {{ old('verification_status') == 'pending'    ? 'selected' : '' }}>⏳ Pending Review</option>
                    </select>
                </div>

                {{-- Badge --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Vendor Badge
                    </label>
                    <select name="vendor_badge_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="">No Badge</option>
                        @foreach($badges as $badge)
                            <option value="{{ $badge->id }}" {{ old('vendor_badge_id') == $badge->id ? 'selected' : '' }}>
                                {{ $badge->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Notes --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Internal Notes
                    </label>
                    <textarea name="notes" rows="3" placeholder="Optional notes about this vendor (only visible to admins)..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm resize-none">{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pb-6">
            <a href="{{ route('admin.vendors') }}"
               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-8 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <i class="fas fa-user-plus"></i> Create Vendor
            </button>
        </div>

    </form>
</div>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
