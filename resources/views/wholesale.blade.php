@extends('layouts.app')

@section('title', 'Wholesale - AlphaVendor Multi Vendor Marketplace')

@section('content')
<!-- Breadcrumb -->
{{-- <section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator">/</span>
            <span>Wholesale</span>
        </nav>
    </div>
</section> --}}

<!-- Hero Banner -->
<section class="wholesale-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge">B2B Solutions</span>
                <h1>Wholesale Marketplace</h1>
                <p>Connect with verified suppliers and manufacturers. Buy in bulk with competitive wholesale prices for your business.</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <i class="fas fa-industry"></i>
                        <div>
                            <h3>2,500+</h3>
                            <span>Manufacturers</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-boxes"></i>
                        <div>
                            <h3>50K+</h3>
                            <span>Bulk Products</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-handshake"></i>
                        <div>
                            <h3>15K+</h3>
                            <span>Business Partners</span>
                        </div>
                    </div>
                </div>
                <div class="hero-buttons">
                    <button class="btn-primary">
                        <i class="fas fa-shopping-cart"></i> Start Ordering
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-user-plus"></i> Register as Buyer
                    </button>
                </div>
            </div>
            <div class="hero-image">
                <div class="image-stack">
                    <div class="image-card card-1">
                        <i class="fas fa-truck"></i>
                        <h4>Fast Shipping</h4>
                        <p>Global delivery</p>
                    </div>
                    <div class="image-card card-2">
                        <i class="fas fa-shield-alt"></i>
                        <h4>Secure Payment</h4>
                        <p>100% protected</p>
                    </div>
                    <div class="image-card card-3">
                        <i class="fas fa-percentage"></i>
                        <h4>Best Prices</h4>
                        <p>Bulk discounts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop Section -->
