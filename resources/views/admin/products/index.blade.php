@extends('layouts.dashboard')

@section('title', 'Products Management')
@section('page-title', 'Products')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
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

<!-- Filter Section -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
    <div style="background: white; padding: 20px; border-radius: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h3 style="margin: 0; color: #667eea; font-size: 18px; display: flex; align-items: center; gap: 8px; font-weight: 600;">
                <i class="fas fa-filter"></i> FILTER & SEARCH PRODUCTS
            </h3>
            <button type="button" onclick="toggleFilters()" id="toggleFilterBtn" style="padding: 8px 16px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 6px; font-weight: 500;">
                <i class="fas fa-chevron-up" id="filterIcon"></i> <span id="filterText">Hide Filters</span>
            </button>
        </div>
        <form method="GET" action="{{ route('admin.products') }}" id="filterForm">
            <div id="filterContent" style="display: block;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-search"></i> Search
                </label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name or SKU..." 
                    style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e0e0e0'">
            </div>

            <!-- Category Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-tags"></i> Category
                </label>
                <select name="category" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Vendor Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-user"></i> Vendor
                </label>
                <select name="vendor" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="">All Vendors</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }} ({{ ucfirst($vendor->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Brand Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-copyright"></i> Brand
                </label>
                <select name="brand" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-toggle-on"></i> Status
                </label>
                <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label style="display: block; margin-bottom: 5px; color: #2c3e50; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-sort"></i> Sort By
                </label>
                <select name="sort_by" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    <option value="stock_asc" {{ request('sort_by') == 'stock_asc' ? 'selected' : '' }}>Stock (Low to High)</option>
                    <option value="stock_desc" {{ request('sort_by') == 'stock_desc' ? 'selected' : '' }}>Stock (High to Low)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <button type="submit" style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
            <a href="{{ route('admin.products') }}" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="fas fa-redo"></i> Reset
            </a>
            <div style="margin-left: auto; color: #7f8c8d; display: flex; align-items: center; font-size: 14px;">
                <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
                <strong style="color: #667eea;">{{ $products->count() }}</strong>&nbsp;products found
            </div>
        </div>
        </div>
    </form>
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
                        <button 
                            onclick="editProductById({{ $product->id }})"
                            style="padding: 6px 12px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button 
                            onclick="confirmDelete({{ $product->id }}, {{ Js::from($product->name) }})"
                            style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">
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

            <!-- Row 1: Product Name, SKU, Category, Brand (Always Visible) -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px;">
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
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Brand</label>
                    <select name="brand_id" id="productBrand" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Brand (Optional)</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- See More Button -->
            <div style="text-align: center; margin-bottom: 20px;">
                <button type="button" onclick="toggleMoreFields()" id="seeMoreBtn" style="padding: 10px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <span id="seeMoreText">See More</span>
                    <i class="fas fa-chevron-down" id="seeMoreIcon"></i>
                </button>
            </div>

            <!-- Additional Fields (Hidden by default) -->
            <div id="moreFields" style="display: none;">
                <!-- Row 2: Vendor, Price, Old Price, Stock -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px;">
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
                        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Price (৳) *</label>
                        <input type="number" name="price" id="productPrice" required step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Old Price (৳)</label>
                        <input type="number" name="old_price" id="productOldPrice" step="0.01" min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Stock Quantity *</label>
                        <input type="number" name="stock" id="productStock" required min="0" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <!-- Row 3: Status (full width) -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Status *</label>
                    <select name="status" id="productStatus" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <!-- Description -->
                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Description</label>
                    <textarea name="description" id="productDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></textarea>
                </div>

                <!-- Product Image -->
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

                <!-- Featured, Free Shipping, Badge -->
                <div style="margin-top: 20px; display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="is_featured" id="productFeatured" value="1" style="margin-right: 8px; width: 18px; height: 18px;">
                        <span style="color: #2c3e50;">Featured Product</span>
                    </label>

                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="free_shipping" id="productFreeShipping" value="1" style="margin-right: 8px; width: 18px; height: 18px;">
                        <span style="color: #2c3e50;"><i class="fas fa-shipping-fast"></i> Free Shipping</span>
                    </label>

                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Badge (Optional)</label>
                        <input type="text" name="badge" id="productBadge" placeholder="e.g., New, Sale, Hot" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                </div>

                <!-- Special Offer -->
                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Special Offer</label>
                    <select name="special_offer_id" id="productOffer" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">No Offer</option>
                        @foreach($offers as $offer)
                            <option value="{{ $offer->id }}" data-color="{{ $offer->badge_color }}" data-text="{{ $offer->badge_text }}">
                                {{ $offer->name }} @if($offer->badge_text) - {{ $offer->badge_text }} @endif
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #7f8c8d;">Assign this product to a special offer category</small>
                </div>

                <!-- Shipping Method -->
                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">
                        <i class="fas fa-shipping-fast" style="color: #3498db;"></i> Shipping Method
                    </label>
                    <select name="shipping_method_id" id="productShipping" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                        <option value="">Select Shipping Method</option>
                        @foreach($shippingMethods->groupBy('zone') as $zone => $methods)
                            <optgroup label="{{ $zone }}">
                                @foreach($methods as $method)
                                    <option value="{{ $method->id }}">
                                        {{ $method->name }} - ৳{{ number_format($method->cost, 2) }} 
                                        ({{ $method->estimated_days_min }}-{{ $method->estimated_days_max }} days)
                                        @if($method->free_shipping_threshold)
                                            - Free over ৳{{ number_format($method->free_shipping_threshold, 2) }}
                                        @endif
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <small style="color: #7f8c8d;">Select the shipping method for this product</small>
                </div>

                <!-- Product Attributes -->
                <div style="margin-top: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Product Attributes</label>
                    <div id="attributesContainer" style="border: 1px solid #ddd; border-radius: 6px; padding: 15px; background: #f8f9fa;">
                        @if($attributes->count() > 0)
                            @foreach($attributes as $attribute)
                            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                                <label style="min-width: 120px; color: #2c3e50; font-weight: 500;">
                                    {{ $attribute->name }}:
                                    @if($attribute->is_required)
                                        <span style="color: #ef4444; font-size: 12px;">*</span>
                                    @endif
                                </label>
                                @if($attribute->type === 'select')
                                    <select name="attributes[{{ $attribute->id }}]" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" {{ $attribute->is_required ? 'required' : '' }}>
                                        <option value="">Select {{ $attribute->name }}</option>
                                        @if($attribute->options)
                                            @foreach($attribute->options as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                @elseif($attribute->type === 'color')
                                    <input type="color" name="attributes[{{ $attribute->id }}]" value="#000000" style="width: 60px; height: 40px; border: 1px solid #ddd; border-radius: 4px;" {{ $attribute->is_required ? 'required' : '' }}>
                                @elseif($attribute->type === 'number')
                                    <input type="number" name="attributes[{{ $attribute->id }}]" placeholder="Enter {{ $attribute->name }}" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" {{ $attribute->is_required ? 'required' : '' }}>
                                @else
                                    <input type="text" name="attributes[{{ $attribute->id }}]" placeholder="Enter {{ $attribute->name }}" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" {{ $attribute->is_required ? 'required' : '' }}>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <p style="color: #7f8c8d; margin: 0; text-align: center;">No attributes configured yet. <a href="{{ route('admin.attributes.index') }}" style="color: #3498db;">Create attributes</a> to enhance your products.</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End of More Fields -->

            <!-- Submit Buttons -->
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

<script id="products-data" type="application/json">{{ json_encode($products->toArray()) }}</script>

<script>
let isEditMode = false;

// Products data - loaded via AJAX to avoid JS injection issues
const productsMap = {};

async function loadProductsMap() {
    try {
        const res = await fetch('{{ route("admin.products") }}?format=json', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        });
        if (res.ok) {
            const data = await res.json();
            if (data.products) {
                data.products.forEach(p => { productsMap[p.id] = p; });
            }
        }
    } catch(e) {}
}

// Pre-load products data from inline script (safe encoding)
(function() {
    const raw = document.getElementById('products-data');
    if (raw) {
        try {
            const arr = JSON.parse(raw.textContent);
            arr.forEach(p => { productsMap[p.id] = p; });
        } catch(e) { console.error('Products data parse error:', e); }
    }
})();

function editProductById(id) {
    const product = productsMap[id];
    if (!product) {
        showToast('Product not found', 'error');
        return;
    }
    editProduct(product);
}

function showToast(message, type = 'success') {
    const colors = { success: '#27ae60', error: '#e74c3c', info: '#3b82f6', warning: '#f59e0b' };
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;top:20px;right:20px;background:${colors[type]||colors.success};color:white;padding:14px 22px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:99999;font-size:14px;font-weight:500;max-width:320px;`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}

// Toggle More Fields
function toggleMoreFields() {
    const moreFields = document.getElementById('moreFields');
    const seeMoreBtn = document.getElementById('seeMoreBtn');
    const seeMoreText = document.getElementById('seeMoreText');
    const seeMoreIcon = document.getElementById('seeMoreIcon');
    
    if (moreFields.style.display === 'none' || moreFields.style.display === '') {
        moreFields.style.display = 'block';
        seeMoreText.textContent = 'See Less';
        seeMoreIcon.className = 'fas fa-chevron-up';
    } else {
        moreFields.style.display = 'none';
        seeMoreText.textContent = 'See More';
        seeMoreIcon.className = 'fas fa-chevron-down';
    }
}

function openAddModal() {
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').action = '{{ route('admin.products.store') }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('productForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imageRequiredLabel').textContent = '*';
    document.getElementById('productImage').required = true;

    // Collapse more fields by default
    document.getElementById('moreFields').style.display = 'none';
    document.getElementById('seeMoreText').textContent = 'See More';
    document.getElementById('seeMoreIcon').className = 'fas fa-chevron-down';

    // Check if there's a saved draft ID - fetch LATEST data from server
    const draftId = localStorage.getItem('product_draft_id');
    if (draftId) {
        fetch(`/admin/products/draft/${draftId}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.product) {
                const p = data.product;
                const savedAt = localStorage.getItem('product_draft_time') || 'earlier';
                const restore = confirm(`You have a draft saved at ${savedAt}:\n"${p.name || 'Untitled'}"\n\nRestore it?`);
                if (restore) {
                    if (p.name)        document.getElementById('productName').value        = p.name;
                    if (p.sku)         document.getElementById('productSku').value         = p.sku;
                    if (p.category_id) document.getElementById('productCategory').value    = p.category_id;
                    if (p.brand_id)    document.getElementById('productBrand').value       = p.brand_id;
                    if (p.vendor_id)   document.getElementById('productVendor').value      = p.vendor_id;
                    if (p.price)       document.getElementById('productPrice').value       = p.price;
                    if (p.old_price)   document.getElementById('productOldPrice').value    = p.old_price;
                    if (p.stock)       document.getElementById('productStock').value       = p.stock;
                    if (p.description) document.getElementById('productDescription').value = p.description;
                    if (p.badge)       document.getElementById('productBadge').value       = p.badge;
                    // Expand more fields
                    document.getElementById('moreFields').style.display = 'block';
                    document.getElementById('seeMoreText').textContent = 'See Less';
                    document.getElementById('seeMoreIcon').className = 'fas fa-chevron-up';
                    showToast('Latest draft restored!', 'success');
                } else {
                    // User declined - clear draft
                    localStorage.removeItem('product_draft_id');
                    localStorage.removeItem('product_draft_time');
                }
            } else {
                // Draft not found on server - clear localStorage
                localStorage.removeItem('product_draft_id');
                localStorage.removeItem('product_draft_time');
            }
        })
        .catch(() => {
            localStorage.removeItem('product_draft_id');
        });
    }

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
    document.getElementById('productBrand').value = product.brand_id || '';
    
    // Auto-expand more fields when editing
    document.getElementById('moreFields').style.display = 'block';
    document.getElementById('seeMoreText').textContent = 'See Less';
    document.getElementById('seeMoreIcon').className = 'fas fa-chevron-up';
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

    // Clear all attribute inputs
    const attributeInputs = document.querySelectorAll('#attributesContainer input, #attributesContainer select');
    attributeInputs.forEach(input => {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });

    // Populate attributes
    if (product.attributes && product.attributes.length > 0) {
        product.attributes.forEach(attr => {
            const input = document.querySelector(`[name="attributes[${attr.id}]"]`);
            if (input) {
                input.value = attr.pivot.value;
            }
        });
    }

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

// Clear draft ID when form is actually submitted (published)
document.getElementById('productForm')?.addEventListener('submit', function() {
    localStorage.removeItem('product_draft_id');
    localStorage.removeItem('product_draft_time');
});

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

// Modal only closes via ✕ button or Save — backdrop click does nothing

async function saveDraftToServer() {
    const name  = document.getElementById('productName')?.value?.trim();
    const price = document.getElementById('productPrice')?.value?.trim();
    const desc  = document.getElementById('productDescription')?.value?.trim();

    // Only save if there's meaningful data
    if (!name && !price && !desc) {
        closeModal();
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('name',        document.getElementById('productName')?.value || '');
    formData.append('sku',         document.getElementById('productSku')?.value || '');
    formData.append('category_id', document.getElementById('productCategory')?.value || '');
    formData.append('brand_id',    document.getElementById('productBrand')?.value || '');
    formData.append('vendor_id',   document.getElementById('productVendor')?.value || '');
    formData.append('price',       document.getElementById('productPrice')?.value || '');
    formData.append('old_price',   document.getElementById('productOldPrice')?.value || '');
    formData.append('stock',       document.getElementById('productStock')?.value || '');
    formData.append('description', document.getElementById('productDescription')?.value || '');
    formData.append('badge',       document.getElementById('productBadge')?.value || '');

    // Pass existing draft_id if we have one (to update same draft)
    const draftId = localStorage.getItem('product_draft_id');
    if (draftId) formData.append('draft_id', draftId);

    try {
        const res = await fetch('{{ route("admin.products.draft") }}', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
        });
        const data = await res.json();

        if (data.success) {
            // Store draft ID so next save updates the same draft
            localStorage.setItem('product_draft_id', data.product_id);
            localStorage.setItem('product_draft_time', data.saved_at);
            showToast(`✅ Draft saved at ${data.saved_at}`, 'info');
        } else {
            if (data.message !== 'Nothing to save') {
                showToast(data.message || 'Could not save draft', 'warning');
            }
        }
    } catch(err) {
        showToast('Draft save failed', 'error');
    }

    closeModal();
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Auto-submit filter form on select change
document.querySelectorAll('#filterForm select').forEach(function(select) {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Submit on search input (with debounce)
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 500);
    });
}

// Toggle filters
function toggleFilters() {
    const filterContent = document.getElementById('filterContent');
    const filterIcon = document.getElementById('filterIcon');
    const filterText = document.getElementById('filterText');
    
    if (filterContent.style.display === 'none') {
        filterContent.style.display = 'block';
        filterIcon.className = 'fas fa-chevron-up';
        filterText.textContent = 'Hide Filters';
    } else {
        filterContent.style.display = 'none';
        filterIcon.className = 'fas fa-chevron-down';
        filterText.textContent = 'Show Filters';
    }
}
</script>
@endsection
