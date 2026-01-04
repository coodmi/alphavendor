@extends('layouts.app')

@section('title', 'Retail - AlphaVendor Multi Vendor Marketplace')

@section('content')
<!-- Breadcrumb -->
{{-- <section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator">/</span>
            <span>Retail</span>
        </nav>
    </div>
</section> --}}

<!-- Hero Banner -->
<section class="retail-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Retail Marketplace</h1>
                <p>Discover thousands of products from trusted retail vendors</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <i class="fas fa-store"></i>
                        <div>
                            <h3>500+</h3>
                            <span>Retail Stores</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-box"></i>
                        <div>
                            <h3>10K+</h3>
                            <span>Products</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <h3>50K+</h3>
                            <span>Happy Customers</span>
                        </div>
                    </div>
                </div>
                <button class="btn-explore">
                    <i class="fas fa-compass"></i> Explore Now
                </button>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=500&fit=crop" alt="Retail Shopping">
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
                                <span>Electronics</span>
                                <span class="count">(234)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Fashion & Apparel</span>
                                <span class="count">(567)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Home & Living</span>
                                <span class="count">(321)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Sports & Outdoor</span>
                                <span class="count">(198)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Beauty & Personal Care</span>
                                <span class="count">(445)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Toys & Games</span>
                                <span class="count">(276)</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Price Range</h3>
                    <div class="price-range-slider">
                        <input type="range" min="0" max="1000" value="0" class="range-min">
                        <input type="range" min="0" max="1000" value="1000" class="range-max">
                    </div>
                    <div class="price-inputs">
                        <div class="price-input">
                            <label>Min</label>
                            <input type="number" value="0" min="0">
                        </div>
                        <div class="price-input">
                            <label>Max</label>
                            <input type="number" value="1000" max="1000">
                        </div>
                    </div>
                    <button class="btn-apply-filter">Apply Filter</button>
                </div>

                <!-- Brands Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Brands</h3>
                    <div class="search-filter">
                        <input type="text" placeholder="Search brands...">
                    </div>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Nike</span>
                                <span class="count">(67)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Adidas</span>
                                <span class="count">(54)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Samsung</span>
                                <span class="count">(92)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Apple</span>
                                <span class="count">(45)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Sony</span>
                                <span class="count">(38)</span>
                            </label>
                        </li>
                    </ul>
                    <button class="show-more-btn">Show More +</button>
                </div>

                <!-- Rating Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Rating</h3>
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
                        <p class="results-count">Showing <strong>1-24</strong> of <strong>1,234</strong> retail products</p>
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
                                <option>Newest First</option>
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
                        Electronics
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                    <span class="filter-tag">
                        Price: $50 - $200
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                </div>

                <!-- Products Grid -->
                <div class="products-grid-view">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="{{ $product['name'] }}">
                            @if($product['badge'])
                            <span class="badge {{ strtolower($product['badge']) }}">{{ $product['badge'] }}</span>
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
                            <div class="product-category">{{ $categories[array_rand($categories)]['name'] ?? 'Electronics' }}</div>
                            <h4>{{ $product['name'] }}</h4>
                            <div class="vendor-name">
                                <i class="fas fa-store"></i> {{ $stores[array_rand($stores)]['name'] ?? 'Retail Store' }}
                            </div>
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($product['rating']))
                                        <i class="fas fa-star"></i>
                                    @elseif($i < $product['rating'])
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span>({{ $product['rating'] }}) {{ $product['reviews'] }} reviews</span>
                            </div>
                            <div class="price">
                                <span class="current-price">${{ $product['price'] }}</span>
                                <span class="old-price">${{ $product['old_price'] }}</span>
                                <span class="discount">-{{ round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
                        <button class="page-btn">25</button>
                        <button class="page-btn next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                    <div class="pagination-info">
                        Go to page: <input type="number" min="1" max="25" value="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
