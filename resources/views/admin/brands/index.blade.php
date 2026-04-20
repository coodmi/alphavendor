@extends('layouts.dashboard')

@section('title', 'Brands Management')
@section('page-title', 'Brands')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Brands Management</h2>
        <p class="text-gray-200 text-sm mt-1">Manage product brands shown on the website</p>
    </div>
    <button onclick="openAddModal()"
        class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
        <i class="fas fa-plus"></i> Add Brand
    </button>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-lg flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-4 text-left font-semibold text-gray-100">Logo</th>
                <th class="px-5 py-4 text-left font-semibold text-gray-100">Brand Name</th>
                <th class="px-5 py-4 text-left font-semibold text-gray-100">Products</th>
                <th class="px-5 py-4 text-left font-semibold text-gray-100">Status</th>
                <th class="px-5 py-4 text-left font-semibold text-gray-100">Sort</th>
                <th class="px-5 py-4 text-center font-semibold text-gray-100">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($brands as $brand)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                            class="w-12 h-12 object-contain rounded-lg border border-gray-200 bg-gray-50 p-1">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-sm">{{ strtoupper(substr($brand->name,0,2)) }}</span>
                        </div>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <p class="font-semibold text-white">{{ $brand->name }}</p>
                    <p class="text-gray-400 text-xs mt-0.5">{{ $brand->description ? Str::limit($brand->description, 55) : '—' }}</p>
                </td>
                <td class="px-5 py-4">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                        {{ $brand->products_count }} Products
                    </span>
                </td>
                <td class="px-5 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $brand->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-gray-100">{{ $brand->sort_order }}</td>
                <td class="px-5 py-4 text-center">
                    <button onclick='editBrand(@json($brand))'
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-medium transition mr-1">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button onclick="confirmDelete({{ $brand->id }}, '{{ addslashes($brand->name) }}')"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-medium transition">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                    <i class="fas fa-tag text-5xl mb-3 block opacity-30"></i>
                    No brands yet. Click "Add Brand" to create one.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add/Edit Modal -->
<div id="brandModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-white" id="modalTitle">Add Brand</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-100 text-2xl leading-none">&times;</button>
        </div>

        <form id="brandForm" method="POST" enctype="multipart/form-data" action="{{ route('admin.brands.store') }}" class="px-6 py-5 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            @if($errors->any())
            <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Brand Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="brandName" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent outline-none text-sm"
                    placeholder="e.g. Nike">
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Description</label>
                <textarea name="description" id="brandDescription" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent outline-none text-sm resize-none"
                    placeholder="Short brand description..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Brand Logo</label>
                <input type="file" name="logo" id="brandLogo" accept="image/*" onchange="previewImage(event)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-medium">
                <div id="imagePreview" class="hidden mt-3 relative inline-block">
                    <img id="previewImg" src="" alt="Preview" class="h-28 w-28 object-contain rounded-lg border border-gray-200 bg-gray-50 p-1">
                    <button type="button" onclick="cancelImage()"
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" id="brandSortOrder" value="0" min="0"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 outline-none text-sm">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" id="brandStatus" value="1" checked
                            class="w-4 h-4 accent-indigo-600">
                        <span class="text-sm font-semibold text-white">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeModal()"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-white rounded-lg font-medium text-sm transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-sm transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Brand
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form (hidden) -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Brand';
    document.getElementById('brandForm').action = '{{ route('admin.brands.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('brandForm').reset();
    document.getElementById('brandStatus').checked = true;
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('brandModal').classList.remove('hidden');
}

function editBrand(brand) {
    document.getElementById('modalTitle').textContent = 'Edit Brand';
    document.getElementById('brandForm').action = `/admin/brands/${brand.id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('brandName').value = brand.name;
    document.getElementById('brandDescription').value = brand.description || '';
    document.getElementById('brandSortOrder').value = brand.sort_order;
    document.getElementById('brandStatus').checked = !!brand.is_active;

    if (brand.logo) {
        document.getElementById('previewImg').src = `/storage/${brand.logo}`;
        document.getElementById('imagePreview').classList.remove('hidden');
    } else {
        document.getElementById('imagePreview').classList.add('hidden');
    }

    document.getElementById('brandModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('brandModal').classList.add('hidden');
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function cancelImage() {
    document.getElementById('brandLogo').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}

function confirmDelete(id, name) {
    if (confirm(`Delete "${name}"? This cannot be undone.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/brands/${id}`;
        form.submit();
    }
}

document.getElementById('brandModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Re-open modal if there are validation errors
@if($errors->any())
    document.getElementById('brandModal').classList.remove('hidden');
@endif
</script>
@endsection
