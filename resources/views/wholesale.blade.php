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
                <!-- Categories Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Categories</h3>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Apparel & Fashion</span>
                                <span class="count">(10,500)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Electronics</span>
                                <span class="count">(8,200)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Home & Garden</span>
                                <span class="count">(12,800)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Furniture</span>
                                <span class="count">(5,400)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Food & Beverage</span>
                                <span class="count">(7,600)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Health & Beauty</span>
                                <span class="count">(9,300)</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Price Range</h3>
                    <div class="price-range-slider">
                        <input type="range" min="0" max="10000" value="0" class="range-min">
                        <input type="range" min="0" max="10000" value="10000" class="range-max">
                    </div>
                    <div class="price-inputs">
                        <div class="price-input">
                            <label>Min</label>
                            <input type="number" value="0" min="0">
                        </div>
                        <div class="price-input">
                            <label>Max</label>
                            <input type="number" value="10000" max="10000">
                        </div>
                    </div>
                    <button class="btn-apply-filter">Apply Filter</button>
                </div>

                <!-- Minimum Order Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Minimum Order</h3>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>10-50 Units</span>
                                <span class="count">(234)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>50-100 Units</span>
                                <span class="count">(187)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>100-500 Units</span>
                                <span class="count">(156)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>500+ Units</span>
                                <span class="count">(98)</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Supplier Location Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Supplier Location</h3>
                    <div class="search-filter">
                        <input type="text" placeholder="Search location...">
                    </div>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>China</span>
                                <span class="count">(450)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>India</span>
                                <span class="count">(320)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Taiwan</span>
                                <span class="count">(210)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>South Korea</span>
                                <span class="count">(180)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>USA</span>
                                <span class="count">(145)</span>
                            </label>
                        </li>
                    </ul>
                    <button class="show-more-btn">Show More +</button>
                </div>

                <!-- Rating Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Supplier Rating</h3>
                    <ul class="filter-list rating-filter">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                                <span class="count">(123)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </span>
                                <span>& Up</span>
                                <span class="count">(267)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                </span>
                                <span>& Up</span>
                                <span class="count">(445)</span>
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
                        <p class="results-count">Showing <strong>1-24</strong> of <strong>2,845</strong> wholesale products</p>
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
                                <option>Sort by: Default</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Minimum Order: Low to High</option>
                                <option>Best Rating</option>
                                <option>Most Popular</option>
                            </select>
                        </div>
                        <div class="per-page-dropdown">
                            <select>
                                <option>Show: 24</option>
                                <option>Show: 36</option>
                                <option>Show: 48</option>
                                <option>Show: 96</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                <div class="active-filters">
                    <span class="filter-tag">
                        Apparel & Fashion
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                    <span class="filter-tag">
                        Price: $100 - $5000
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                </div>

                <!-- Products Grid -->
                <div class="products-grid-view">
                    @for($i = 1; $i <= 24; $i++)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="Wholesale Product {{ $i }}">
                            @if($i % 3 == 0)
                            <span class="badge hot">Hot Deal</span>
                            @elseif($i % 4 == 0)
                            <span class="badge new">New</span>
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
                            <div class="product-category">Electronics</div>
                            <h4>Wholesale Product {{ $i }}</h4>
                            <div class="vendor-name">
                                <i class="fas fa-industry"></i> Global Manufacturer Co.
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span>(4.5) 250 reviews</span>
                            </div>
                            <div class="wholesale-info">
                                <span class="min-order"><i class="fas fa-boxes"></i> Min: 50 units</span>
                            </div>
                            <div class="price">
                                <span class="current-price">${{ 50 + ($i * 10) }}</span>
                                <span class="old-price">${{ 80 + ($i * 10) }}</span>
                                <span class="discount">-30%</span>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <nav class="pagination">
                        <button class="page-btn prev" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">4</button>
                        <button class="page-btn">5</button>
                        <span class="page-dots">...</span>
                        <button class="page-btn">35</button>
                        <button class="page-btn next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                    <div class="pagination-info">
                        Go to page: <input type="number" min="1" max="35" value="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
