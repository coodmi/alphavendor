@extends('layouts.app')

@section('title', 'Import - AlphaVendor Multi Vendor Marketplace')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/import-mobile.css') }}">
@endpush

@section('content')
<!-- Hero Banner -->
<section class="import-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge"><i class="fas fa-globe"></i> Global Import</span>
                <h1>International Import Marketplace</h1>
                <p>Connect with verified international suppliers and source quality products globally. Import premium products with seamless logistics and documentation support.</p>
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
                        <i class="fas fa-plane-arrival"></i> Start Importing
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-file-contract"></i> View Documentation
                    </button>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1548588627-f978862b85e1?w=1100&h=750&fit=crop" alt="Global Import Cargo Logistics">
            </div>
        </div>
    </div>
</section>

<!-- Shop Section -->
<section class="shop-section">
    <div class="container">
        <!-- Mobile Filter Toggle Button -->
        <button class="mobile-filter-toggle-import" id="mobileFilterToggleImport">
            <i class="fas fa-filter"></i>
            Filters & Categories
        </button>

        <!-- Filter Sidebar Overlay -->
        <div class="filter-sidebar-overlay-import" id="filterSidebarOverlayImport"></div>

        <div class="shop-wrapper">
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar" id="importSidebar">
                <!-- Mobile Filter Header -->
                <div class="filter-sidebar-header-import" style="display: none;">
                    <h3>Filters</h3>
                    <button class="filter-close-btn-import" id="filterCloseBtnImport">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Categories Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Import Categories</h3>
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
                            <span class="text-gray-200">No categories available</span>
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
                            <span class="text-gray-200">No locations available</span>
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
                    <h3 class="filter-title">Import Certifications</h3>
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
                        <p class="text-gray-200 text-sm px-4 py-2">No certifications available</p>
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
                        <p class="results-count">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} import products</p>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($products as $product)
                    <a href="{{ route('product.show', $product->id) }}?quantity={{ $product->minimum_order ?? 1 }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow group block">
                        <div class="relative overflow-hidden aspect-square">
                            @if($product->image)
                                @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @endif
                            @else
                                <img src="https://via.placeholder.com/300x300?text=No+Image" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @endif
                            @if($product->badge)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">{{ $product->badge }}</span>
                            @endif
                            @if($product->is_featured)
                                <span class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded" style="margin-top: {{ $product->badge ? '30px' : '0' }}">Featured</span>
                            @endif
                            <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-teal-600 hover:text-white transition-colors" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-teal-600 hover:text-white transition-colors" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-teal-600 hover:text-white transition-colors" title="Compare" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="flex-1 bg-teal-600 text-white py-3 font-semibold flex items-center justify-center gap-2" data-product-id="{{ $product->id }}" onclick="quickAddToCart({{ $product->id }}, this); event.preventDefault(); event.stopPropagation();">
                                <i class="fas fa-shopping-cart"></i>
                                Quick Add
                            </button>
                            <button class="flex-1 bg-teal-700 text-white py-3 font-semibold flex items-center justify-center gap-2" data-product-id="{{ $product->id }}" onclick="buyNow({{ $product->id }}, this); event.preventDefault(); event.stopPropagation();">
                                <i class="fas fa-bolt"></i>
                                Buy Now
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-teal-700 font-semibold mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h4 class="text-sm font-bold text-white mb-2 line-clamp-2 hover:text-teal-600 transition-colors">{{ $product->name }}</h4>
                            <div class="flex items-center gap-1 text-xs text-gray-100 mb-1">
                                <i class="fas fa-building text-teal-600"></i>
                                <span>{{ $product->vendor->name ?? 'Unknown Vendor' }}</span>
                            </div>
                            @if($product->supplier_location)
                            <div class="flex items-center gap-1 text-xs text-gray-100 mb-2">
                                <i class="fas fa-map-marker-alt text-teal-600"></i>
                                <span>{{ $product->supplier_location }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="fas fa-star text-teal-500 text-xs"></i>
                                    @elseif($i - 0.5 <= $product->rating)
                                        <i class="fas fa-star-half-alt text-teal-500 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-teal-500 text-xs"></i>
                                    @endif
                                @endfor
                                <span class="text-xs text-gray-100">({{ number_format($product->rating, 1) }}) {{ $product->reviews_count }} reviews</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-100 mb-3">
                                <i class="fas fa-boxes text-teal-600"></i>
                                <span>MOQ: {{ $product->minimum_order ?? 1 }} units</span>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                <span class="text-lg font-bold text-white">${{ number_format($product->price, 2) }}</span>
                                @if($product->old_price)
                                    <span class="text-sm text-gray-200 line-through">${{ number_format($product->old_price, 2) }}</span>
                                    <span class="text-xs font-semibold text-red-500">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-200">per unit (FOB)</div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-200 text-lg">No export products found.</p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply Filters Function
    function applyFilters() {
        const url = new URL(window.location.href);
        const params = new URLSearchParams();

        // Category filters (collect all checked categories)
        const categoryCheckboxes = document.querySelectorAll('.category-filter:checked');
        const categoryIds = Array.from(categoryCheckboxes).map(cb => cb.value);
        if (categoryIds.length > 0) {
            params.set('categories', categoryIds.join(','));
        }

        // Location filter
        const locationCheckboxes = document.querySelectorAll('.location-filter:checked');
        locationCheckboxes.forEach(cb => {
            params.set('location', cb.value);
        });

        // Price range
        const minPrice = document.querySelector('.min-price-input').value;
        const maxPrice = document.querySelector('.max-price-input').value;
        if (minPrice && minPrice != 0) {
            params.set('min_price', minPrice);
        }
        if (maxPrice && maxPrice != 10000) {
            params.set('max_price', maxPrice);
        }

        // MOQ filter (only one can be selected at a time)
        const moqCheckbox = document.querySelector('.moq-filter:checked');
        if (moqCheckbox) {
            params.set('moq', moqCheckbox.value);
        }

        // Certification filters
        const certCheckboxes = document.querySelectorAll('.certification-filter:checked');
        const certIds = Array.from(certCheckboxes).map(cb => cb.value);
        if (certIds.length > 0) {
            params.set('certifications', certIds.join(','));
        }

        // Rating filter
        const ratingRadio = document.querySelector('.rating-filter:checked');
        if (ratingRadio) {
            params.set('min_rating', ratingRadio.value);
        }

        // Sort
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect && sortSelect.value !== 'featured') {
            params.set('sort', sortSelect.value);
        }

        // Redirect with filters
        window.location.href = url.pathname + '?' + params.toString();
    }

    // Category filters
    document.querySelectorAll('.category-filter').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });

    // Location filters
    document.querySelectorAll('.location-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Uncheck other location checkboxes (single selection)
            document.querySelectorAll('.location-filter').forEach(cb => {
                if (cb !== this) cb.checked = false;
            });
            applyFilters();
        });
    });

    // MOQ filters
    document.querySelectorAll('.moq-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Uncheck other MOQ checkboxes (single selection)
            document.querySelectorAll('.moq-filter').forEach(cb => {
                if (cb !== this) cb.checked = false;
            });
            applyFilters();
        });
    });

    // Certification filters
    document.querySelectorAll('.certification-filter').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });

    // Rating filters
    document.querySelectorAll('.rating-filter').forEach(radio => {
        radio.addEventListener('change', applyFilters);
    });

    // Price range apply button
    const applyPriceBtn = document.querySelector('.btn-apply-filter');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', applyFilters);
    }

    // Price range sliders
    const rangeMin = document.querySelector('.range-min');
    const rangeMax = document.querySelector('.range-max');
    const minPriceInput = document.querySelector('.min-price-input');
    const maxPriceInput = document.querySelector('.max-price-input');

    if (rangeMin && rangeMax) {
        rangeMin.addEventListener('input', function() {
            if (parseInt(this.value) > parseInt(rangeMax.value)) {
                this.value = rangeMax.value;
            }
            minPriceInput.value = this.value;
        });

        rangeMax.addEventListener('input', function() {
            if (parseInt(this.value) < parseInt(rangeMin.value)) {
                this.value = rangeMin.value;
            }
            maxPriceInput.value = this.value;
        });

        minPriceInput.addEventListener('input', function() {
            rangeMin.value = this.value;
        });

        maxPriceInput.addEventListener('input', function() {
            rangeMax.value = this.value;
        });
    }

    // Clear all filters
    const clearFiltersBtn = document.querySelector('.clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
    }

    // Set sort value from URL
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        const urlParams = new URLSearchParams(window.location.search);
        const sortValue = urlParams.get('sort');
        if (sortValue) {
            sortSelect.value = sortValue;
        }
    }
});

