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

                    <!-- Supplier Location Filter -->
                    <div class="filter-box">
                        <h3 class="filter-title">Supplier Location</h3>
                        <ul class="filter-list">
                            @foreach($supplierLocations as $location)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="radio" name="supplier_location" value="{{ $location }}" {{ request('supplier_location') == $location ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>{{ $location }}</span>
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
                @if(request()->hasAny(['category', 'minimum_order', 'supplier_location', 'brand']))
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