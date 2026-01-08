@extends('layouts.app')

@section('title', 'Export - AlphaVendor Multi Vendor Marketplace')

@section('content')
<!-- Hero Banner -->
<section class="export-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge"><i class="fas fa-globe"></i> Global Export</span>
                <h1>International Export Marketplace</h1>
                <p>Connect with verified international buyers and expand your business globally. Export quality products with seamless logistics and documentation support.</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <i class="fas fa-globe-americas"></i>
                        <div>
                            <h3>{{ $locations->count() }}+</h3>
                            <span>Locations</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-ship"></i>
                        <div>
                            <h3>{{ $products->total() }}+</h3>
                            <span>Products</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-handshake"></i>
                        <div>
                            <h3>{{ $categories->count() }}+</h3>
                            <span>Categories</span>
                        </div>
                    </div>
                </div>
                <div class="hero-buttons">
                    <button class="btn-primary">
                        <i class="fas fa-plane-departure"></i> Start Exporting
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-file-contract"></i> View Documentation
                    </button>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1548588627-f978862b85e1?w=1100&h=750&fit=crop" alt="Global Export Cargo Logistics">
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
                <!-- Categories Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Export Categories</h3>
                    <ul class="filter-list">
                        @forelse($categories as $category)
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="category" value="{{ $category->id }}" class="category-filter"
                                    {{ in_array($category->id, explode(',', request('categories', ''))) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                                <span class="count">({{ $category->products_count }})</span>
                            </label>
                        </li>
                        @empty
                        <li>
                            <span class="text-gray-500">No categories available</span>
                        </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Supplier Location Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Supplier Location</h3>
                    <ul class="filter-list">
                        @forelse($locations as $location)
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="location" value="{{ $location }}" class="location-filter"
                                    {{ request('location') == $location ? 'checked' : '' }}>
                                <span>{{ $location }}</span>
                            </label>
                        </li>
                        @empty
                        <li>
                            <span class="text-gray-500">No locations available</span>
                        </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Price Range (FOB)</h3>
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

                <!-- Minimum Order Quantity Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Minimum Order (MOQ)</h3>
                    <ul class="filter-list">
                        @foreach($moqRanges as $moq)
                        @if($moq['count'] > 0)
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="moq" value="{{ $moq['range'] }}" class="moq-filter"
                                    {{ request('moq') == $moq['range'] ? 'checked' : '' }}>
                                <span>{{ $moq['label'] }}</span>
                                <span class="count">({{ $moq['count'] }})</span>
                            </label>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>

                <!-- Certifications Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Export Certifications</h3>
                    @if($certifications && $certifications->count() > 0)
                        <ul class="filter-list">
                            @php
                                $selectedCertifications = request('certifications', []);
                                $selectedCertifications = is_array($selectedCertifications) ? $selectedCertifications : [$selectedCertifications];
                            @endphp
                            @foreach($certifications as $cert)
                                <li>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="certifications" value="cert_{{ $cert->id }}" class="certification-filter"
                                            {{ in_array('cert_' . $cert->id, $selectedCertifications) ? 'checked' : '' }}>
                                        <span>{{ $cert->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-sm px-4 py-2">No certifications available</p>
                    @endif
                </div>

                <!-- Supplier Rating Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Exporter Rating</h3>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="radio" name="rating" class="rating-filter" value="4.5"
                                    {{ request('min_rating') == '4.5' ? 'checked' : '' }}>
                                <span><i class="fas fa-star"></i> 4.5+ Stars</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="radio" name="rating" class="rating-filter" value="4.0"
                                    {{ request('min_rating') == '4.0' ? 'checked' : '' }}>
                                <span><i class="fas fa-star"></i> 4.0+ Stars</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="radio" name="rating" class="rating-filter" value="3.5"
                                    {{ request('min_rating') == '3.5' ? 'checked' : '' }}>
                                <span><i class="fas fa-star"></i> 3.5+ Stars</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Clear Filters Button -->
                <button class="clear-filters">
                    <i class="fas fa-times"></i> Clear All Filters
                </button>
            </aside>

            <!-- Main Content -->
            <div class="shop-content">
                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="toolbar-left">
                        <p class="results-count">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} export products</p>
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
                        <select class="sort-select" id="sortSelect" onchange="applyFilters()">
                            <option value="featured">Sort by: Featured</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="moq_low">MOQ: Low to High</option>
                            <option value="newest">Newest First</option>
                            <option value="rating">Best Rating</option>
                        </select>
                        <select class="per-page-select">
                            <option>Show: 24</option>
                            <option>Show: 48</option>
                            <option>Show: 96</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters -->
                <div class="active-filters" id="activeFilters">
                    <!-- Dynamic active filters will be added here -->
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
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
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="{{ $product->name }}">
                            @endif
                            <div class="product-badges">
                                @if($product->badge)
                                    <span class="badge badge-hot">{{ $product->badge }}</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="badge badge-new">Featured</span>
                                @endif
                            </div>
                            <div class="product-actions">
                                <button class="action-btn" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="action-btn" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Compare" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>
                            <button class="quick-add-btn" data-product-id="{{ $product->id }}" onclick="quickAddToCart({{ $product->id }}, this);">
                                <i class="fas fa-shopping-cart"></i> Quick Add
                            </button>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <div class="product-vendor">
                                <i class="fas fa-building"></i> {{ $product->vendor->name ?? 'Unknown Vendor' }}
                            </div>
                            <div class="product-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product->rating)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-count">({{ number_format($product->rating, 1) }})</span>
                            </div>
                            <div class="export-info">
                                <div class="moq-info">
                                    <i class="fas fa-boxes"></i>
                                    <span class="moq">MOQ: {{ $product->minimum_order ?? 1 }} units</span>
                                </div>
                                @if($product->supplier_location)
                                <div class="destination-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="destination">{{ $product->supplier_location }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="product-price">
                                <div class="price-group">
                                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                    @if($product->old_price)
                                        <span class="original-price">${{ number_format($product->old_price, 2) }}</span>
                                    @endif
                                    <span class="price-unit">per unit (FOB)</span>
                                </div>
                                @if($product->old_price)
                                    <span class="discount-badge">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                                @endif
                            </div>
                            <button class="btn-add-cart">
                                <i class="fas fa-file-invoice"></i> Request Quote
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No export products found.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination">
                    @if($products->onFirstPage())
                        <button class="page-btn" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="page-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if($page == $products->currentPage())
                            <button class="page-btn active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="page-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="page-btn" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
// Filter functionality
function applyFilters() {
    const categoryFilters = document.querySelectorAll('.category-filter:checked');
    const locationFilters = document.querySelectorAll('.location-filter:checked');
    const moqFilters = document.querySelectorAll('.moq-filter:checked');
    const certificationFilters = document.querySelectorAll('.certification-filter:checked');
    const ratingFilters = document.querySelectorAll('.rating-filter:checked');
    const sortSelect = document.getElementById('sortSelect');

    const params = new URLSearchParams();

    // Categories
    if (categoryFilters.length > 0) {
        const categoryIds = Array.from(categoryFilters).map(cb => cb.value);
        params.set('categories', categoryIds.join(','));
    }

    // Location
    if (locationFilters.length > 0) {
        params.set('location', locationFilters[0].value);
    }

    // MOQ
    if (moqFilters.length > 0) {
        params.set('moq', moqFilters[0].value);
    }

    // Certifications
    if (certificationFilters.length > 0) {
        const certifications = Array.from(certificationFilters).map(cb => cb.value);
        params.set('certifications', certifications.join(','));
    }

    // Rating
    if (ratingFilters.length > 0) {
        params.set('min_rating', ratingFilters[0].value);
    }

    // Preserve price range if set
    const minPriceInput = document.querySelector('.min-price-input');
    const maxPriceInput = document.querySelector('.max-price-input');
    if (minPriceInput && maxPriceInput) {
        const minPrice = minPriceInput.value;
        const maxPrice = maxPriceInput.value;
        if (minPrice != 0 || maxPrice != 10000) {
            params.set('min_price', minPrice);
            params.set('max_price', maxPrice);
        }
    }

    // Sort
    if (sortSelect.value !== 'featured') {
        params.set('sort', sortSelect.value);
    }

    // Redirect with filters
    window.location.href = '{{ route("export") }}?' + params.toString();
}

// Add event listeners to filter checkboxes
document.querySelectorAll('.category-filter, .location-filter, .moq-filter, .certification-filter, .rating-filter').forEach(checkbox => {
    checkbox.addEventListener('change', applyFilters);
});

// Clear all filters
document.querySelector('.clear-filters')?.addEventListener('click', function() {
    window.location.href = '{{ route("export") }}';
});

// Update active filters display
function updateActiveFilters() {
    const activeFilters = document.getElementById('activeFilters');
    activeFilters.innerHTML = '';

    document.querySelectorAll('.category-filter:checked, .location-filter:checked, .moq-filter:checked, .certification-filter:checked, .rating-filter:checked').forEach(checkbox => {
        const label = checkbox.closest('label').querySelector('span:first-of-type').textContent;
        const tag = document.createElement('span');
        tag.className = 'filter-tag';
        tag.innerHTML = label + ' <i class="fas fa-times" onclick="removeFilter(this)"></i>';
        tag.dataset.value = checkbox.value;
        tag.dataset.type = checkbox.className.split('-')[0];
        activeFilters.appendChild(tag);
    });
}

function removeFilter(element) {
    const tag = element.closest('.filter-tag');
    const value = tag.dataset.value;
    const type = tag.dataset.type;

    document.querySelectorAll('.' + type + '-filter').forEach(cb => {
        if (cb.value === value) {
            cb.checked = false;
        }
    });

    applyFilters();
}

// Initialize active filters on page load
document.addEventListener('DOMContentLoaded', updateActiveFilters);

// Price Range Slider Functionality
document.addEventListener('DOMContentLoaded', function() {
    const rangeMin = document.querySelector('.range-min');
    const rangeMax = document.querySelector('.range-max');
    const minInput = document.querySelector('.min-price-input');
    const maxInput = document.querySelector('.max-price-input');
    const applyFilterBtn = document.querySelector('.btn-apply-filter');
    const priceRangeSlider = document.querySelector('.price-range-slider');

    function updateSliderBackground() {
        const min = parseInt(rangeMin.value);
        const max = parseInt(rangeMax.value);
        const percent1 = (min / 10000) * 100;
        const percent2 = (max / 10000) * 100;

        priceRangeSlider.style.background = `linear-gradient(to right, #e0e0e0 ${percent1}%, #FF8C00 ${percent1}%, #FF8C00 ${percent2}%, #e0e0e0 ${percent2}%)`;
    }

    function syncSliderToInput() {
        let min = parseInt(rangeMin.value);
        let max = parseInt(rangeMax.value);

        if (min > max - 100) {
            rangeMin.value = max - 100;
            min = max - 100;
        }

        minInput.value = min;
        maxInput.value = max;
        updateSliderBackground();
    }

    function syncInputToSlider() {
        let min = parseInt(minInput.value) || 0;
        let max = parseInt(maxInput.value) || 10000;

        if (min < 0) min = 0;
        if (max > 10000) max = 10000;
        if (min > max - 100) min = max - 100;

        rangeMin.value = min;
        rangeMax.value = max;
        minInput.value = min;
        maxInput.value = max;
        updateSliderBackground();
    }

    rangeMin.addEventListener('input', syncSliderToInput);
    rangeMax.addEventListener('input', syncSliderToInput);
    minInput.addEventListener('change', syncInputToSlider);
    maxInput.addEventListener('change', syncInputToSlider);

    applyFilterBtn.addEventListener('click', function() {
        const params = new URLSearchParams(window.location.search);

        // Set price range
        if (minInput.value != 0 || maxInput.value != 10000) {
            params.set('min_price', minInput.value);
            params.set('max_price', maxInput.value);
        }

        window.location.href = '{{ route("export") }}?' + params.toString();
    });

    // Initialize
    updateSliderBackground();
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
