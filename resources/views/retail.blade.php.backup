@extends('layouts.app')

@section('title', 'Retail - AlphaVendor Multi Vendor Marketplace')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/retail-mobile.css') }}">
<link rel="stylesheet" href="{{ asset('css/shop-mobile.css') }}">
<link rel="stylesheet" href="{{ asset('css/buy-now-button.css') }}">
@endpush

@section('content')
<!-- Hero Banner -->
<section class="retail-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>{{ $content['hero_title'] ?? 'Retail Marketplace' }}</h1>
                <p>{{ $content['hero_description'] ?? 'Discover thousands of products from trusted retail vendors' }}</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <i class="fas fa-store"></i>
                        <div>
                            <h3>{{ $content['stat1_number'] ?? '500+' }}</h3>
                            <span>{{ $content['stat1_label'] ?? 'Retail Stores' }}</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-box"></i>
                        <div>
                            <h3>{{ $content['stat2_number'] ?? '10K+' }}</h3>
                            <span>{{ $content['stat2_label'] ?? 'Products' }}</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <h3>{{ $content['stat3_number'] ?? '50K+' }}</h3>
                            <span>{{ $content['stat3_label'] ?? 'Happy Customers' }}</span>
                        </div>
                    </div>
                </div>
                <button class="btn-explore">
                    <i class="fas fa-compass"></i> Explore Now
                </button>
            </div>
            <div class="hero-image">
                @php
                    $heroImage = $content['hero_image'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=500&fit=crop';
                    $heroImageUrl = str_starts_with($heroImage, 'http') ? $heroImage : asset('storage/' . $heroImage);
                @endphp
                <img src="{{ $heroImageUrl }}" alt="Retail Shopping">
            </div>
        </div>
    </div>
</section>

<!-- Shop Section -->
<section class="shop-section">
    <div class="container">
        <!-- Filter Sidebar Overlay -->
        <div class="filter-sidebar-overlay" id="filterSidebarOverlay"></div>

        <div class="shop-wrapper">
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar" id="shopSidebar">
                <!-- Mobile Filter Header -->
                <div class="filter-sidebar-header" style="display: none;">
                    <h3>Filters</h3>
                    <button class="filter-close-btn" id="filterCloseBtn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Categories Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Categories</h3>
                    <ul class="filter-list">
                        @forelse($categories as $category)
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="category-checkbox" value="{{ $category->id }}"
                                    {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                                <span class="count">({{ $category->products_count }})</span>
                            </label>
                        </li>
                        @empty
                        <li style="padding: 10px; color: #7f8c8d;">No categories available</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Price Range</h3>
                    <div class="price-range-slider">
                        <input type="range" min="0" max="10000" value="{{ request('min_price', 0) }}" class="range-min">
                        <input type="range" min="0" max="10000" value="{{ request('max_price', 10000) }}" class="range-max">
                    </div>
                    <div class="price-inputs">
                        <div class="price-input">
                            <label>Min</label>
                            <input type="number" class="min-price-input" value="{{ request('min_price', 0) }}" min="0" max="10000">
                        </div>
                        <div class="price-input">
                            <label>Max</label>
                            <input type="number" class="max-price-input" value="{{ request('max_price', 10000) }}" min="0" max="10000">
                        </div>
                    </div>
                    <button type="button" class="btn-apply-filter">Apply Filter</button>
                </div>

                <!-- Brands Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Brands</h3>
                    <div class="search-filter">
                        <input type="text" placeholder="Search brands..." class="brand-search-input">
                    </div>
                    <ul class="filter-list brand-filter-list">
                        @forelse($brands ?? [] as $brand)
                        <li style="{{ $loop->index >= 5 ? 'display: none;' : '' }}" class="brand-filter-item" data-brand-name="{{ strtolower($brand->name) }}">
                            <label class="filter-checkbox">
                                <input type="checkbox" class="brand-checkbox" value="{{ $brand->id }}"
                                    {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}>
                                <span>{{ $brand->name }}</span>
                            </label>
                        </li>
                        @empty
                        <li style="padding: 10px; color: #7f8c8d;">No brands available</li>
                        @endforelse
                    </ul>
                    @if(isset($brands) && $brands->count() > 5)
                    <button class="show-more-btn">Show More +</button>
                    @endif
                </div>

                <!-- Rating Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Rating</h3>
                    <ul class="filter-list rating-filter">
                        <li>
                            <label class="filter-checkbox">
                                <input type="radio" name="rating" class="rating-checkbox" value="5"
                                    {{ request('min_rating') == 5 ? 'checked' : '' }}>
                                <span class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="radio" name="rating" class="rating-checkbox" value="4"
                                    {{ request('min_rating') == 4 ? 'checked' : '' }}>
                                <span class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </span>
                                <span>& Up</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="radio" name="rating" class="rating-checkbox" value="3"
                                    {{ request('min_rating') == 3 ? 'checked' : '' }}>
                                <span class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                </span>
                                <span>& Up</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Clear All Filters -->
                <button class="btn-clear-filters">
                    <i class="fas fa-times"></i> Clear All Filters
                </button>
            </aside>

            <!-- Products Area -->
            <div class="shop-content">
                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="toolbar-left">
                        <p class="results-count">Showing <strong>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> results</p>
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
                        <div class="sort-dropdown">
                            <select>
                                <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Sort by: Default</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Best Rating</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                        <div class="per-page-dropdown">
                            <select>
                                <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>Show: 12</option>
                                <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>Show: 24</option>
                                <option value="36" {{ request('per_page', 12) == 36 ? 'selected' : '' }}>Show: 36</option>
                                <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>Show: 48</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if(request()->hasAny(['categories', 'brands', 'min_price', 'max_price', 'min_rating']))
                <div class="active-filters">
                    @if(request('categories'))
                        @foreach(request('categories') as $categoryId)
                            @php
                                $category = $categories->firstWhere('id', $categoryId);
                            @endphp
                            @if($category)
                            <span class="filter-tag" data-filter-type="category" data-filter-value="{{ $categoryId }}">
                                {{ $category->name }}
                                <button class="remove-filter"><i class="fas fa-times"></i></button>
                            </span>
                            @endif
                        @endforeach
                    @endif

                    @if(request('min_price') || request('max_price'))
                    <span class="filter-tag">
                        Price: ${{ request('min_price', 0) }} - ${{ request('max_price', 10000) }}
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                    @endif

                    @if(request('brands'))
                        @foreach(request('brands') as $brand)
                        <span class="filter-tag" data-filter-type="brand" data-filter-value="{{ $brand }}">
                            {{ $brand }}
                            <button class="remove-filter"><i class="fas fa-times"></i></button>
                        </span>
                        @endforeach
                    @endif

                    @if(request('min_rating'))
                    <span class="filter-tag">
                        {{ request('min_rating') }}+ Stars
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                    @endif
                </div>
                @endif

                <!-- Products Grid -->
                <div class="products-grid-view">
                    @forelse($products as $product)
                    <a href="{{ route('product.show', $product->id) }}" class="product-card">
                        <div class="product-image">
                            @if($product->image)
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="{{ $product->name }}">
                            @endif
                            @if($product->badge)
                            <span class="badge {{ strtolower($product->badge) }}">{{ $product->badge }}</span>
                            @endif
                            <div class="product-actions">
                                @auth
                                    <button class="action-btn wishlist-btn" 
                                            data-product-id="{{ $product->id }}"
                                            title="Add to Wishlist" 
                                            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this);">
                                        <i class="far fa-heart"></i>
                                    </button>
                                @else
                                    <button class="action-btn" 
                                            title="Login to add to wishlist" 
                                            onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('login') }}';">
                                        <i class="far fa-heart"></i>
                                    </button>
                                @endauth
                                <button class="action-btn" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Compare" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-card-actions">
                            <button class="quick-add-btn" data-product-id="{{ $product->id }}" onclick="event.preventDefault(); event.stopPropagation(); quickAddToCart({{ $product->id }}, this);">
                                <i class="fas fa-shopping-cart"></i>
                                Quick Add
                            </button>
                            <button class="buy-now-btn" data-product-id="{{ $product->id }}" onclick="event.preventDefault(); event.stopPropagation(); buyNow({{ $product->id }}, this);">
                                <i class="fas fa-bolt"></i>
                                Buy Now
                            </button>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h4>{{ $product->name }}</h4>
                            <div class="vendor-name">
                                <i class="fas fa-store"></i> {{ $product->vendor->name ?? 'AlphaVendor' }}
                            </div>
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($product->rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i < $product->rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span>({{ number_format($product->rating, 1) }}) {{ $product->reviews_count }} reviews</span>
                            </div>
                            <div class="price">
                                <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                @if($product->old_price)
                                    <span class="old-price">${{ number_format($product->old_price, 2) }}</span>
                                    <span class="discount">-{{ $product->discount_percentage }}%</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                        <i class="fas fa-box-open" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                        <h3 style="color: #2c3e50; margin-bottom: 10px;">No Products Found</h3>
                        <p style="color: #7f8c8d;">Check back later for new products!</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
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
            newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #0d5c63; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
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

// Price Range Slider - Synchronize sliders with inputs
document.addEventListener('DOMContentLoaded', function() {
    const rangeMin = document.querySelector(".range-min");
    const rangeMax = document.querySelector(".range-max");
    const minPriceInput = document.querySelector('.min-price-input');
    const maxPriceInput = document.querySelector('.max-price-input');

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

// Wishlist functionality - Define in global scope
window.toggleWishlist = function(productId, button) {
    console.log('toggleWishlist called with productId:', productId);
    
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    
    if (!isAuthenticated) {
        console.log('User not authenticated, redirecting to login');
        window.location.href = '{{ route("login") }}';
        return;
    }

    const icon = button.querySelector('i');
    const originalClass = icon.className;
    
    console.log('Starting wishlist toggle...');
    
    // Show loading state
    button.disabled = true;
    icon.className = 'fas fa-spinner fa-spin';

    fetch('/wishlist/toggle/' + productId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Update icon based on wishlist status
            if (data.inWishlist) {
                icon.className = 'fas fa-heart';
                button.style.color = '#e74c3c';
            } else {
                icon.className = 'far fa-heart';
                button.style.color = '';
            }
            
            // Update wishlist count in header
            updateWishlistCount(data.wishlistCount);
            
            // Show toast notification
            showToast(data.message, 'success');
        } else {
            icon.className = originalClass;
            showToast(data.message || 'Failed to update wishlist', 'error');
        }
        button.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        icon.className = originalClass;
        button.disabled = false;
        showToast('Failed to update wishlist', 'error');
    });
};

window.updateWishlistCount = function(count) {
    console.log('Updating wishlist count to:', count);
    const headerWishlist = document.querySelector('.header-actions a[href*="wishlist"]');
    if (headerWishlist) {
        let badge = headerWishlist.querySelector('span');
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #e74c3c; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
                headerWishlist.appendChild(badge);
            }
            badge.textContent = count;
        } else if (badge) {
            badge.remove();
        }
    }
};

