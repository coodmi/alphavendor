@extends('layouts.app')

@section('title', 'Shop - AlphaVendor Multi Vendor Marketplace')

@section('content')
<!-- Breadcrumb -->
{{-- <section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator">/</span>
            <span>Shop</span>
        </nav>
    </div>
</section> --}}

<!-- Shop Section -->
<section class="shop-section">
    <div class="container">
        <div class="shop-wrapper">
            <!-- Sidebar Filters -->
            <aside class="shop-sidebar">
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

                <!-- Vendor Type Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Vendor Type</h3>
                    <ul class="filter-list">
                        @php
                            $vendorTypeLabels = ['retailer' => 'Retail', 'wholesaler' => 'Wholesale', 'exporter' => 'Export'];
                        @endphp
                        @foreach(['retailer', 'wholesaler', 'exporter'] as $type)
                        @php
                            $vendorType = isset($vendorTypes) ? $vendorTypes->firstWhere('role', $type) : null;
                            $count = $vendorType ? $vendorType->products_count : 0;
                        @endphp
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="vendor-type-checkbox" value="{{ $type }}"
                                    {{ in_array($type, request('vendor_types', [])) ? 'checked' : '' }}>
                                <span>{{ $vendorTypeLabels[$type] }}</span>
                                <span class="count">({{ $count }})</span>
                            </label>
                        </li>
                        @endforeach
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
                                <option value="24" {{ request('per_page', 24) == 24 ? 'selected' : '' }}>Show: 24</option>
                                <option value="36" {{ request('per_page', 24) == 36 ? 'selected' : '' }}>Show: 36</option>
                                <option value="48" {{ request('per_page', 24) == 48 ? 'selected' : '' }}>Show: 48</option>
                                <option value="96" {{ request('per_page', 24) == 96 ? 'selected' : '' }}>Show: 96</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if(request()->hasAny(['categories', 'brands', 'min_price', 'max_price', 'min_rating', 'vendor_types']))
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

                    @if(request('vendor_types'))
                        @foreach(request('vendor_types') as $type)
                        <span class="filter-tag" data-filter-type="vendor_type" data-filter-value="{{ $type }}">
                            {{ ucfirst($type) }}
                            <button class="remove-filter"><i class="fas fa-times"></i></button>
                        </span>
                        @endforeach
                    @endif
                </div>
                @endif

                <!-- Products Grid -->
                <div class="products-grid-view">
                    @forelse($products as $product)
                    <div class="product-card">
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
                                <button class="action-btn" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="action-btn" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="action-btn" title="Compare">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <button class="quick-add-btn">
                                <i class="fas fa-shopping-cart"></i>
                                Quick Add
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
                    </div>
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
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Price Range Slider - Immediate Execution
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing price range');

    const rangeMin = document.querySelector(".range-min");
    const rangeMax = document.querySelector(".range-max");
    const minPriceInput = document.querySelector('.min-price-input');
    const maxPriceInput = document.querySelector('.max-price-input');

    console.log('Elements:', {rangeMin, rangeMax, minPriceInput, maxPriceInput});

    if (!rangeMin || !rangeMax || !minPriceInput || !maxPriceInput) {
        console.error('Price range elements not found!');
        return;
    }

    console.log('All price range elements found successfully');

    // Update min slider
    rangeMin.addEventListener('input', function() {
        let min = parseInt(this.value);
        let max = parseInt(rangeMax.value);

        console.log('Min slider moved:', min);

        if (min > max - 100) {
            min = max - 100;
            this.value = min;
        }

        minPriceInput.value = min;
        console.log('Updated min input to:', minPriceInput.value);
    });

    // Update max slider
    rangeMax.addEventListener('input', function() {
        let min = parseInt(rangeMin.value);
        let max = parseInt(this.value);

        console.log('Max slider moved:', max);

        if (max < min + 100) {
            max = min + 100;
            this.value = max;
        }

        maxPriceInput.value = max;
        console.log('Updated max input to:', maxPriceInput.value);
    });

    // Update min input
    minPriceInput.addEventListener('input', function() {
        let min = parseInt(this.value) || 0;
        let max = parseInt(maxPriceInput.value) || 10000;

        console.log('Min input changed:', min);

        if (min < 0) {
            min = 0;
            this.value = min;
        }
        if (min > max - 100) {
            min = max - 100;
            this.value = min;
        }

        rangeMin.value = min;
        console.log('Updated min slider to:', rangeMin.value);
    });

    // Update max input
    maxPriceInput.addEventListener('input', function() {
        let min = parseInt(minPriceInput.value) || 0;
        let max = parseInt(this.value) || 10000;

        console.log('Max input changed:', max);

        if (max > 10000) {
            max = 10000;
            this.value = max;
        }
        if (max < min + 100) {
            max = min + 100;
            this.value = max;
        }

        rangeMax.value = max;
        console.log('Updated max slider to:', rangeMax.value);
    });

    console.log('Price range event listeners attached successfully');

    // Filter application function
    function triggerFilterApplication() {
        const params = new URLSearchParams(window.location.search);

        const minVal = parseInt(minPriceInput.value) || 0;
        const maxVal = parseInt(maxPriceInput.value) || 10000;

        params.delete('min_price');
        params.delete('max_price');

        if (minVal > 0) {
            params.set('min_price', minVal);
        }
        if (maxVal < 10000) {
            params.set('max_price', maxVal);
        }

        const newUrl = window.location.pathname + '?' + params.toString();
        console.log('Navigating to:', newUrl);
        window.location.href = newUrl;
    }

    // Apply Filter Button Handler
    function attachApplyFilterHandler() {
        const btn = document.querySelector('.btn-apply-filter');
        if (btn) {
            console.log('Apply Filter button found');
            btn.onclick = function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                console.log('Apply Filter clicked! Min:', minPriceInput.value, 'Max:', maxPriceInput.value);
                triggerFilterApplication();
                return false;
            };
            console.log('Apply Filter handler attached');
        } else {
            console.error('Apply Filter button NOT found!');
        }
    }

    // Attach handler immediately and after short delay
    attachApplyFilterHandler();
    setTimeout(attachApplyFilterHandler, 500);
});
</script>
<script src="{{ asset('js/shop.js') }}"></script>
@endpush
