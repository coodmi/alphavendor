@extends('layouts.app')

@section('title', 'Shop - ' . ($siteSettings->site_name ?? 'AlphaVendor'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endpush

@section('content')
<section class="shop-section">
    <div class="container">
        <!-- Mobile Filter Toggle -->
        <button class="mobile-filter-toggle" id="mobileFilterToggle">
            <i class="fas fa-sliders-h"></i> Filters & Categories
        </button>

        <!-- Filter Overlay -->
        <div class="filter-sidebar-overlay" id="filterOverlay"></div>

        <div class="shop-wrapper">
            <!-- Sidebar -->
            <aside class="shop-sidebar" id="shopSidebar">
                <div class="filter-sidebar-header">
                    <h3>Filters</h3>
                    <button class="filter-close-btn" id="filterCloseBtn">&times;</button>
                </div>

                <!-- Categories -->
                <div class="filter-box">
                    <h3 class="filter-title">Categories</h3>
                    <ul class="filter-list">
                        @foreach($categories as $category)
                        <li>
                            <label class="filter-checkbox">
                                <div style="display:flex;align-items:center;">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                    <span>{{ $category->name }}</span>
                                </div>
                                <span class="count">({{ $category->products_count }})</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range -->
                <div class="filter-box">
                    <h3 class="filter-title">Price Range</h3>
                    <div class="price-range-slider">
                        <input type="range" id="priceMin" min="0" max="10000" value="{{ request('min_price', 0) }}">
                        <input type="range" id="priceMax" min="0" max="10000" value="{{ request('max_price', 10000) }}">
                    </div>
                    <div class="price-inputs">
                        <div class="price-input">
                            <label>Min</label>
                            <input type="number" id="minPriceInput" name="min_price" value="{{ request('min_price', 0) }}">
                        </div>
                        <div class="price-input">
                            <label>Max</label>
                            <input type="number" id="maxPriceInput" name="max_price" value="{{ request('max_price', 10000) }}">
                        </div>
                    </div>
                    <button class="btn-apply-filter" id="applyPriceFilter">Apply Filter</button>
                </div>

                <!-- Brands -->
                <div class="filter-box">
                    <h3 class="filter-title">Brands</h3>
                    <div class="search-filter">
                        <input type="text" id="brandSearch" placeholder="Search brands...">
                    </div>
                    @if($brands->count() > 0)
                    <ul class="filter-list" id="brandList">
                        @foreach($brands as $brand)
                        <li>
                            <label class="filter-checkbox">
                                <div style="display:flex;align-items:center;">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                        {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}>
                                    <span>{{ $brand->name }}</span>
                                </div>
                                <span class="count">({{ $brand->products_count }})</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p style="font-size:13px;color:#9ca3af;">No brands available</p>
                    @endif
                </div>

                <!-- Rating -->
                <div class="filter-box">
                    <h3 class="filter-title">Rating</h3>
                    <ul class="filter-list rating-filter">
                        @for($i = 5; $i >= 1; $i--)
                        <li>
                            <label class="filter-checkbox">
                                <div style="display:flex;align-items:center;">
                                    <input type="radio" name="min_rating" value="{{ $i }}"
                                        {{ request('min_rating') == $i ? 'checked' : '' }}
                                        style="margin-right:10px;width:16px;height:16px;accent-color:var(--primary-color,#0d5c63);">
                                    <span class="rating-stars">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fa{{ $s <= $i ? 's' : 'r' }} fa-star"></i>
                                        @endfor
                                        & Up
                                    </span>
                                </div>
                            </label>
                        </li>
                        @endfor
                    </ul>
                </div>

                <!-- Clear Filters -->
                <a href="{{ route('shop') }}" class="btn-clear-filters">
                    <i class="fas fa-times"></i> Clear All Filters
                </a>
            </aside>

            <!-- Main Content -->
            <div class="shop-content">
                <!-- Toolbar -->
                <div class="shop-toolbar">
                    <div class="toolbar-left">
                        <p class="results-count">
                            Showing <strong>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of <strong>{{ $products->total() }}</strong> products
                        </p>
                    </div>
                    <div class="toolbar-right">
                        <div class="view-mode">
                            <button class="view-btn active" data-view="grid" title="Grid View"><i class="fas fa-th"></i></button>
                            <button class="view-btn" data-view="list" title="List View"><i class="fas fa-list"></i></button>
                        </div>
                        <div class="sort-dropdown">
                            <select id="sortSelect">
                                <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                            </select>
                        </div>
                        <div class="per-page-dropdown">
                            <select id="perPageSelect">
                                <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                                <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid-view" id="productsGrid">
                    @forelse($products as $product)
                    <div class="product-card">
                        <a href="{{ route('product.show', $product->id) }}" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%;">
                            <div class="product-image">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/placeholder.png') }}" alt="{{ $product->name }}" loading="lazy">
                                @if($product->badge)
                                <span class="badge {{ strtolower($product->badge) }}">{{ $product->badge }}</span>
                                @elseif($product->old_price && $product->old_price > $product->price)
                                <span class="badge sale">
                                    -{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                </span>
                                @endif
                            </div>
                            <div class="product-info">
                                <span class="product-category">{{ $product->category->name ?? '' }}</span>
                                <h4>{{ $product->name }}</h4>
                                <div class="vendor-name">
                                    <i class="fas fa-store"></i>
                                    {{ $product->vendor->name ?? 'Unknown' }}
                                </div>
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= round($product->rating) ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                    <span>({{ number_format($product->rating, 1) }}) {{ $product->reviews_count }} reviews</span>
                                </div>
                                <div class="price">
                                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                    @if($product->old_price && $product->old_price > $product->price)
                                    <span class="old-price">${{ number_format($product->old_price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <button class="quick-add-btn" onclick="event.preventDefault(); addToCart({{ $product->id }})">
                            <i class="fas fa-cart-plus"></i> Buy Now
                        </button>
                    </div>
                    @empty
                    <div style="grid-column:1/-1;text-align:center;padding:60px 20px;">
                        <i class="fas fa-box-open" style="font-size:48px;color:#d1d5db;margin-bottom:16px;"></i>
                        <h3 style="font-size:18px;color:#6b7280;margin-bottom:8px;">No products found</h3>
                        <p style="color:#9ca3af;">Try adjusting your filters or search criteria.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-container">
                        <p class="pagination-info">
                            Showing <strong>{{ $products->firstItem() }}</strong> to <strong>{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> results
                        </p>
                        <ul class="pagination-list">
                            {{-- Previous --}}
                            <li class="pagination-item">
                                @if($products->onFirstPage())
                                <span class="pagination-link disabled">&laquo;</span>
                                @else
                                <a href="{{ $products->previousPageUrl() }}" class="pagination-link">&laquo;</a>
                                @endif
                            </li>
                            {{-- Pages --}}
                            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-link {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            {{-- Next --}}
                            <li class="pagination-item">
                                @if($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="pagination-link">&raquo;</a>
                                @else
                                <span class="pagination-link disabled">&raquo;</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('shopSidebar');
    const overlay = document.getElementById('filterOverlay');
    const toggleBtn = document.getElementById('mobileFilterToggle');
    const closeBtn = document.getElementById('filterCloseBtn');

    // Open/Close sidebar
    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Sort & Per Page
    const sortSelect = document.getElementById('sortSelect');
    const perPageSelect = document.getElementById('perPageSelect');

    function updateURL(key, value) {
        const url = new URL(window.location);
        url.searchParams.set(key, value);
        url.searchParams.delete('page');
        window.location = url;
    }

    if (sortSelect) sortSelect.addEventListener('change', () => updateURL('sort', sortSelect.value));
    if (perPageSelect) perPageSelect.addEventListener('change', () => updateURL('per_page', perPageSelect.value));

    // Category & Brand checkboxes
    document.querySelectorAll('input[name="categories[]"], input[name="brands[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const url = new URL(window.location);
            const name = this.name;
            url.searchParams.delete(name);
            document.querySelectorAll(`input[name="${name}"]:checked`).forEach(checked => {
                url.searchParams.append(name, checked.value);
            });
            url.searchParams.delete('page');
            window.location = url;
        });
    });

    // Rating radio
    document.querySelectorAll('input[name="min_rating"]').forEach(r => {
        r.addEventListener('change', function() {
            updateURL('min_rating', this.value);
        });
    });

    // Price filter
    const applyPriceBtn = document.getElementById('applyPriceFilter');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function() {
            const url = new URL(window.location);
            url.searchParams.set('min_price', document.getElementById('minPriceInput').value);
            url.searchParams.set('max_price', document.getElementById('maxPriceInput').value);
            url.searchParams.delete('page');
            window.location = url;
        });
    }

    // Sync range sliders with inputs
    const priceMin = document.getElementById('priceMin');
    const priceMax = document.getElementById('priceMax');
    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');

    if (priceMin && minInput) {
        priceMin.addEventListener('input', () => minInput.value = priceMin.value);
        minInput.addEventListener('input', () => priceMin.value = minInput.value);
    }
    if (priceMax && maxInput) {
        priceMax.addEventListener('input', () => maxInput.value = priceMax.value);
        maxInput.addEventListener('input', () => priceMax.value = maxInput.value);
    }

    // View mode toggle
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const grid = document.getElementById('productsGrid');
            if (this.dataset.view === 'list') {
                grid.classList.add('list-view');
            } else {
                grid.classList.remove('list-view');
            }
        });
    });

    // Brand search
    const brandSearch = document.getElementById('brandSearch');
    if (brandSearch) {
        brandSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('#brandList li').forEach(li => {
                const text = li.textContent.toLowerCase();
                li.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
});

function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success || data.message) {
            window.location.href = '/cart';
        }
    })
    .catch(() => {
        window.location.href = '/product/' + productId;
    });
}
</script>
@endpush
