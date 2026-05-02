@extends('layouts.dashboard')

@section('title', 'About Page Content')
@section('page-title', 'About Page Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                <i class="fas fa-info-circle text-indigo-600 mr-3"></i>About Page Content
            </h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">Manage all content displayed on the about page</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-white border-l-4 border-green-500 rounded-r-xl shadow-md overflow-hidden">
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

        <form action="{{ route('admin.about-page.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- SEO Meta --}}
            @include('admin.partials.seo-meta-fields', ['meta' => $content])

            {{-- Logo Upload --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-image"></i> About Page Logo
                    </h2>
                </div>
                <div class="p-6 flex items-center gap-6">
                    @if($content->logo)
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $content->logo) }}" alt="Logo"
                             class="h-20 w-auto object-contain border border-gray-200 rounded-lg p-2 bg-gray-50">
                    </div>
                    @endif
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">
                            Upload Logo <span class="text-xs font-normal text-gray-400">(PNG, JPG, SVG, WebP — max 2MB)</span>
                        </label>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        @if($content->logo)
                        <p class="text-xs text-gray-400 mt-1">Upload a new file to replace the current logo</p>
                        @endif
                    </div>
                </div>
            </div>

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
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hero Title</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $content->hero_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hero Subtitle</label>
                        <textarea name="hero_subtitle" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">{{ old('hero_subtitle', $content->hero_subtitle) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Primary Button Text</label>
                            <input type="text" name="hero_cta_primary_text" value="{{ old('hero_cta_primary_text', $content->hero_cta_primary_text) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Primary Button Link</label>
                            <input type="text" name="hero_cta_primary_link" value="{{ old('hero_cta_primary_link', $content->hero_cta_primary_link) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Secondary Button Text</label>
                            <input type="text" name="hero_cta_secondary_text" value="{{ old('hero_cta_secondary_text', $content->hero_cta_secondary_text) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Secondary Button Link</label>
                            <input type="text" name="hero_cta_secondary_link" value="{{ old('hero_cta_secondary_link', $content->hero_cta_secondary_link) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Story Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-book-open"></i>
                        Our Story Section
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Section Title</label>
                        <input type="text" name="story_title" value="{{ old('story_title', $content->story_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Paragraph 1</label>
                        <textarea name="story_paragraph_1" rows="3" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">{{ old('story_paragraph_1', $content->story_paragraph_1) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Paragraph 2</label>
                        <textarea name="story_paragraph_2" rows="3" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">{{ old('story_paragraph_2', $content->story_paragraph_2) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Paragraph 3</label>
                        <textarea name="story_paragraph_3" rows="3" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">{{ old('story_paragraph_3', $content->story_paragraph_3) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Mission & Vision -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-bullseye"></i>
                        Mission & Vision
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Mission</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mission Title</label>
                                <input type="text" name="mission_title" value="{{ old('mission_title', $content->mission_title) }}" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mission Content</label>
                                <textarea name="mission_content" rows="4" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">{{ old('mission_content', $content->mission_content) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Vision</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Vision Title</label>
                                <input type="text" name="vision_title" value="{{ old('vision_title', $content->vision_title) }}" required 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Vision Content</label>
                                <textarea name="vision_content" rows="4" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-200 focus:border-green-500 transition-all">{{ old('vision_content', $content->vision_content) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-chart-line"></i>
                        Statistics Section
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Vendors Count</label>
                            <input type="text" name="stats_vendors" value="{{ old('stats_vendors', $content->stats_vendors) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all"
                                placeholder="e.g., 1000+">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Products Count</label>
                            <input type="text" name="stats_products" value="{{ old('stats_products', $content->stats_products) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all"
                                placeholder="e.g., 50000+">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Customers Count</label>
                            <input type="text" name="stats_customers" value="{{ old('stats_customers', $content->stats_customers) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all"
                                placeholder="e.g., 100000+">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Countries Count</label>
                            <input type="text" name="stats_countries" value="{{ old('stats_countries', $content->stats_countries) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all"
                                placeholder="e.g., 50+">
                        </div>
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
                        <label class="block text-sm font-bold text-gray-700 mb-2">CTA Title</label>
                        <input type="text" name="cta_title" value="{{ old('cta_title', $content->cta_title) }}" required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">CTA Subtitle</label>
                        <textarea name="cta_subtitle" rows="2" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">{{ old('cta_subtitle', $content->cta_subtitle) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Primary Button Text</label>
                            <input type="text" name="cta_primary_text" value="{{ old('cta_primary_text', $content->cta_primary_text) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Primary Button Link</label>
                            <input type="text" name="cta_primary_link" value="{{ old('cta_primary_link', $content->cta_primary_link) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Secondary Button Text</label>
                            <input type="text" name="cta_secondary_text" value="{{ old('cta_secondary_text', $content->cta_secondary_text) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Secondary Button Link</label>
                            <input type="text" name="cta_secondary_link" value="{{ old('cta_secondary_link', $content->cta_secondary_link) }}" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 transition-all">
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
