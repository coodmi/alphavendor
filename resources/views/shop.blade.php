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
                                <input type="checkbox">
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

                <!-- Vendor Type Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Vendor Type</h3>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Retail</span>
                                <span class="count">(543)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Wholesale</span>
                                <span class="count">(321)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Export</span>
                                <span class="count">(189)</span>
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
                        <p class="results-count">Showing <strong>1-24</strong> of <strong>1,234</strong> results</p>
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
                        Fashion
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                    <span class="filter-tag">
                        Price: $50 - $200
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                    <span class="filter-tag">
                        Nike
                        <button class="remove-filter"><i class="fas fa-times"></i></button>
                    </span>
                </div>

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
                        <button class="page-btn">52</button>
                        <button class="page-btn next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                    <div class="pagination-info">
                        Go to page: <input type="number" min="1" max="52" value="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