<section class="shop-section">
    <div class="container">
        <div class="shop-wrapper">
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar">
                <form method="GET" action="{{ route('wholesale') }}" id="filterForm">
                    <!-- Categories Filter -->
                    <div class="filter-box">
                        <h3 class="filter-title">Categories</h3>
                        <ul class="filter-list">
                            @foreach($categories as $category)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>{{ $category->name }}</span>
                                    <span class="count">({{ $category->products_count }})</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Minimum Order Filter -->
                    <div class="filter-box">
                        <h3 class="filter-title">Minimum Order</h3>
                        <ul class="filter-list">
                            @foreach($minOrderRanges as $value => $label)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="radio" name="minimum_order" value="{{ $value }}" {{ request('minimum_order') == $value ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>{{ $label }}</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-box">
                        <h3 class="filter-title">Price Range</h3>
                        <div class="price-range-slider">
                            <input type="range" min="0" max="10000" value="{{ request('min_price', 0) }}" class="range-min" id="wholesale-range-min">
                            <input type="range" min="0" max="10000" value="{{ request('max_price', 10000) }}" class="range-max" id="wholesale-range-max">
                        </div>
                        <div class="price-inputs">
                            <div class="price-input">
                                <label>Min</label>
                                <input type="number" name="min_price" class="min-price-input" value="{{ request('min_price', 0) }}" min="0" max="10000" id="wholesale-min-price">
                            </div>
                            <div class="price-input">
                                <label>Max</label>
                                <input type="number" name="max_price" class="max-price-input" value="{{ request('max_price', 10000) }}" min="0" max="10000" id="wholesale-max-price">
                            </div>
                        </div>
                        <button type="submit" class="btn-apply-filter">Apply Filter</button>
                    </div>

                    <!-- Supplier Location Filter -->
                    <div class="filter-box">
                        <h3 class="filter-title">Supplier Location</h3>
                        <ul class="filter-list">
                            @foreach($supplierLocations as $location)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="radio" name="supplier_location" value="{{ $location->country }}" {{ request('supplier_location') == $location->country ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>{{ $location->country }}</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Brands Filter -->
                    <div class="filter-box">
                        <h3 class="filter-title">Brands</h3>
                        <ul class="filter-list">
                            @foreach($brands as $brand)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>{{ $brand->name }}</span>
                                    <span class="count">({{ $brand->products_count }})</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Clear All Filters -->
                    <a href="{{ route('wholesale') }}" class="btn-clear-filters">
                        <i class="fas fa-times"></i> Clear All Filters
                    </a>
                </form>
            </aside>

            <!-- Products Area -->
            <div class="shop-content">
                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="toolbar-left">
                        <p class="results-count">Showing <strong>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> wholesale products</p>
                    </div>
                    <div class="toolbar-right">
                        <div class="view-mode">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if(request()->hasAny(['category', 'minimum_order', 'supplier_location', 'brand', 'min_price', 'max_price']))
                <div class="active-filters">
                    @if(request('category'))
                        @php $selectedCategory = $categories->firstWhere('id', request('category')); @endphp
                        @if($selectedCategory)
                        <span class="filter-tag">
                            {{ $selectedCategory->name }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="remove-filter"><i class="fas fa-times"></i></a>
                        </span>
                        @endif
                    @endif
                    @if(request('minimum_order'))
                        <span class="filter-tag">
                            Min Order: {{ $minOrderRanges[request('minimum_order')] ?? request('minimum_order') }}
                            <a href="{{ request()->fullUrlWithoutQuery('minimum_order') }}" class="remove-filter"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                    @if(request('supplier_location'))
                        <span class="filter-tag">
                            Location: {{ request('supplier_location') }}
                            <a href="{{ request()->fullUrlWithoutQuery('supplier_location') }}" class="remove-filter"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                    @if(request('brand'))
                        @php $selectedBrand = $brands->firstWhere('id', request('brand')); @endphp
                        @if($selectedBrand)
                        <span class="filter-tag">
                            {{ $selectedBrand->name }}
                            <a href="{{ request()->fullUrlWithoutQuery('brand') }}" class="remove-filter"><i class="fas fa-times"></i></a>
                        </span>
                        @endif
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="filter-tag">
                            Price: ${{ request('min_price', 0) }} - ${{ request('max_price', 10000) }}
                            <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}" class="remove-filter"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                </div>
                @endif

                <!-- Products Grid -->
                <div class="products-grid-view">
                    @forelse($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->image)
                                @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @endif
                            @else
                                <img src="https://via.placeholder.com/300x300?text=No+Image" alt="{{ $product->name }}">
                            @endif
                            @if($product->badge)
                                <span class="badge {{ strtolower($product->badge) }}">{{ $product->badge }}</span>
                            @endif
                            <div class="product-actions">
                                <button class="action-btn" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="action-btn" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Compare" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <button class="quick-add-btn" data-product-id="{{ $product->id }}" onclick="quickAddToCart({{ $product->id }}, this);">
                                <i class="fas fa-shopping-cart"></i>
                                Quick Add
                            </button>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h4>{{ $product->name }}</h4>
                            <div class="vendor-name">
                                <i class="fas fa-industry"></i> {{ $product->vendor->name ?? 'Unknown Vendor' }}
                            </div>
                            @if($product->supplier_location)
                            <div class="supplier-location">
                                <i class="fas fa-map-marker-alt"></i> {{ $product->supplier_location }}
                            </div>
                            @endif
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $product->rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span>({{ number_format($product->rating, 1) }}) {{ $product->reviews_count }} reviews</span>
                            </div>
                            <div class="wholesale-info">
                                <span class="min-order"><i class="fas fa-boxes"></i> Min: {{ $product->minimum_order }} units</span>
                            </div>
                            <div class="price">
                                <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                @if($product->old_price)
                                    <span class="old-price">${{ number_format($product->old_price, 2) }}</span>
                                    <span class="discount">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                        <i class="fas fa-box-open" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                        <h3 style="color: #666; margin-bottom: 10px;">No Products Found</h3>
                        <p style="color: #999;">Try adjusting your filters or <a href="{{ route('wholesale') }}">clear all filters</a>.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Price Range Slider - Synchronize sliders with inputs
document.addEventListener('DOMContentLoaded', function() {
    const rangeMin = document.querySelector("#wholesale-range-min");
    const rangeMax = document.querySelector("#wholesale-range-max");
    const minPriceInput = document.querySelector('#wholesale-min-price');
    const maxPriceInput = document.querySelector('#wholesale-max-price');

    if (!rangeMin || !rangeMax || !minPriceInput || !maxPriceInput) {
        return;
    }

    // Update min slider
    rangeMin.addEventListener('input', function() {
        let min = parseInt(this.value);
        let max = parseInt(rangeMax.value);

        if (min > max - 100) {
            min = max - 100;
            this.value = min;
        }

        minPriceInput.value = min;
    });

    // Update max slider
    rangeMax.addEventListener('input', function() {
        let min = parseInt(rangeMin.value);
        let max = parseInt(this.value);

        if (max < min + 100) {
            max = min + 100;
            this.value = max;
        }

        maxPriceInput.value = max;
    });

    // Update min input
    minPriceInput.addEventListener('input', function() {
        let min = parseInt(this.value) || 0;
        let max = parseInt(maxPriceInput.value) || 10000;

        if (min < 0) {
            min = 0;
            this.value = min;
        }
        if (min > max - 100) {
            min = max - 100;
            this.value = min;
        }

        rangeMin.value = min;
    });

    // Update max input
    maxPriceInput.addEventListener('input', function() {
        let min = parseInt(minPriceInput.value) || 0;
        let max = parseInt(this.value) || 10000;

        if (max > 10000) {
            max = 10000;
            this.value = max;
        }
        if (max < min + 100) {
            max = min + 100;
            this.value = max;
        }

        rangeMax.value = max;
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 10000;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease-out;
    `;
    
    const icon = type === 'success' ? '✓' : '✕';
    toast.innerHTML = `<span style="font-size: 20px;">${icon}</span>${message}`;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add CSS animations
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement.querySelector('span');
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            const cartLink = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement;
            const newBadge = document.createElement('span');
            newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #FFA500; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
            newBadge.textContent = count;
            cartLink.appendChild(newBadge);
        }
    }
}

// Quick Add to Cart function
function quickAddToCart(productId, button) {
    const originalContent = button.innerHTML;
    
    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success state
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.style.background = '#27ae60';
            
            // Show toast notification
            showToast('Product added to cart successfully!', 'success');
            
            // Update cart badge
            updateCartBadge(data.cartCount);
            
            // Reset button after 2 seconds
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalContent;
                button.style.background = '';
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to add product');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalContent;
        showToast('Failed to add product to cart', 'error');
    });
}