// Make applyFilters available globally for inline onchange handlers
function applyFilters() {
    const url = new URL(window.location.href);
    const params = new URLSearchParams();

    // Category filters (collect all checked categories)
    const categoryCheckboxes = document.querySelectorAll('.category-filter:checked');
    const categoryIds = Array.from(categoryCheckboxes).map(cb => cb.value);
    if (categoryIds.length > 0) {
        params.set('categories', categoryIds.join(','));
    }

    // Location filter
    const locationCheckboxes = document.querySelectorAll('.location-filter:checked');
    locationCheckboxes.forEach(cb => {
        params.set('location', cb.value);
    });

    // Price range
    const minPrice = document.querySelector('.min-price-input').value;
    const maxPrice = document.querySelector('.max-price-input').value;
    if (minPrice && minPrice != 0) {
        params.set('min_price', minPrice);
    }
    if (maxPrice && maxPrice != 10000) {
        params.set('max_price', maxPrice);
    }

    // MOQ filter
    const moqCheckbox = document.querySelector('.moq-filter:checked');
    if (moqCheckbox) {
        params.set('moq', moqCheckbox.value);
    }

    // Certification filters
    const certCheckboxes = document.querySelectorAll('.certification-filter:checked');
    const certIds = Array.from(certCheckboxes).map(cb => cb.value);
    if (certIds.length > 0) {
        params.set('certifications', certIds.join(','));
    }

    // Rating filter
    const ratingRadio = document.querySelector('.rating-filter:checked');
    if (ratingRadio) {
        params.set('min_rating', ratingRadio.value);
    }

    // Sort
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect && sortSelect.value !== 'featured') {
        params.set('sort', sortSelect.value);
    }

    // Redirect with filters
    window.location.href = url.pathname + '?' + params.toString();
}
</script>

