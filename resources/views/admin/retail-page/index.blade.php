@extends('layouts.dashboard')

@section('title', 'Retail Page Management')
@section('page-title', 'Retail Page')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.products') }}" class="menu-item">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="menu-item">
            <i class="fas fa-tags"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('admin.brands') }}" class="menu-item">
            <i class="fas fa-copyright"></i>
            <span>Brands</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Pages</div>
        <a href="{{ route('admin.retail-page') }}" class="menu-item active">
            <i class="fas fa-store"></i>
            <span>Retail Page</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Account</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Retail Page Management</h2>
            <p class="text-gray-600">Customize the retail marketplace page content</p>
        </div>
        <a href="{{ route('retail') }}" target="_blank" class="px-6 py-3 text-white rounded-lg transition-all flex items-center gap-2" style="background: linear-gradient(135deg, #2D3F51 0%, #1a252f 100%);" onmouseover="this.style.background='linear-gradient(135deg, #1a252f 0%, #0f1419 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #2D3F51 0%, #1a252f 100%)'">
            <i class="fas fa-external-link-alt"></i> Preview Page
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
</div>
@endif

<form action="{{ route('admin.retail-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Hero Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-image mr-2" style="color: #2D3F51;"></i>
            Hero Section
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $content['hero_title'] ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #2D3F51;" required>
                @error('hero_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Description</label>
                <textarea name="hero_description" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #2D3F51;" required>{{ old('hero_description', $content['hero_description'] ?? '') }}</textarea>
                @error('hero_description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Image</label>
                @if(isset($content['hero_image']) && $content['hero_image'])
                <div class="mb-3">
                    @php
                        $imageUrl = str_starts_with($content['hero_image'], 'http') ? $content['hero_image'] : asset('storage/' . $content['hero_image']);
                        $imageUrl .= '?v=' . time(); // Cache busting
                    @endphp
                    <img src="{{ $imageUrl }}"
                        alt="Current Hero Image"
                        class="w-48 h-32 object-cover rounded-lg border border-gray-300">
                </div>
                @endif
                <input type="file" name="hero_image" accept="image/*"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #2D3F51;">
                <p class="text-sm text-gray-500 mt-1">Upload a new image to replace the current one (max 2MB)</p>
                @error('hero_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2" style="color: #2D3F51;"></i>
            Statistics Section
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stat 1 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Statistic 1</h4>
                <label class="block text-xs text-gray-600 mb-1">Number</label>
                <input type="text" name="stat1_number" value="{{ old('stat1_number', $content['stat1_number'] ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent mb-3"
                    style="--tw-ring-color: #2D3F51;" placeholder="e.g., 500+" required>

                <label class="block text-xs text-gray-600 mb-1">Label</label>
                <input type="text" name="stat1_label" value="{{ old('stat1_label', $content['stat1_label'] ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #2D3F51;" placeholder="e.g., Retail Stores" required>
            </div>

            <!-- Stat 2 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Statistic 2</h4>
                <label class="block text-xs text-gray-600 mb-1">Number</label>
                <input type="text" name="stat2_number" value="{{ old('stat2_number', $content['stat2_number'] ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent mb-3"
                    style="--tw-ring-color: #2D3F51;" placeholder="e.g., 10K+" required>

                <label class="block text-xs text-gray-600 mb-1">Label</label>
                <input type="text" name="stat2_label" value="{{ old('stat2_label', $content['stat2_label'] ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #2D3F51;" placeholder="e.g., Products" required>
            </div>

            <!-- Stat 3 -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Statistic 3</h4>
                <label class="block text-xs text-gray-600 mb-1">Number</label>
                <input type="text" name="stat3_number" value="{{ old('stat3_number', $content['stat3_number'] ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent mb-3"
                    style="--tw-ring-color: #2D3F51;" placeholder="e.g., 50K+" required>

                <label class="block text-xs text-gray-600 mb-1">Label</label>
                <input type="text" name="stat3_label" value="{{ old('stat3_label', $content['stat3_label'] ?? '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                    style="--tw-ring-color: #2D3F51;" placeholder="e.g., Happy Customers" required>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end">
        <button type="submit" class="px-8 py-3 text-white rounded-lg transition-all flex items-center gap-2 font-semibold" style="background: linear-gradient(135deg, #2D3F51 0%, #1a252f 100%);" onmouseover="this.style.background='linear-gradient(135deg, #1a252f 0%, #0f1419 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #2D3F51 0%, #1a252f 100%)'">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>
@endsection
