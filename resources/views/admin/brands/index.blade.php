@extends('layouts.dashboard')

@section('title', 'Brands Management')
@section('page-title', 'Brands')

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
        <a href="{{ route('admin.brands') }}" class="menu-item active">
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
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Brands Management</h2>
            <p style="color: #7f8c8d;">Manage product brands</p>
        </div>
        <button onclick="openAddModal()" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> Add Brand
        </button>
    </div>
</div>

<div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Logo</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Brand Name</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Products</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Status</th>
                    <th style="padding: 12px; text-align: left; color: #2c3e50;">Sort Order</th>
                    <th style="padding: 12px; text-align: center; color: #2c3e50;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-copyright" style="color: #95a5a6;"></i>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <strong>{{ $brand->name }}</strong><br>
                        <small style="color: #7f8c8d;">{{ $brand->description ? Str::limit($brand->description, 50) : 'No description' }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 12px; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 12px;">
                            {{ $brand->products_count ?? 0 }} Products
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 12px; background: {{ $brand->is_active ? '#d4edda' : '#f8d7da' }}; color: {{ $brand->is_active ? '#155724' : '#721c24' }}; border-radius: 12px; font-size: 12px;">
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="padding: 12px;">{{ $brand->sort_order }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <button onclick='editBrand(@json($brand))' style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="confirmDelete({{ $brand->id }}, '{{ $brand->name }}')" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #7f8c8d;">
                        <i class="fas fa-copyright" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i><br>
                        No brands found. Click "Add Brand" to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="brandModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 25px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #2c3e50;" id="modalTitle">Add Brand</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="brandForm" method="POST" enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Brand Name *</label>
                <input type="text" name="name" id="brandName" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                <textarea name="description" id="brandDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Brand Logo</label>
                <input type="file" name="logo" id="brandLogo" accept="image/*" onchange="previewImage(event)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                <div id="imagePreview" style="display: none; margin-top: 15px; position: relative;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #3498db;">
                    <button type="button" onclick="cancelImage()" style="position: absolute; top: 10px; right: 10px; width: 30px; height: 30px; border-radius: 50%; background: #e74c3c; color: white; border: none; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Sort Order</label>
                <input type="number" name="sort_order" id="brandSortOrder" value="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="brandStatus" value="1" checked style="margin-right: 8px; width: 18px; height: 18px;">
                    <span style="color: #2c3e50;">Active Brand</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <button type="button" onclick="closeModal()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-save"></i> Save Brand
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let editingBrandId = null;

function openAddModal() {
    editingBrandId = null;
    document.getElementById('modalTitle').textContent = 'Add Brand';
    document.getElementById('brandForm').action = '{{ route('admin.brands.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('brandForm').reset();
    document.getElementById('brandStatus').checked = true;
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('brandModal').style.display = 'flex';
}

function editBrand(brand) {
    editingBrandId = brand.id;
    document.getElementById('modalTitle').textContent = 'Edit Brand';
    document.getElementById('brandForm').action = `/admin/brands/${brand.id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('brandName').value = brand.name;
    document.getElementById('brandDescription').value = brand.description || '';
    document.getElementById('brandSortOrder').value = brand.sort_order;
    document.getElementById('brandStatus').checked = brand.is_active;
    
    // Show current logo if exists
    if (brand.logo) {
        document.getElementById('previewImg').src = `/storage/${brand.logo}`;
        document.getElementById('imagePreview').style.display = 'block';
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
    
    document.getElementById('brandModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('brandModal').style.display = 'none';
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

function cancelImage() {
    document.getElementById('brandLogo').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}

function confirmDelete(brandId, brandName) {
    if (confirm(`Are you sure you want to delete "${brandName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/brands/${brandId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.id === 'brandModal') {
        closeModal();
    }
}

// Show success message if exists
@if(session('success'))
    setTimeout(() => {
        const alert = document.createElement('div');
        alert.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000;';
        alert.innerHTML = '<i class="fas fa-check-circle"></i> {{ session('success') }}';
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }, 100);
@endif
</script>
@endsection
