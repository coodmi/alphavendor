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
                        Price: ৳50 - ৳200
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
                                <span class="current-price">{{ currency($product['price']) }}</span>
                                <span class="old-price">{{ currency($product['old_price']) }}</span>
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
