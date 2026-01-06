@extends('layouts.dashboard')

@section('title', 'Products Management')
@section('page-title', 'Products')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.products') }}" class="menu-item active">
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
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Products Management</h2>
            <p style="color: #7f8c8d;">Manage all products in your store</p>
        </div>
        <button onclick="openAddModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>
</div>

<div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Image</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Product</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Category</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Vendor</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Price</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Stock</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                    <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-box" style="color: #95a5a6;"></i>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ $product->name }}</strong><br>
                        <small style="color: #7f8c8d;">SKU: {{ $product->sku }}</small>
                        @if($product->is_featured)
                            <span style="display: inline-block; padding: 2px 8px; background: #ffd700; color: #000; border-radius: 8px; font-size: 11px; margin-left: 5px;">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                            {{ $product->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        {{ $product->vendor->name ?? 'N/A' }}
                    </td>
                    <td style="padding: 12px;">
                        <strong style="color: #27ae60;">${{ number_format($product->price, 2) }}</strong>
                        @if($product->old_price)
                            <br><small style="text-decoration: line-through; color: #95a5a6;">${{ number_format($product->old_price, 2) }}</small>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 12px; background: {{ $product->stock > 10 ? '#d4edda' : ($product->stock > 0 ? '#fff3cd' : '#f8d7da') }}; color: {{ $product->stock > 10 ? '#155724' : ($product->stock > 0 ? '#856404' : '#721c24') }}; border-radius: 12px; font-size: 12px;">
                            {{ $product->stock }} units
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        @if($product->status === 'active')
                            <span style="padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 12px;">Active</span>
                        @elseif($product->status === 'out_of_stock')
                            <span style="padding: 4px 12px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 12px;">Out of Stock</span>
                        @else
                            <span style="padding: 4px 12px; background: #d1ecf1; color: #0c5460; border-radius: 12px; font-size: 12px;">Inactive</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <button onclick='editProduct(@json($product))' style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 40px; text-align: center; color: #7f8c8d;">
                        <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                        No products found. Click "Add Product" to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="productModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 25px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #2c3e50;" id="modalTitle">Add Product</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="productForm" method="POST" enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Product Name *</label>
                    <input type="text" name="name" id="productName" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">SKU *</label>
                    <input type="text" name="sku" id="productSku" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Category *</label>
                    <select name="category_id" id="productCategory" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Vendor *</label>
                    <select name="vendor_id" id="productVendor" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Price ($) *</label>
                    <input type="number" name="price" id="productPrice" required step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Old Price ($)</label>
                    <input type="number" name="old_price" id="productOldPrice" step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Stock Quantity *</label>
                    <input type="number" name="stock" id="productStock" required min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Status *</label>
                    <select name="status" id="productStatus" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                <textarea name="description" id="productDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Product Image <span id="imageRequiredLabel">*</span></label>
                <input type="file" name="image" id="productImage" accept="image/*" onchange="previewImage(event)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                <div id="imagePreview" style="display: none; margin-top: 15px; position: relative;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: 250px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db;">
                    <button type="button" onclick="cancelImage()" style="position: absolute; top: 10px; right: 10px; width: 35px; height: 35px; border-radius: 50%; background: #e74c3c; color: white; border: none; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_featured" id="productFeatured" value="1" style="margin-right: 8px; width: 18px; height: 18px;">
                    <span style="color: #2c3e50;">Featured Product</span>
                </label>

                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Badge (Optional)</label>
                    <input type="text" name="badge" id="productBadge" placeholder="e.g., New, Sale, Hot" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeModal()" style="padding: 12px 24px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-save"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; width: 90%; max-width: 400px; padding: 30px; text-align: center;">
        <div style="width: 60px; height: 60px; background: #fee; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 30px; color: #e74c3c;"></i>
        </div>
        <h3 style="margin: 0 0 10px 0; color: #2c3e50;">Delete Product?</h3>
        <p style="color: #7f8c8d; margin-bottom: 25px;">Are you sure you want to delete "<strong id="deleteProductName"></strong>"? This action cannot be undone.</p>
        <form id="deleteForm" method="POST" style="display: flex; gap: 10px; justify-content: center;">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                Cancel
            </button>
            <button type="submit" style="padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 6px; cursor: pointer;">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

<script>
let isEditMode = false;

function openAddModal() {
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').action = '{{ route('admin.products.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('productForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imageRequiredLabel').textContent = '*';
    document.getElementById('productImage').required = true;
    document.getElementById('productModal').style.display = 'flex';
}

function editProduct(product) {
    isEditMode = true;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('productForm').action = `/admin/products/${product.id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('productName').value = product.name;
    document.getElementById('productSku').value = product.sku;
    document.getElementById('productCategory').value = product.category_id;
    document.getElementById('productVendor').value = product.vendor_id;
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productOldPrice').value = product.old_price || '';
    document.getElementById('productStock').value = product.stock;
    document.getElementById('productStatus').value = product.status;
    document.getElementById('productDescription').value = product.description || '';
    document.getElementById('productFeatured').checked = product.is_featured;
    document.getElementById('productBadge').value = product.badge || '';
    document.getElementById('imageRequiredLabel').textContent = '';
    document.getElementById('productImage').required = false;

    if (product.image) {
        document.getElementById('imagePreview').style.display = 'block';
        document.getElementById('previewImg').src = `/storage/${product.image}`;
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }

    document.getElementById('productModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2097152) {
            showToast('File size must be less than 2MB', 'error');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function cancelImage() {
    if (!isEditMode) {
        document.getElementById('productImage').value = '';
        document.getElementById('imagePreview').style.display = 'none';
    } else {
        document.getElementById('productImage').value = '';
        document.getElementById('imagePreview').style.display = 'none';
    }
}

function confirmDelete(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/products/${id}`;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modals on outside click
document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endsection