<script>
// Mobile Filter Toggle Functionality for Import/Export
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterToggle = document.getElementById('mobileFilterToggleImport');
    const importSidebar = document.getElementById('importSidebar');
    const filterSidebarOverlay = document.getElementById('filterSidebarOverlayImport');
    const filterCloseBtn = document.getElementById('filterCloseBtnImport');
    const filterHeader = document.querySelector('.filter-sidebar-header-import');

    // Show filter header on mobile
    function updateFilterHeader() {
        if (window.innerWidth <= 1024 && filterHeader) {
            filterHeader.style.display = 'flex';
        } else if (filterHeader) {
            filterHeader.style.display = 'none';
        }
    }

    updateFilterHeader();
    window.addEventListener('resize', updateFilterHeader);

    function openFilters() {
        if (importSidebar && filterSidebarOverlay) {
            importSidebar.classList.add('active');
            filterSidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeFilters() {
        if (importSidebar && filterSidebarOverlay) {
            importSidebar.classList.remove('active');
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
            if (window.innerWidth <= 1024) {
                setTimeout(closeFilters, 300);
            }
        });
    }

    // Close filters when clearing all
    const clearFiltersBtn = document.querySelector('.clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                setTimeout(closeFilters, 300);
            }
        });
    }
});

// Quick Add to Cart function
function quickAddToCart(productId, button) {
    const originalContent = button.innerHTML;
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
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.style.background = '#27ae60';
            showToast('Product added to cart!', 'success');
            updateCartBadge(data.cartCount);
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

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; top: 20px; right: 20px;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white; padding: 15px 25px; border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 10000;
        font-size: 16px; font-weight: 500;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.querySelector('.action-link .fas.fa-shopping-bag')?.parentElement.querySelector('span');
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            const cartLink = document.querySelector('.action-link .fas.fa-shopping-bag')?.parentElement;
            if (cartLink) {
                const newBadge = document.createElement('span');
                newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #0d5c63; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
                newBadge.textContent = count;
                cartLink.appendChild(newBadge);
            }
        }
    }
}
</script>
@endpush
@endsection