console.log('Wishlist functions loaded');

// Check wishlist status on page load
@if(auth()->check())
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checking wishlist status for products...');
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    console.log('Found wishlist buttons:', wishlistButtons.length);
    
    wishlistButtons.forEach(button => {
        const productId = button.dataset.productId;
        if (productId) {
            fetch('/wishlist/check/' + productId, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.inWishlist) {
                    const icon = button.querySelector('i');
                    icon.className = 'fas fa-heart';
                    button.style.color = '#e74c3c';
                }
            })
            .catch(error => console.error('Error checking wishlist:', error));
        }
    });
});
@endif
</script>

<script>
// Mobile Filter Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterToggle = document.getElementById('mobileFilterToggle');
    const shopSidebar = document.getElementById('shopSidebar');
    const filterSidebarOverlay = document.getElementById('filterSidebarOverlay');
    const filterCloseBtn = document.getElementById('filterCloseBtn');
    const filterHeader = document.querySelector('.filter-sidebar-header');

    // Show filter header on mobile
    function updateFilterHeader() {
        if (window.innerWidth <= 768 && filterHeader) {
            filterHeader.style.display = 'flex';
        } else if (filterHeader) {
            filterHeader.style.display = 'none';
        }
    }

    updateFilterHeader();
    window.addEventListener('resize', updateFilterHeader);

    function openFilters() {
        if (shopSidebar && filterSidebarOverlay) {
            shopSidebar.classList.add('active');
            filterSidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeFilters() {
        if (shopSidebar && filterSidebarOverlay) {
            shopSidebar.classList.remove('active');
            filterSidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (mobileFilterToggle) {
        mobileFilterToggle.addEventListener('click', openFilters);
    }

    if (filterCloseBtn) {
        filterCloseBtn.addEventListener('click', closeFilters);
    }

    if (filterSidebarOverlay) {
        filterSidebarOverlay.addEventListener('click', closeFilters);
    }

    // Close filters when applying filter
    const applyFilterBtn = document.querySelector('.btn-apply-filter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(closeFilters, 300);
            }
        });
    }

    // Close filters when clearing all
    const clearFiltersBtn = document.querySelector('.btn-clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(closeFilters, 300);
            }
        });
    }
});

// Buy Now function
function buyNow(productId, button) {
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    fetch('/cart/buy-now', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            product_id: productId,
            quantity: 1 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/checkout';
        } else {
            throw new Error(data.message || 'Failed to process');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalContent;
        showToast('Failed to process order', 'error');
    });
}
</script>

<script src="{{ asset('js/shop.js') }}"></script>
@endpush
