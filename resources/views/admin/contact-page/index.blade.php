@extends('layouts.dashboard')

@section('title', 'Contact Page Content')
@section('page-title', 'Contact Page Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                <i class="fas fa-address-book text-indigo-600 mr-3"></i>Contact Page Content
            </h1>
            <p class="text-gray-100 mt-2 text-sm sm:text-base">Manage all content displayed on the contact page</p>
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
            <div class="mb-6 bg-white border-l-4 border-red-500 rounded-r-xl shadow-md overflow-hidden">
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
            <div class="mb-6 bg-white border-l-4 border-red-500 rounded-r-xl shadow-md overflow-hidden">
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

        <form action="{{ route('admin.contact-page.update') }}" method="POST">
            @csrf

            <!-- Hero Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-star"></i>
                        Hero Section
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            <i class="fas fa-heading text-indigo-600 mr-2"></i>Hero Title
                        </label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $content->hero_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            <i class="fas fa-align-left text-indigo-600 mr-2"></i>Hero Subtitle
                        </label>
                        <textarea name="hero_subtitle" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">{{ old('hero_subtitle', $content->hero_subtitle) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Contact Info Cards -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Contact Information Cards
                    </h2>
                </div>
                <div class="p-6 space-y-8">
                    <!-- Address -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-white mb-4">Address Section</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Section Title</label>
                                <input type="text" name="address_title" value="{{ old('address_title', $content->address_title) }}" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Address Line 1</label>
                                <input type="text" name="address_line1" value="{{ old('address_line1', $content->address_line1) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Address Line 2</label>
                                <input type="text" name="address_line2" value="{{ old('address_line2', $content->address_line2) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Address Line 3</label>
                                <input type="text" name="address_line3" value="{{ old('address_line3', $content->address_line3) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-white mb-4">Phone Section</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Section Title</label>
                                <input type="text" name="phone_title" value="{{ old('phone_title', $content->phone_title) }}" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Primary Phone</label>
                                <input type="text" name="phone_primary" value="{{ old('phone_primary', $content->phone_primary) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Secondary Phone</label>
                                <input type="text" name="phone_secondary" value="{{ old('phone_secondary', $content->phone_secondary) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Phone Hours</label>
                                <input type="text" name="phone_hours" value="{{ old('phone_hours', $content->phone_hours) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                                    placeholder="e.g., Mon-Fri 9AM-6PM">
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-white mb-4">Email Section</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Section Title</label>
                                <input type="text" name="email_title" value="{{ old('email_title', $content->email_title) }}" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Info Email</label>
                                <input type="email" name="email_info" value="{{ old('email_info', $content->email_info) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Support Email</label>
                                <input type="email" name="email_support" value="{{ old('email_support', $content->email_support) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Sales Email</label>
                                <input type="email" name="email_sales" value="{{ old('email_sales', $content->email_sales) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4">Working Hours Section</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Section Title</label>
                                <input type="text" name="hours_title" value="{{ old('hours_title', $content->hours_title) }}" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Weekdays</label>
                                <input type="text" name="hours_weekdays" value="{{ old('hours_weekdays', $content->hours_weekdays) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                                    placeholder="e.g., Monday - Friday">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Weekdays Time</label>
                                <input type="text" name="hours_weekdays_time" value="{{ old('hours_weekdays_time', $content->hours_weekdays_time) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                                    placeholder="e.g., 9:00 AM - 6:00 PM">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-white mb-2">Weekend Hours</label>
                                <input type="text" name="hours_weekend" value="{{ old('hours_weekend', $content->hours_weekend) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                                    placeholder="e.g., Saturday: 10AM-4PM">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form & Map Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-map-marked-alt"></i>
                        Form & Map Section
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            <i class="fas fa-envelope text-green-600 mr-2"></i>Contact Form Title
                        </label>
                        <input type="text" name="form_title" value="{{ old('form_title', $content->form_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            <i class="fas fa-map text-green-600 mr-2"></i>Map Section Title
                        </label>
                        <input type="text" name="map_title" value="{{ old('map_title', $content->map_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            <i class="fas fa-code text-green-600 mr-2"></i>Google Maps Embed URL
                        </label>
                        <textarea name="map_embed_url" rows="3" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all font-mono text-sm"
                            placeholder="https://www.google.com/maps/embed?pb=...">{{ old('map_embed_url', $content->map_embed_url) }}</textarea>
                        <p class="text-xs text-gray-200 mt-2">Get embed URL from Google Maps → Share → Embed a map</p>
                    </div>
                </div>
            </div>

            <!-- Social Media Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-share-alt"></i>
                        Social Media Section
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Section Title</label>
                        <input type="text" name="social_title" value="{{ old('social_title', $content->social_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Description</label>
                        <textarea name="social_description" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">{{ old('social_description', $content->social_description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook URL
                            </label>
                            <input type="url" name="social_facebook" value="{{ old('social_facebook', $content->social_facebook) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter URL
                            </label>
                            <input type="url" name="social_twitter" value="{{ old('social_twitter', $content->social_twitter) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram URL
                            </label>
                            <input type="url" name="social_instagram" value="{{ old('social_instagram', $content->social_instagram) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                            </label>
                            <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $content->social_linkedin) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">
                                <i class="fab fa-youtube text-red-600 mr-2"></i>YouTube URL
                            </label>
                            <input type="url" name="social_youtube" value="{{ old('social_youtube', $content->social_youtube) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-teal-700 to-teal-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-question-circle"></i>
                        FAQ Section
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Section Title</label>
                        <input type="text" name="faq_title" value="{{ old('faq_title', $content->faq_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-teal-200 focus:border-teal-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Subtitle</label>
                        <textarea name="faq_subtitle" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-teal-200 focus:border-teal-600 transition-all">{{ old('faq_subtitle', $content->faq_subtitle) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-red-600 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-bullhorn"></i>
                        Call to Action Section
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">CTA Title</label>
                        <input type="text" name="cta_title" value="{{ old('cta_title', $content->cta_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-white mb-2">CTA Subtitle</label>
                        <textarea name="cta_subtitle" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">{{ old('cta_subtitle', $content->cta_subtitle) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Email Button Text</label>
                            <input type="text" name="cta_email_text" value="{{ old('cta_email_text', $content->cta_email_text) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all"
                                placeholder="e.g., Email Us">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Email Link</label>
                            <input type="email" name="cta_email_link" value="{{ old('cta_email_link', $content->cta_email_link) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all"
                                placeholder="support@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Phone Button Text</label>
                            <input type="text" name="cta_phone_text" value="{{ old('cta_phone_text', $content->cta_phone_text) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all"
                                placeholder="e.g., Call Us Now">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Phone Link</label>
                            <input type="text" name="cta_phone_link" value="{{ old('cta_phone_link', $content->cta_phone_link) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all"
                                placeholder="+1234567890">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save All Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
