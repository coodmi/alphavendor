@extends('layouts.dashboard')

@section('title', 'Products Management')
@section('page-title', 'Products')

@section('sidebar-menu')
    @include('dashboards.partials.wholesaler-sidebar')
@endsection

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Products Management</h2>
            <p class="text-gray-600">Manage your products</p>
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stock</th>
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
                        <div class="font-semibold text-gray-900"> {{ currency($product->price) }}</div>
                        @if($product->old_price)
                            <div class="text-sm text-gray-500 line-through"> {{ currency($product->old_price) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->stock }} units
                        </span>
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
                        <button onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" class="inline-flex items-center px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-150">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
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
            <button onclick="closeModal()" class="text-white hover:text-gray-200 transition-colors duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form id="productForm" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

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

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="productDescription" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"></textarea>
            </div>

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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Brand</label>
                    <select name="brand_id" id="productBrand" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        <option value="">Select Brand (Optional)</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Product Image</label>

                <!-- Image Source Toggle -->
                <div class="flex gap-4 mb-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="imageSource" value="upload" checked onchange="toggleImageSource()" class="mr-2">
                        <span class="text-sm text-gray-700">Upload File</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="imageSource" value="url" onchange="toggleImageSource()" class="mr-2">
                        <span class="text-sm text-gray-700">Image URL</span>
                    </label>
                </div>

                <!-- File Upload -->
                <div id="uploadSection">
                    <input type="file" name="image" id="productImage" accept="image/*" onchange="previewImageFile(event)" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- URL Input -->
                <div id="urlSection" class="hidden">
                    <input type="url" name="image_url" id="productImageUrl" placeholder="https://example.com/image.jpg" onchange="previewImageUrl()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="hidden mt-4 relative">
                    <img id="previewImg" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg border-2 border-indigo-500 shadow-md">
                    <button type="button" onclick="cancelImage()" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors duration-150 flex items-center justify-center shadow-lg">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            @include('partials.vendor-product-gallery')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" id="productPrice" step="0.01" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Old Price</label>
                    <input type="number" name="old_price" id="productOldPrice" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stock *</label>
                    <input type="number" name="stock" id="productStock" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select name="status" id="productStatus" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Badge</label>
                    <input type="text" name="badge" id="productBadge" placeholder="e.g., New, Sale, Hot" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>

            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" id="productFeatured" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="ml-3 text-sm font-medium text-gray-700">Featured Product</span>
                </label>
            </div>

            {{-- Special Offer --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

            @include('partials.product-shipping-charge-fields')

            {{-- MOQ & Supplier Location --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Min Order (MOQ) *</label>
                    <input type="number" name="minimum_order" id="productMOQ" min="1" required
                           placeholder="e.g. 10"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier Location *</label>
                    <select name="supplier_location_id" id="productSupplierLocation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select location</option>
                        @foreach($supplierLocations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Product Video --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Product Video URL <span class="text-gray-400 font-normal">(Optional)</span></label>
                <input type="url" name="video" id="productVideo"
                       placeholder="https://youtube.com/watch?v=..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-400 mt-1">Enter a YouTube or other video URL</p>
            </div>

            {{-- SEO / Meta Fields --}}
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 space-y-4">
                <h4 class="text-sm font-bold text-gray-600 uppercase tracking-wide">
                    <i class="fas fa-search text-indigo-400 mr-1"></i> SEO / Meta (Optional)
                </h4>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="productMetaTitle"
                           placeholder="SEO title for this product"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Meta Keywords
                        <span class="text-xs text-gray-400 font-normal ml-1">(minimum 5, comma separated)</span>
                    </label>
                    <input type="text" name="meta_keywords" id="productMetaKeywords"
                           placeholder="keyword1, keyword2, keyword3, keyword4, keyword5"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"
                           oninput="validateKeywords(this)">
                    <p id="keywordHint" class="text-xs text-gray-400 mt-1">Separate keywords with commas (minimum 5)</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" id="productMetaDescription" rows="2"
                              placeholder="Short description for search engines (max 160 chars)"
                              maxlength="500"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white resize-none"></textarea>
                </div>
            </div>

            @if(isset($attributes) && $attributes->count() > 0)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Product Attributes</label>
                <div class="space-y-4 border border-gray-200 rounded-xl p-4 bg-gray-50">
                    @foreach($attributes as $attr)
                    <div class="bg-white rounded-lg border border-gray-100 p-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                            {{ $attr->name }}
                            @if($attr->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
                        </label>

                        @if($attr->type === 'select' && $attr->options)
                        {{-- Multi-select tag picker --}}
                        <div id="select-picker-{{ $attr->id }}">
                            <div class="flex flex-wrap gap-2 mb-2" id="selected-tags-{{ $attr->id }}"></div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($attr->options as $opt)
                                <button type="button"
                                        onclick="toggleSelectOption({{ $attr->id }}, '{{ $opt }}')"
                                        id="opt-{{ $attr->id }}-{{ Str::slug($opt) }}"
                                        class="px-3 py-1 text-xs font-medium border border-gray-300 rounded-full bg-white hover:border-indigo-500 hover:text-indigo-600 transition-all select-option-btn">
                                    {{ $opt }}
                                </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="attributes[{{ $attr->id }}]"
                                   id="selectValue-{{ $attr->id }}" class="attr-field" data-attr-id="{{ $attr->id }}">
                        </div>

                        @elseif($attr->type === 'color')
                        {{-- Multi-color picker --}}
                        <div id="color-picker-{{ $attr->id }}">
                            <div class="flex flex-wrap gap-2 mb-2" id="swatches-{{ $attr->id }}"></div>
                            <div class="flex items-center gap-2">
                                <input type="color" id="colorInput-{{ $attr->id }}" value="#000000"
                                       class="h-9 w-12 border border-gray-300 rounded-lg cursor-pointer">
                                <button type="button" onclick="addColor({{ $attr->id }})"
                                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                    + Add Color
                                </button>
                            </div>
                            <input type="hidden" name="attributes[{{ $attr->id }}]"
                                   id="colorValue-{{ $attr->id }}" class="attr-field" data-attr-id="{{ $attr->id }}">
                        </div>

                        @elseif($attr->type === 'number')
                        {{-- Single number --}}
                        <input type="number" name="attributes[{{ $attr->id }}]"
                               placeholder="Enter {{ $attr->name }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none attr-field"
                               data-attr-id="{{ $attr->id }}"
                               {{ $attr->is_required ? 'required' : '' }}>

                        @else
                        {{-- Multi-text tag input --}}
                        <div id="text-picker-{{ $attr->id }}">
                            <div class="flex flex-wrap gap-1.5 mb-2" id="text-tags-{{ $attr->id }}"></div>
                            <div class="flex gap-2">
                                <input type="text" id="textInput-{{ $attr->id }}"
                                       placeholder="Type and press Enter or +"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();addTextTag({{ $attr->id }});}">
                                <button type="button" onclick="addTextTag({{ $attr->id }})"
                                        class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">+</button>
                            </div>
                            <input type="hidden" name="attributes[{{ $attr->id }}]"
                                   id="textValue-{{ $attr->id }}" class="attr-field" data-attr-id="{{ $attr->id }}">
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

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
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2" id="deleteModalTitle">Delete Product?</h3>
            <p class="text-gray-600 text-center mb-6">
                Are you sure you want to delete "<span id="deleteItemName" class="font-semibold text-gray-900"></span>"? This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-150 font-semibold">
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
let editingProductId = null;
let deleteTargetId = null;

function toggleImageSource() {
    const source = document.querySelector('input[name="imageSource"]:checked').value;
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');

    if (source === 'upload') {
        uploadSection.classList.remove('hidden');
        urlSection.classList.add('hidden');
        document.getElementById('productImageUrl').value = '';
    } else {
        uploadSection.classList.add('hidden');
        urlSection.classList.remove('hidden');
        document.getElementById('productImage').value = '';
    }
    document.getElementById('imagePreview').classList.add('hidden');
}

function openAddModal() {
    editingProductId = null;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('productForm').reset();
    document.querySelector('input[value="upload"]').checked = true;
    toggleImageSource();
    document.getElementById('imagePreview').classList.add('hidden');
    if (typeof resetProductGallery === 'function') resetProductGallery();
    document.getElementById('productModal').classList.remove('hidden');
}

function editProduct(product) {
    editingProductId = product.id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('productName').value = product.name;
    document.getElementById('productSku').value = product.sku;
    document.getElementById('productDescription').value = product.description || '';
    document.getElementById('productCategory').value = product.category_id;
    document.getElementById('productBrand').value = product.brand_id || '';
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productOldPrice').value = product.old_price || '';
    document.getElementById('productStock').value = product.stock;
    document.getElementById('productStatus').value = product.status;
    document.getElementById('productBadge').value = product.badge || '';
    document.getElementById('productFeatured').checked = product.is_featured;
    // New fields
    if (document.getElementById('productOffer'))        document.getElementById('productOffer').value = product.special_offer_id || '';
    if (document.getElementById('productShippingInsideDhaka')) {
        document.getElementById('productShippingInsideDhaka').value = product.shipping_charge_inside_dhaka ?? '';
    }
    if (document.getElementById('productShippingOutsideDhaka')) {
        document.getElementById('productShippingOutsideDhaka').value = product.shipping_charge_outside_dhaka ?? '';
    }
    if (document.getElementById('productMOQ'))          document.getElementById('productMOQ').value = product.minimum_order || '';
    if (document.getElementById('productSupplierLocation')) document.getElementById('productSupplierLocation').value = product.supplier_location_id || '';
    const galleryInput = document.getElementById('productGalleryImages');
    if (galleryInput) galleryInput.removeAttribute('required');
    if (typeof renderExistingGallery === 'function') renderExistingGallery(product);
    if (document.getElementById('productVideo'))        document.getElementById('productVideo').value = product.video || '';
    if (document.getElementById('productMetaTitle'))    document.getElementById('productMetaTitle').value = product.meta_title || '';
    if (document.getElementById('productMetaKeywords')) document.getElementById('productMetaKeywords').value = product.meta_keywords || '';
    if (document.getElementById('productMetaDescription')) document.getElementById('productMetaDescription').value = product.meta_description || '';

    // Populate attribute values
    document.querySelectorAll('.attr-field').forEach(field => {
        const attrId = field.dataset.attrId;
        const attrData = product.attributes ? product.attributes.find(a => a.id == attrId) : null;
        const val = attrData ? (attrData.pivot ? attrData.pivot.value : '') : '';

        if (field.type === 'hidden') {
            if (document.getElementById('swatches-' + attrId)) {
                // Multi-color
                document.getElementById('swatches-' + attrId).innerHTML = '';
                field.value = '';
                if (val) initColorSwatches(attrId, val);
            } else if (document.getElementById('selectValue-' + attrId) === field) {
                // Multi-select options
                initSelectOptions(attrId, val);
            } else if (document.getElementById('textValue-' + attrId) === field) {
                // Multi-text tags
                initTextTags(attrId, val);
            } else {
                field.value = val;
            }
        } else {
            field.value = val;
        }
    });

    // Show current image if exists
    if (product.image) {
        const isUrl = product.image.startsWith('http');
        const imgSrc = isUrl ? product.image : `/storage/${product.image}`;
        document.getElementById('previewImg').src = imgSrc;
        document.getElementById('imagePreview').classList.remove('hidden');
    } else {
        document.getElementById('imagePreview').classList.add('hidden');
    }

    document.getElementById('productModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('productModal').classList.add('hidden');
    if (typeof resetProductGallery === 'function') resetProductGallery();
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
    const url = document.getElementById('productImageUrl').value;
    if (url) {
        document.getElementById('previewImg').src = url;
        document.getElementById('imagePreview').classList.remove('hidden');
    }
}

function cancelImage() {
    document.getElementById('productImage').value = '';
    document.getElementById('productImageUrl').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
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

    fetch(`/wholesaler/products/${deleteTargetId}`, {
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

            // Check if table is empty
            const tbody = document.getElementById('productsTableBody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
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

// Handle form submission with AJAX
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate meta keywords minimum 5
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
    const url = editingProductId ? `/wholesaler/products/${editingProductId}` : '{{ route('wholesaler.products.store') }}';

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => parseSellerProductResponse(response))
    .then(data => {
        showToast(data.message, 'success');
        closeModal();
        setTimeout(() => location.reload(), 800);
    })
    .catch(error => {
        showToast(error.message || 'An error occurred while saving the product', 'error');
        console.error('Error:', error);
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.id === 'productModal') {
        closeModal();
    }
    if (event.target.id === 'deleteModal') {
        closeDeleteModal();
    }
}

function validateKeywords(input) {
    const kws = input.value.split(',').map(k => k.trim()).filter(k => k.length > 0);
    const hint = document.getElementById('keywordHint');
    if (kws.length >= 5) {
        hint.textContent = '✓ ' + kws.length + ' keywords added';
        hint.className = 'text-xs text-green-600 mt-1';
    } else {
        hint.textContent = kws.length + '/5 keywords — minimum 5 required';
        hint.className = 'text-xs text-orange-500 mt-1';
    }
}

// ── Multi-Color Picker ──────────────────────────────────────────────────────
function addColor(attrId) {
    const hex = document.getElementById('colorInput-' + attrId).value;
    const hidden = document.getElementById('colorValue-' + attrId);
    const swatches = document.getElementById('swatches-' + attrId);

    // Avoid duplicates
    const existing = hidden.value ? hidden.value.split(',') : [];
    if (existing.includes(hex)) return;
    existing.push(hex);
    hidden.value = existing.join(',');

    // Render swatch
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
        document.getElementById('colorInput-' + attrId).value = hex;
        addColor(attrId);
    });
}

// ── Multi-Select Tag Picker ─────────────────────────────────────────────────
function toggleSelectOption(attrId, value) {
    const hidden = document.getElementById('selectValue-' + attrId);
    const btn = document.getElementById('opt-' + attrId + '-' + value.toLowerCase().replace(/[^a-z0-9]/g, '-'));
    const existing = hidden.value ? hidden.value.split(',') : [];

    if (existing.includes(value)) {
        // Deselect
        hidden.value = existing.filter(v => v !== value).join(',');
        if (btn) { btn.classList.remove('bg-indigo-600','text-white','border-indigo-600'); btn.classList.add('bg-white','border-gray-300'); }
    } else {
        // Select
        existing.push(value);
        hidden.value = existing.join(',');
        if (btn) { btn.classList.add('bg-indigo-600','text-white','border-indigo-600'); btn.classList.remove('bg-white','border-gray-300'); }
    }
}

function initSelectOptions(attrId, value) {
    if (!value) return;
    // Reset all buttons first
    document.querySelectorAll(`[id^="opt-${attrId}-"]`).forEach(btn => {
        btn.classList.remove('bg-indigo-600','text-white','border-indigo-600');
        btn.classList.add('bg-white','border-gray-300');
    });
    document.getElementById('selectValue-' + attrId).value = '';
    value.split(',').forEach(v => { if (v) toggleSelectOption(attrId, v.trim()); });
}

// ── Multi-Text Tag Input ────────────────────────────────────────────────────
function addTextTag(attrId) {
    const input = document.getElementById('textInput-' + attrId);
    const val = input.value.trim();
    if (!val) return;
    const hidden = document.getElementById('textValue-' + attrId);
    const tags = document.getElementById('text-tags-' + attrId);
    const existing = hidden.value ? hidden.value.split(',') : [];
    if (existing.includes(val)) { input.value = ''; return; }
    existing.push(val);
    hidden.value = existing.join(',');

    const tag = document.createElement('span');
    tag.className = 'inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full';
    tag.dataset.val = val;
    tag.innerHTML = `${val} <button type="button" onclick="removeTextTag(${attrId},'${val.replace(/'/g,"\\'")}',this.parentElement)" class="text-indigo-400 hover:text-red-500 font-bold leading-none">×</button>`;
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
