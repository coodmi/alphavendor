@extends('layouts.dashboard')

@section('title', 'Special Offer Management')
@section('page-title', 'Special Offer')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Homepage Banner Management</h2>
            <p class="text-gray-500 mt-1">Manage promotional banners displayed on the homepage</p>
        </div>
        <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all flex items-center gap-2 shadow-lg">
            <i class="fas fa-plus"></i> Add Banner
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Preview</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Link</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($banners as $banner)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-32 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-800">{{ $banner->title ?? 'No Title' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                {{ Str::limit($banner->link, 30) }}
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">No link</span>
                        @endif
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
                            <button onclick='editBanner(@json($banner))' class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="openDeleteModal({{ $banner->id }}, '{{ $banner->title ?? 'this banner' }}')" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-images text-6xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500 text-lg">No banners found</p>
                            <p class="text-gray-400 text-sm mt-1">Click "Add Banner" to create your first banner</p>
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
    <div class="bg-white rounded-xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add Banner</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="bannerForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Banner Title</label>
                <input type="text" name="title" id="bannerTitle" placeholder="e.g., Summer Sale" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image *</label>
                <input type="file" name="image" id="bannerImage" accept="image/*" onchange="previewImage(event)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-400 mt-1">Recommended size: 1200x400px. Max 2MB.</p>
                <div id="imagePreview" class="hidden mt-4 relative">
                    <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border-2 border-purple-500">
                    <button type="button" onclick="cancelImage()" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag text-purple-600 mr-1"></i>Special Offer (Optional)
                </label>
                <select name="special_offer_id" id="bannerSpecialOffer" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    <option value="">No Special Offer</option>
                    @foreach($offers as $offer)
                        <option value="{{ $offer->id }}">{{ $offer->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">If selected, banner will link to this special offer page</p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Link URL (Optional)</label>
                <input type="text" name="link" id="bannerLink" placeholder="e.g., /shop or https://example.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-400 mt-1">Leave empty if using Special Offer above</p>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="bannerSortOrder" value="0" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-400 mt-1">Lower numbers appear first</p>
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="bannerStatus" value="1" checked class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                    <span class="ml-3 text-gray-700">Active (visible on homepage)</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Banner
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
            <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Banner</h3>
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
    document.getElementById('modalTitle').textContent = 'Add Banner';
    document.getElementById('bannerForm').action = '{{ route('admin.banners.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('bannerForm').reset();
    document.getElementById('bannerStatus').checked = true;
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('bannerImage').required = true;
    document.getElementById('bannerModal').classList.remove('hidden');
}

function editBanner(banner) {
    editingBannerId = banner.id;
    document.getElementById('modalTitle').textContent = 'Edit Banner';
    document.getElementById('bannerForm').action = `/admin/banners/${banner.id}`;
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('bannerTitle').value = banner.title || '';
    document.getElementById('bannerLink').value = banner.link || '';
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
    document.getElementById('deleteForm').action = `/admin/banners/${bannerId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 transform translate-x-full transition-transform duration-300`;
    toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;

    document.getElementById('toastContainer').appendChild(toast);

    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 10);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.id === 'bannerModal') closeModal();
    if (event.target.id === 'deleteModal') closeDeleteModal();
}

// Show success toast if session has success message
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'));
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'));
@endif
</script>
@endsection
