@extends('layouts.dashboard')

@section('title', 'Promo Banner Management')
@section('page-title', 'Promo Banners')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Promo Banner Management</h2>
            <p class="text-gray-500 mt-1">Manage promotional banners with text and images (displayed on homepage)</p>
        </div>
        <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all flex items-center gap-2 shadow-lg">
            <i class="fas fa-plus"></i> Add Promo Banner
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Preview</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title & Subtitle</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Button</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Colors</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($promoBanners as $banner)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-24 h-16 object-cover rounded-lg shadow-sm border border-gray-200">
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-800">{{ $banner->title }}</span>
                        @if($banner->subtitle)
                            <br><span class="text-sm text-gray-500">{{ $banner->subtitle }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($banner->button_text)
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                {{ $banner->button_text }}
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">No button</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $banner->background_color }}"></div>
                            <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $banner->text_color }}"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm">
                            {{ $banner->sort_order }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick='editBanner(@json($banner))' class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors text-sm">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="openDeleteModal({{ $banner->id }}, '{{ $banner->title }}')" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-bullhorn text-6xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500 text-lg">No promo banners found</p>
                            <p class="text-gray-400 text-sm mt-1">Click "Add Promo Banner" to create your first promo banner</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="bannerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Promo Banner</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="bannerForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="bannerTitle" required placeholder="e.g., Mega Sale Event" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                    <input type="text" name="subtitle" id="bannerSubtitle" placeholder="e.g., Limited Time Offer" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="bannerDescription" rows="3" placeholder="Promotional message..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                    <input type="text" name="button_text" id="bannerButtonText" placeholder="e.g., Shop Now" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                    <input type="text" name="button_link" id="bannerButtonLink" placeholder="e.g., /shop" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image *</label>
                <input type="file" name="image" id="bannerImage" accept="image/*" onchange="previewImage(event)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-400 mt-1">Recommended size: 800x600px. Max 2MB.</p>
                <div id="imagePreview" class="hidden mt-4 relative">
                    <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border-2 border-yellow-500">
                    <button type="button" onclick="cancelImage()" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="background_color" id="bannerBgColor" value="#FFA500" class="w-12 h-10 rounded border border-gray-300 cursor-pointer">
                        <input type="text" id="bannerBgColorText" value="#FFA500" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="document.getElementById('bannerBgColor').value = this.value">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="text_color" id="bannerTextColor" value="#FFFFFF" class="w-12 h-10 rounded border border-gray-300 cursor-pointer">
                        <input type="text" id="bannerTextColorText" value="#FFFFFF" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" onchange="document.getElementById('bannerTextColor').value = this.value">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" id="bannerSortOrder" value="0" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div class="mt-5">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="bannerStatus" value="1" checked class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-500">
                    <span class="ml-3 text-gray-700">Active (visible on homepage)</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end pt-6 mt-6 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Promo Banner
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md shadow-2xl transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Promo Banner</h3>
            <p class="text-gray-500 mb-6">Are you sure you want to delete "<span id="deleteBannerName" class="font-semibold text-gray-700"></span>"? This action cannot be undone.</p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3"></div>

<script>
let editingBannerId = null;

function openAddModal() {
    editingBannerId = null;
    document.getElementById('modalTitle').textContent = 'Add Promo Banner';
    document.getElementById('bannerForm').action = '{{ route('admin.promo-banners.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('bannerForm').reset();
    document.getElementById('bannerStatus').checked = true;
    document.getElementById('bannerBgColor').value = '#FFA500';
    document.getElementById('bannerBgColorText').value = '#FFA500';
    document.getElementById('bannerTextColor').value = '#FFFFFF';
    document.getElementById('bannerTextColorText').value = '#FFFFFF';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('bannerImage').required = true;
    document.getElementById('bannerModal').classList.remove('hidden');
}

function editBanner(banner) {
    editingBannerId = banner.id;
    document.getElementById('modalTitle').textContent = 'Edit Promo Banner';
    document.getElementById('bannerForm').action = `/admin/promo-banners/${banner.id}`;
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('bannerTitle').value = banner.title || '';
    document.getElementById('bannerSubtitle').value = banner.subtitle || '';
    document.getElementById('bannerDescription').value = banner.description || '';
    document.getElementById('bannerButtonText').value = banner.button_text || '';
    document.getElementById('bannerButtonLink').value = banner.button_link || '';
    document.getElementById('bannerBgColor').value = banner.background_color || '#FFA500';
    document.getElementById('bannerBgColorText').value = banner.background_color || '#FFA500';
    document.getElementById('bannerTextColor').value = banner.text_color || '#FFFFFF';
    document.getElementById('bannerTextColorText').value = banner.text_color || '#FFFFFF';
    document.getElementById('bannerSortOrder').value = banner.sort_order;
    document.getElementById('bannerStatus').checked = banner.is_active;
    document.getElementById('bannerImage').required = false;

    // Show current image
    if (banner.image) {
        const imageUrl = banner.image.startsWith('http') ? banner.image : `/storage/${banner.image}`;
        document.getElementById('previewImg').src = imageUrl;
        document.getElementById('imagePreview').classList.remove('hidden');
    } else {
        document.getElementById('imagePreview').classList.add('hidden');
    }

    document.getElementById('bannerModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('bannerModal').classList.add('hidden');
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function cancelImage() {
    document.getElementById('bannerImage').value = '';
    if (!editingBannerId) {
        document.getElementById('imagePreview').classList.add('hidden');
    }
}

function openDeleteModal(bannerId, bannerName) {
    document.getElementById('deleteBannerName').textContent = bannerName;
    document.getElementById('deleteForm').action = `/admin/promo-banners/${bannerId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Sync color pickers
document.getElementById('bannerBgColor').addEventListener('input', function() {
    document.getElementById('bannerBgColorText').value = this.value;
});
document.getElementById('bannerTextColor').addEventListener('input', function() {
    document.getElementById('bannerTextColorText').value = this.value;
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;

    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-x-full'), 10);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

window.onclick = function(event) {
    if (event.target.id === 'bannerModal') closeModal();
    if (event.target.id === 'deleteModal') closeDeleteModal();
}

@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'));
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'));
@endif
</script>
@endsection
