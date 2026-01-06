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
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Retail Page Management</h2>
            <p style="color: #7f8c8d;">Customize the retail marketplace page content</p>
        </div>
        <a href="{{ route('retail') }}" target="_blank" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: all 0.3s;">
            <i class="fas fa-external-link-alt"></i> Preview Page
        </a>
    </div>
</div>

{{-- @if(session('success'))
<div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
    <p style="color: #155724; margin: 0;">
        <i class="fas fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}
    </p>
</div>
@endif --}}

<form action="{{ route('admin.retail-page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Hero Section -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-image" style="margin-right: 10px; color: #667eea;"></i>
            Hero Section
        </h3>

        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Title</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $content['hero_title'] ?? '') }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>
                @error('hero_title')
                    <p style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Description</label>
                <textarea name="hero_description" rows="3"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" required>{{ old('hero_description', $content['hero_description'] ?? '') }}</textarea>
                @error('hero_description')
                    <p style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Hero Image</label>
                <div style="margin-bottom: 12px;" id="imagePreviewContainer">
                    @if(isset($content['hero_image']) && $content['hero_image'])
                        @php
                            $imageUrl = str_starts_with($content['hero_image'], 'http') ? $content['hero_image'] : asset('storage/' . $content['hero_image']);
                            $imageUrl .= '?v=' . time(); // Cache busting
                        @endphp
                        <img src="{{ $imageUrl }}"
                            alt="Current Hero Image"
                            id="imagePreview"
                            style="width: 192px; height: 128px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                    @else
                        <img src=""
                            alt="Image Preview"
                            id="imagePreview"
                            style="width: 192px; height: 128px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; display: none;">
                    @endif
                </div>
                <input type="file" name="hero_image" id="heroImageInput" accept="image/*"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Upload a new image to replace the current one (max 2MB)</p>
                @error('hero_image')
                    <p style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="margin-right: 10px; color: #667eea;"></i>
            Statistics Section
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <!-- Stat 1 -->
            <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 15px;">Statistic 1</h4>
                <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Number</label>
                <input type="text" name="stat1_number" value="{{ old('stat1_number', $content['stat1_number'] ?? '') }}"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;"
                    placeholder="e.g., 500+" required>

                <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Label</label>
                <input type="text" name="stat1_label" value="{{ old('stat1_label', $content['stat1_label'] ?? '') }}"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    placeholder="e.g., Retail Stores" required>
            </div>

            <!-- Stat 2 -->
            <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 15px;">Statistic 2</h4>
                <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Number</label>
                <input type="text" name="stat2_number" value="{{ old('stat2_number', $content['stat2_number'] ?? '') }}"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;"
                    placeholder="e.g., 10K+" required>

                <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Label</label>
                <input type="text" name="stat2_label" value="{{ old('stat2_label', $content['stat2_label'] ?? '') }}"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    placeholder="e.g., Products" required>
            </div>

            <!-- Stat 3 -->
            <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 15px;">Statistic 3</h4>
                <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Number</label>
                <input type="text" name="stat3_number" value="{{ old('stat3_number', $content['stat3_number'] ?? '') }}"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px;"
                    placeholder="e.g., 50K+" required>

                <label style="display: block; font-size: 12px; color: #7f8c8d; margin-bottom: 5px;">Label</label>
                <input type="text" name="stat3_label" value="{{ old('stat3_label', $content['stat3_label'] ?? '') }}"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    placeholder="e.g., Happy Customers" required>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="display: flex; justify-content: flex-end;">
        <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>

<script>
document.getElementById('heroImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');

    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            e.target.value = '';
            return;
        }

        // Read and display the image
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
