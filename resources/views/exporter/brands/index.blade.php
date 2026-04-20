@extends('layouts.dashboard')

@section('title', 'Brands Management')
@section('page-title', 'Brands')

@section('sidebar-menu')
    @include('dashboards.partials.exporter-sidebar')
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-white mb-1">Brands Management</h2>
            <p class="text-gray-100">Manage product brands for export</p>
        </div>
        <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i> Add Brand
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Logo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Brand Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Parent Brand</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Products</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Sort Order</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="brandsTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($brands as $brand)
                <tr id="brand-row-{{ $brand->id }}" class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($brand->logo)
                            @if(filter_var($brand->logo, FILTER_VALIDATE_URL))
                                <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="w-14 h-14 object-cover rounded-lg shadow-sm">
                            @else
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-14 h-14 object-cover rounded-lg shadow-sm">
                            @endif
                        @else
                            <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-copyright text-gray-400 text-xl"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-white">{{ $brand->name }}</div>
                        <div class="text-sm text-gray-200">{{ $brand->description ? Str::limit($brand->description, 50) : 'No description' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($brand->parent)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                <i class="fas fa-sitemap mr-1"></i> {{ $brand->parent->name }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $brand->products_count ?? 0 }} Products
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $brand->sort_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <button onclick='editBrand(@json($brand))' class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-150 mr-2">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button onclick="confirmDelete({{ $brand->id }}, '{{ $brand->name }}')" class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-150">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <i class="fas fa-copyright text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-200 text-lg">No brands found. Click "Add Brand" to create one.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="brandModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Add Brand</h3>
            <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form id="brandForm" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Parent Brand (Admin) *</label>
                <select name="parent_brand_id" id="parentBrandId" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    <option value="">Select Admin Brand</option>
                    @foreach($adminBrands as $adminBrand)
                        <option value="{{ $adminBrand->id }}">{{ $adminBrand->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-200 mt-1">Link your custom brand to an admin brand</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Brand Name *</label>
                <input type="text" name="name" id="brandName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Description</label>
                <textarea name="description" id="brandDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Brand Logo</label>

                <!-- Image Source Toggle -->
                <div class="flex gap-4 mb-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="imageSource" value="upload" checked onchange="toggleImageSource()" class="mr-2">
                        <span class="text-sm text-white">Upload File</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="imageSource" value="url" onchange="toggleImageSource()" class="mr-2">
                        <span class="text-sm text-white">Image URL</span>
                    </label>
                </div>

                <!-- File Upload -->
                <div id="uploadSection">
                    <input type="file" name="logo" id="brandLogo" accept="image/*" onchange="previewImageFile(event)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- URL Input -->
                <div id="urlSection" class="hidden">
                    <input type="url" name="logo_url" id="brandLogoUrl" placeholder="https://example.com/image.jpg" onchange="previewImageUrl()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="hidden mt-4 relative">
                    <img id="previewImg" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg border-2 border-indigo-500 shadow-md">
                    <button type="button" onclick="cancelImage()" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors duration-150 flex items-center justify-center shadow-lg">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="brandSortOrder" value="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
            </div>

            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="brandStatus" value="1" checked class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-3 text-sm font-medium text-white">Active Brand</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-150">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Brand
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full shadow-2xl transform transition-all duration-300">
        <div class="p-6">
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-gradient-to-br from-red-500 to-red-600 rounded-full mb-4">
                <i class="fas fa-trash-alt text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white text-center mb-2" id="deleteModalTitle">Delete Brand?</h3>
            <p class="text-gray-100 text-center mb-6">
                Are you sure you want to delete "<span id="deleteItemName" class="font-semibold text-white"></span>"? This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-gray-200 text-white rounded-lg hover:bg-gray-300 transition-colors duration-150 font-semibold">
                    Cancel
                </button>
                <button onclick="executeSoftDelete()" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-150 font-semibold">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let editingBrandId = null;
let deleteTargetId = null;

function toggleImageSource() {
    const source = document.querySelector('input[name="imageSource"]:checked').value;
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');

    if (source === 'upload') {
        uploadSection.classList.remove('hidden');
        urlSection.classList.add('hidden');
        document.getElementById('brandLogoUrl').value = '';
    } else {
        uploadSection.classList.add('hidden');
        urlSection.classList.remove('hidden');
        document.getElementById('brandLogo').value = '';
    }
    document.getElementById('imagePreview').classList.add('hidden');
}

function openAddModal() {
    editingBrandId = null;
    document.getElementById('modalTitle').textContent = 'Add Brand';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('brandForm').reset();
    document.getElementById('brandStatus').checked = true;
    document.querySelector('input[value="upload"]').checked = true;
    toggleImageSource();
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('brandModal').classList.remove('hidden');
}

function editBrand(brand) {
    editingBrandId = brand.id;
    document.getElementById('modalTitle').textContent = 'Edit Brand';
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('parentBrandId').value = brand.parent_brand_id || '';
    document.getElementById('brandName').value = brand.name;
    document.getElementById('brandDescription').value = brand.description || '';
    document.getElementById('brandSortOrder').value = brand.sort_order;
    document.getElementById('brandStatus').checked = brand.is_active;

    // Show current logo if exists
    if (brand.logo) {
        const isUrl = brand.logo.startsWith('http');
        const imgSrc = isUrl ? brand.logo : `/storage/${brand.logo}`;
        document.getElementById('previewImg').src = imgSrc;
        document.getElementById('imagePreview').classList.remove('hidden');
    } else {
        document.getElementById('imagePreview').classList.add('hidden');
    }

    document.getElementById('brandModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('brandModal').classList.add('hidden');
}

function previewImageFile(event) {
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

function previewImageUrl() {
    const url = document.getElementById('brandLogoUrl').value;
    if (url) {
        document.getElementById('previewImg').src = url;
        document.getElementById('imagePreview').classList.remove('hidden');
    }
}

function cancelImage() {
    document.getElementById('brandLogo').value = '';
    document.getElementById('brandLogoUrl').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}

function confirmDelete(brandId, brandName) {
    deleteTargetId = brandId;
    document.getElementById('deleteItemName').textContent = brandName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteTargetId = null;
}

function executeSoftDelete() {
    if (!deleteTargetId) return;

    fetch(`/exporter/brands/${deleteTargetId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        closeDeleteModal();
        if (data.success) {
            showToast(data.message, 'success');
            document.getElementById(`brand-row-${deleteTargetId}`).remove();

            // Check if table is empty
            const tbody = document.getElementById('brandsTableBody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <i class="fas fa-copyright text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-200 text-lg">No brands found. Click "Add Brand" to create one.</p>
                        </td>
                    </tr>
                `;
            }
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        closeDeleteModal();
        showToast('An error occurred while deleting the brand', 'error');
        console.error('Error:', error);
    });
}

// Handle form submission with AJAX
document.getElementById('brandForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const url = editingBrandId ? `/exporter/brands/${editingBrandId}` : '{{ route('exporter.brands.store') }}';

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'An error occurred', 'error');
        }
    })
    .catch(error => {
        showToast('An error occurred while saving the brand', 'error');
        console.error('Error:', error);
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.id === 'brandModal') {
        closeModal();
    }
    if (event.target.id === 'deleteModal') {
        closeDeleteModal();
    }
}
</script>
@endsection
