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
                                <input type="checkbox" name="category" value="{{ $category->id }}" class="category-filter">
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
                                <input type="checkbox" name="location" value="{{ $location }}" class="location-filter">
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
                    <div class="price-range">
                        <input type="range" min="0" max="10000" value="5000" class="range-slider">
                        <div class="price-labels">
                            <span>$0</span>
                            <span>$10,000+</span>
                        </div>
                    </div>
                </div>

                <!-- Minimum Order Quantity Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Minimum Order (MOQ)</h3>
                    <ul class="filter-list">
                        @foreach($moqRanges as $moq)
                        @if($moq['count'] > 0)
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="moq" value="{{ $moq['range'] }}" class="moq-filter">
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
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>ISO Certified</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>CE Certified</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>FDA Approved</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Export License</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Supplier Rating Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Exporter Rating</h3>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span><i class="fas fa-star"></i> 4.5+ Stars</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span><i class="fas fa-star"></i> 4.0+ Stars</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
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
                                <button class="action-btn" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="action-btn" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Compare">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>
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

    // Sort
    if (sortSelect.value !== 'featured') {
        params.set('sort', sortSelect.value);
    }

    // Redirect with filters
    window.location.href = '{{ route("export") }}?' + params.toString();
}

// Add event listeners to filter checkboxes
document.querySelectorAll('.category-filter, .location-filter, .moq-filter').forEach(checkbox => {
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

    document.querySelectorAll('.category-filter:checked, .location-filter:checked, .moq-filter:checked').forEach(checkbox => {
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
</script>
@endsection
