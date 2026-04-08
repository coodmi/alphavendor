@extends('layouts.dashboard')

@section('title', 'Header & Footer Settings')
@section('page-title', 'Header & Footer Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                <i class="fas fa-cog text-indigo-600 mr-3"></i>Header & Footer Settings
            </h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Customize your site's header logo and footer content</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-white border-l-4 border-green-500 rounded-r-xl shadow-md overflow-hidden animate-fade-in">
                <div class="p-4 flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 bg-white border-l-4 border-red-500 rounded-r-xl shadow-md overflow-hidden animate-fade-in">
                <div class="p-4 flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-red-800 font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="mb-6 bg-white border-l-4 border-red-500 rounded-r-xl shadow-md overflow-hidden animate-fade-in">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-red-800 font-semibold">Please fix the following errors:</p>
                        </div>
                    </div>
                    <ul class="ml-14 list-disc text-red-700 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Header Settings Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-image"></i>
                        Header Settings
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Site Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-tag text-indigo-600 mr-2"></i>Site Name
                        </label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all @error('site_name') border-red-500 @enderror">
                        @error('site_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Site Logo -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-image text-indigo-600 mr-2"></i>Site Logo
                        </label>
                        @if($settings->site_logo)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Current Logo" class="h-16 object-contain border border-gray-200 rounded-lg p-2">
                                <p class="text-xs text-gray-500 mt-1">Current logo</p>
                            </div>
                        @endif
                        <input type="file" name="site_logo" accept="image/*" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all @error('site_logo') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-2">Recommended: PNG or SVG format, max 2MB</p>
                        @error('site_logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Site Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-indigo-600 mr-2"></i>Site Description
                        </label>
                        <textarea name="site_description" rows="3" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all @error('site_description') border-red-500 @enderror">{{ old('site_description', $settings->site_description) }}</textarea>
                        @error('site_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Footer Settings Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-shoe-prints"></i>
                        Footer Settings
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Footer Text -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-paragraph text-blue-600 mr-2"></i>Footer About Text
                        </label>
                        <textarea name="footer_text" rows="3" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all @error('footer_text') border-red-500 @enderror">{{ old('footer_text', $settings->footer_text) }}</textarea>
                        @error('footer_text')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Footer Copyright -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-copyright text-blue-600 mr-2"></i>Copyright Text
                        </label>
                        <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings->footer_copyright) }}" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all @error('footer_copyright') border-red-500 @enderror"
                            placeholder="© 2024 AlphaVendor. All rights reserved.">
                        @error('footer_copyright')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Methods Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-credit-card"></i>
                        Payment Method Logos
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <p class="text-sm text-gray-600 mb-4">Upload up to 3 payment method logos to display in the footer (recommended size: 100x60px)</p>
                    
                    <!-- Payment Logo 1 -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-green-600 mr-2"></i>Payment Logo 1
                        </label>
                        @if($settings->payment_logo_1)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $settings->payment_logo_1) }}" alt="Payment Logo 1" class="h-12 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                                <p class="text-xs text-gray-500 mt-1">Current logo</p>
                            </div>
                        @endif
                        <input type="file" name="payment_logo_1" accept="image/*" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                        <p class="text-xs text-gray-500 mt-2">PNG or SVG format recommended, max 2MB</p>
                    </div>

                    <!-- Payment Logo 2 -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-green-600 mr-2"></i>Payment Logo 2
                        </label>
                        @if($settings->payment_logo_2)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $settings->payment_logo_2) }}" alt="Payment Logo 2" class="h-12 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                                <p class="text-xs text-gray-500 mt-1">Current logo</p>
                            </div>
                        @endif
                        <input type="file" name="payment_logo_2" accept="image/*" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                        <p class="text-xs text-gray-500 mt-2">PNG or SVG format recommended, max 2MB</p>
                    </div>

                    <!-- Payment Logo 3 -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-credit-card text-green-600 mr-2"></i>Payment Logo 3
                        </label>
                        @if($settings->payment_logo_3)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $settings->payment_logo_3) }}" alt="Payment Logo 3" class="h-12 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                                <p class="text-xs text-gray-500 mt-1">Current logo</p>
                            </div>
                        @endif
                        <input type="file" name="payment_logo_3" accept="image/*" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                        <p class="text-xs text-gray-500 mt-2">PNG or SVG format recommended, max 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Social Media & Contact Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-share-alt"></i>
                        Social Media & Contact
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Social Media Links -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook URL
                            </label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter URL
                            </label>
                            <input type="url" name="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram URL
                            </label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                            </label>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-purple-600 mr-2"></i>Contact Email
                            </label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-phone text-purple-600 mr-2"></i>Contact Phone
                            </label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-purple-600 mr-2"></i>Contact Address
                        </label>
                        <textarea name="contact_address" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">{{ old('contact_address', $settings->contact_address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-university text-indigo-600"></i> Bank Transfer Details
                </h2>
                <p class="text-sm text-gray-500 mb-6">This info will be shown to customers when they select Bank Transfer as payment method.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all"
                            placeholder="e.g. Dutch-Bangla Bank">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Account Name</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $settings->bank_account_name) }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all"
                            placeholder="Account holder name">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $settings->bank_account_number) }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all"
                            placeholder="e.g. 1234567890">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Branch Name</label>
                        <input type="text" name="bank_branch" value="{{ old('bank_branch', $settings->bank_branch) }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all"
                            placeholder="e.g. Gulshan Branch">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Routing Number</label>
                        <input type="text" name="bank_routing_number" value="{{ old('bank_routing_number', $settings->bank_routing_number) }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all"
                            placeholder="e.g. 090261539">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
