@extends('layouts.dashboard')

@section('title', 'Products Management')
@section('page-title', 'Products')

@section('sidebar-menu')
    @include('dashboards.partials.vendor-portal-sidebar')
@endsection

@php
    $portalPrefix = $portalPrefix ?? \App\Support\VendorPortal::routePrefix();
@endphp

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Products Management</h2>
            <p class="text-gray-600">Manage your import products</p>
        </div>
        <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">MOQ</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Certifications</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="productsTableBody" class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                <tr id="product-row-{{ $product->id }}" class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->image)
                            @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-14 h-14 object-cover rounded-lg shadow-sm">
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-14 h-14 object-cover rounded-lg shadow-sm">
                            @endif
                        @else
                            <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-xl"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $product->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $product->brand->name ?? 'No Brand' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-semibold text-gray-900">{{ currency($product->price) }}</div>
                        @if($product->old_price)
                            <div class="text-sm text-gray-500 line-through">{{ currency($product->old_price) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $product->minimum_order ?? 1 }} units
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->certifications && count($product->certifications) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->certifications as $cert)
                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-medium rounded bg-green-100 text-green-800">
                                        <i class="fas fa-certificate mr-1"></i>
                                        {{ strtoupper(explode('_', $cert)[0]) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-gray-400">None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : ($product->status === 'out_of_stock' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <button type="button" data-product='@json($product)' onclick="editProductFromData(this)" class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-150 mr-2">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button type="button" onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-150">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center">
                        <i class="fas fa-box text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No products found. Click "Add Product" to create one.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Add Product</h3>
            <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form id="productForm" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            {{-- 1. Product Name, SKU --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" id="productName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SKU *</label>
                    <input type="text" name="sku" id="productSku" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            {{-- 2. Category, Brand --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                    <select name="category_id" id="productCategory" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Brand <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <select name="brand_id" id="productBrand" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        <option value="">Select Brand (Optional)</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 3. Price, Old Price, Stock, Status --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" id="productPrice" step="0.01" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Old Price</label>
                    <input type="number" name="old_price" id="productOldPrice" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock" id="productStock" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select name="status" id="productStatus" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>

            {{-- 4. Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="productDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"></textarea>
            </div>

            {{-- 5. SEO / Meta --}}
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 space-y-4">
                <h4 class="text-sm font-bold text-gray-600 uppercase tracking-wide">
                    <i class="fas fa-search text-indigo-400 mr-1"></i> SEO / Meta
                </h4>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="meta_title" id="productMetaTitle"
                           placeholder="SEO title for this product"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Meta Keywords <span class="text-gray-400 font-normal">(Optional)</span>
                        <span class="text-xs text-gray-400 font-normal ml-1">(minimum 5, comma separated)</span>
                    </label>
                    <input type="text" name="meta_keywords" id="productMetaKeywords"
                           placeholder="keyword1, keyword2, keyword3, keyword4, keyword5"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"
                           oninput="validateKeywords(this)">
                    <p id="keywordHint" class="text-xs text-gray-400 mt-1">Separate keywords with commas (minimum 5 if provided)</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <textarea name="meta_description" id="productMetaDescription" rows="2"
                              placeholder="Short description for search engines (max 160 chars)"
                              maxlength="500"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white resize-none"></textarea>
                </div>
            </div>

            {{-- 6. Product Images --}}
            @include('partials.vendor-product-gallery', ['galleryMin' => 5, 'galleryMax' => 10])

            {{-- 7. Product Video --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Product Video URL <span class="text-gray-400 font-normal">(Optional)</span></label>
                <input type="url" name="video" id="productVideo"
                       placeholder="https://youtube.com/watch?v=..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">Enter a YouTube or other video URL</p>
            </div>

            {{-- 8. Featured, Badge, Special Offer --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="flex items-end">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_featured" id="productFeatured" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-sm font-medium text-gray-700">Featured Product</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Badge <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="text" name="badge" id="productBadge" placeholder="e.g., New, Sale, Hot" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Special Offer <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <select name="special_offer_id" id="productOffer" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">No Special Offer</option>
                        @forelse($offers as $offer)
                            <option value="{{ $offer->id }}">{{ $offer->name }}</option>
                        @empty
                            <option value="" disabled>No active offers — contact admin</option>
                        @endforelse
                    </select>
                </div>
            </div>

            {{-- 9. Product Attributes --}}
            @include('partials.product-attributes-form')

            {{-- 10. MOQ, Certifications, Importer Rating, Supplier Location --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Min Order (MOQ)</label>
                    <input type="number" name="minimum_order" id="productMinOrder" min="1" value="1"
                           placeholder="e.g. 10"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier Location</label>
                    <input type="text" name="supplier_location" id="productLocation" placeholder="e.g., Shanghai, China" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Import Certifications</label>
                @if($certifications && $certifications->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($certifications as $cert)
                            <label class="flex items-center cursor-pointer p-3 border border-gray-300 rounded-lg hover:bg-indigo-50 transition-colors duration-150">
                                <input type="checkbox" name="certifications[]" value="cert_{{ $cert->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $cert->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                        <p class="text-sm text-teal-800">
                            <i class="fas fa-info-circle mr-2"></i>No certifications available.
                            <a href="{{ route($portalPrefix.'.certifications') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline">Create certifications</a> first to assign them to products.
                        </p>
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Importer Rating</label>
                <div class="flex items-center gap-4">
                    <input type="number" name="exporter_rating" id="exporterRating" min="0" max="5" step="0.1" placeholder="0.0" class="w-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    <span class="text-sm text-gray-500">Rating out of 5 stars (e.g., 4.5)</span>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-150">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Product
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
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Delete Product?</h3>
            <p class="text-gray-600 text-center mb-6">
                Are you sure you want to delete "<span id="deleteItemName" class="font-semibold text-gray-900"></span>"? This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-150 font-semibold">
                    Cancel
                </button>
                <button type="button" onclick="executeSoftDelete()" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-150 font-semibold">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let editingProductId = null;
let deleteTargetId = null;

function openAddModal() {
    editingProductId = null;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('productForm').reset();
    document.getElementById('productMinOrder').value = '1';
    resetProductAttributes();
    if (typeof resetProductGallery === 'function') resetProductGallery();
    const hint = document.getElementById('keywordHint');
    if (hint) {
        hint.textContent = 'Separate keywords with commas (minimum 5 if provided)';
        hint.className = 'text-xs text-gray-400 mt-1';
    }
    document.querySelectorAll('input[name="certifications[]"]').forEach(cb => { cb.checked = false; });
    document.getElementById('productModal').classList.remove('hidden');
}

function editProductFromData(button) {
    const product = JSON.parse(button.getAttribute('data-product'));
    editProduct(product);
}

function editProduct(product) {
    editingProductId = product.id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('productName').value = product.name;
    document.getElementById('productSku').value = product.sku;
    document.getElementById('productCategory').value = product.category_id;
    document.getElementById('productBrand').value = product.brand_id || '';
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productOldPrice').value = product.old_price || '';
    document.getElementById('productStock').value = product.stock;
    document.getElementById('productStatus').value = product.status;
    document.getElementById('productDescription').value = product.description || '';
    document.getElementById('productBadge').value = product.badge || '';
    document.getElementById('productFeatured').checked = !!product.is_featured;
    document.getElementById('productMinOrder').value = product.minimum_order || 1;
    document.getElementById('productLocation').value = product.supplier_location || '';
    document.getElementById('exporterRating').value = product.exporter_rating || '';

    if (document.getElementById('productOffer')) {
        document.getElementById('productOffer').value = product.special_offer_id || '';
    }
    if (document.getElementById('productVideo')) {
        document.getElementById('productVideo').value = product.video || '';
    }
    if (document.getElementById('productMetaTitle')) {
        document.getElementById('productMetaTitle').value = product.meta_title || '';
    }
    if (document.getElementById('productMetaKeywords')) {
        document.getElementById('productMetaKeywords').value = product.meta_keywords || '';
        validateKeywords(document.getElementById('productMetaKeywords'));
    }
    if (document.getElementById('productMetaDescription')) {
        document.getElementById('productMetaDescription').value = product.meta_description || '';
    }

    document.querySelectorAll('input[name="certifications[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    if (product.certifications && Array.isArray(product.certifications)) {
        product.certifications.forEach(cert => {
            const checkbox = document.querySelector(`input[name="certifications[]"][value="${cert}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }

    resetProductAttributes();
    document.querySelectorAll('.attr-field').forEach(field => {
        const attrId = field.dataset.attrId;
        const attrData = product.attributes ? product.attributes.find(a => a.id == attrId) : null;
        const val = attrData ? (attrData.pivot ? attrData.pivot.value : '') : '';

        if (field.type === 'hidden') {
            if (document.getElementById('swatches-' + attrId)) {
                document.getElementById('swatches-' + attrId).innerHTML = '';
                field.value = '';
                if (val) initColorSwatches(attrId, val);
            } else if (document.getElementById('selectValue-' + attrId) === field) {
                initSelectOptions(attrId, val);
            } else if (document.getElementById('textValue-' + attrId) === field) {
                initTextTags(attrId, val);
            } else {
                field.value = val;
            }
        } else {
            field.value = val;
        }
    });

    const galleryInput = document.getElementById('productGalleryImages');
    if (galleryInput) galleryInput.removeAttribute('required');
    if (typeof renderExistingGallery === 'function') renderExistingGallery(product);

    document.getElementById('productModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('productModal').classList.add('hidden');
    if (typeof resetProductGallery === 'function') resetProductGallery();
}

function resetProductAttributes() {
    document.querySelectorAll('.attr-field').forEach(field => {
        const attrId = field.dataset.attrId;
        if (!attrId) return;
        field.value = '';
        const swatches = document.getElementById('swatches-' + attrId);
        if (swatches) swatches.innerHTML = '';
        const textTags = document.getElementById('text-tags-' + attrId);
        if (textTags) textTags.innerHTML = '';
        document.querySelectorAll(`[id^="opt-${attrId}-"]`).forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
            btn.classList.add('bg-white', 'border-gray-300');
        });
    });
}

function confirmDelete(productId, productName) {
    deleteTargetId = productId;
    document.getElementById('deleteItemName').textContent = productName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteTargetId = null;
}

function executeSoftDelete() {
    if (!deleteTargetId) return;

    fetch(`{{ url($portalPrefix.'/products') }}/${deleteTargetId}`, {
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
            document.getElementById(`product-row-${deleteTargetId}`).remove();

            const tbody = document.getElementById('productsTableBody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <i class="fas fa-box text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No products found. Click "Add Product" to create one.</p>
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
        showToast('An error occurred while deleting the product', 'error');
        console.error('Error:', error);
    });
}

document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const kwField = document.getElementById('productMetaKeywords');
    if (kwField && kwField.value.trim()) {
        const kws = kwField.value.split(',').map(k => k.trim()).filter(k => k.length > 0);
        if (kws.length < 5) {
            showToast('Please enter at least 5 meta keywords separated by commas', 'error');
            kwField.focus();
            return;
        }
    }

    if (typeof validateProductGallery === 'function' && !validateProductGallery()) {
        return;
    }

    const formData = new FormData(this);
    const url = editingProductId
        ? `{{ url($portalPrefix.'/products') }}/${editingProductId}`
        : '{{ route($portalPrefix . ".products.store") }}';

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            const message = data.message
                || (data.errors && Object.values(data.errors).flat().join(' '))
                || 'An error occurred';
            throw new Error(message);
        }
        return data;
    })
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
        showToast(error.message || 'An error occurred while saving the product', 'error');
        console.error('Error:', error);
    });
});

window.onclick = function(event) {
    if (event.target.id === 'productModal') closeModal();
    if (event.target.id === 'deleteModal') closeDeleteModal();
};

function validateKeywords(input) {
    const kws = input.value.split(',').map(k => k.trim()).filter(k => k.length > 0);
    const hint = document.getElementById('keywordHint');
    if (!hint) return;
    if (!input.value.trim()) {
        hint.textContent = 'Separate keywords with commas (minimum 5 if provided)';
        hint.className = 'text-xs text-gray-400 mt-1';
        return;
    }
    if (kws.length >= 5) {
        hint.textContent = '✓ ' + kws.length + ' keywords added';
        hint.className = 'text-xs text-green-600 mt-1';
    } else {
        hint.textContent = kws.length + '/5 keywords — minimum 5 required';
        hint.className = 'text-xs text-orange-500 mt-1';
    }
}

function addColor(attrId) {
    const hex = document.getElementById('colorInput-' + attrId).value;
    const hidden = document.getElementById('colorValue-' + attrId);
    const swatches = document.getElementById('swatches-' + attrId);

    const existing = hidden.value ? hidden.value.split(',') : [];
    if (existing.includes(hex)) return;
    existing.push(hex);
    hidden.value = existing.join(',');

    const swatch = document.createElement('div');
    swatch.className = 'relative group';
    swatch.innerHTML = `
        <div style="width:32px;height:32px;border-radius:6px;background:${hex};border:2px solid #e5e7eb;cursor:default;" title="${hex}"></div>
        <button type="button" onclick="removeColor(${attrId},'${hex}')"
                style="position:absolute;top:-6px;right:-6px;width:16px;height:16px;border-radius:50%;background:#ef4444;color:white;border:none;cursor:pointer;font-size:10px;line-height:1;display:flex;align-items:center;justify-content:center;">×</button>
    `;
    swatch.dataset.color = hex;
    swatches.appendChild(swatch);
}

function removeColor(attrId, hex) {
    const hidden = document.getElementById('colorValue-' + attrId);
    const swatches = document.getElementById('swatches-' + attrId);
    hidden.value = hidden.value.split(',').filter(c => c !== hex).join(',');
    swatches.querySelectorAll('[data-color]').forEach(el => {
        if (el.dataset.color === hex) el.remove();
    });
}

function initColorSwatches(attrId, value) {
    if (!value) return;
    value.split(',').forEach(hex => {
        if (!hex) return;
        document.getElementById('colorInput-' + attrId).value = hex.trim();
        addColor(attrId);
    });
}

function toggleSelectOption(attrId, value) {
    const hidden = document.getElementById('selectValue-' + attrId);
    const slug = value.toLowerCase().replace(/[^a-z0-9]/g, '-');
    const btn = document.getElementById('opt-' + attrId + '-' + slug);
    const existing = hidden.value ? hidden.value.split(',') : [];

    if (existing.includes(value)) {
        hidden.value = existing.filter(v => v !== value).join(',');
        if (btn) {
            btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
            btn.classList.add('bg-white', 'border-gray-300');
        }
    } else {
        existing.push(value);
        hidden.value = existing.join(',');
        if (btn) {
            btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
            btn.classList.remove('bg-white', 'border-gray-300');
        }
    }
}

function initSelectOptions(attrId, value) {
    if (!value) return;
    document.querySelectorAll(`[id^="opt-${attrId}-"]`).forEach(btn => {
        btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
        btn.classList.add('bg-white', 'border-gray-300');
    });
    document.getElementById('selectValue-' + attrId).value = '';
    value.split(',').forEach(v => {
        if (v.trim()) toggleSelectOption(attrId, v.trim());
    });
}

function addTextTag(attrId) {
    const input = document.getElementById('textInput-' + attrId);
    const val = input.value.trim();
    if (!val) return;
    const hidden = document.getElementById('textValue-' + attrId);
    const tags = document.getElementById('text-tags-' + attrId);
    const existing = hidden.value ? hidden.value.split(',') : [];
    if (existing.includes(val)) {
        input.value = '';
        return;
    }
    existing.push(val);
    hidden.value = existing.join(',');

    const tag = document.createElement('span');
    tag.className = 'inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full';
    tag.dataset.val = val;
    tag.innerHTML = `${val} <button type="button" onclick="removeTextTag(${attrId},'${val.replace(/'/g, "\\'")}',this.parentElement)" class="text-indigo-400 hover:text-red-500 font-bold leading-none">×</button>`;
    tags.appendChild(tag);
    input.value = '';
}

function removeTextTag(attrId, val, tagEl) {
    const hidden = document.getElementById('textValue-' + attrId);
    hidden.value = hidden.value.split(',').filter(v => v !== val).join(',');
    tagEl.remove();
}

function initTextTags(attrId, value) {
    if (!value) return;
    document.getElementById('text-tags-' + attrId).innerHTML = '';
    document.getElementById('textValue-' + attrId).value = '';
    value.split(',').forEach(v => {
        if (!v.trim()) return;
        document.getElementById('textInput-' + attrId).value = v.trim();
        addTextTag(attrId);
    });
}
</script>
@endsection
