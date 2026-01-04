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
                            <h3>150+</h3>
                            <span>Countries</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-ship"></i>
                        <div>
                            <h3>5K+</h3>
                            <span>Shipments</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-handshake"></i>
                        <div>
                            <h3>2K+</h3>
                            <span>Verified Buyers</span>
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
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Agricultural Products</span>
                                <span class="count">(189)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Textiles & Garments</span>
                                <span class="count">(456)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Machinery & Equipment</span>
                                <span class="count">(234)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Chemicals & Pharmaceuticals</span>
                                <span class="count">(167)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Electronics & Tech</span>
                                <span class="count">(389)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Handicrafts & Artworks</span>
                                <span class="count">(298)</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Export Destination Filter -->
                <div class="filter-box">
                    <h3 class="filter-title">Export Destination</h3>
                    <ul class="filter-list">
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>North America</span>
                                <span class="count">(45)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Europe</span>
                                <span class="count">(67)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Middle East</span>
                                <span class="count">(38)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Asia Pacific</span>
                                <span class="count">(89)</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>Latin America</span>
                                <span class="count">(34)</span>
                            </label>
                        </li>
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
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>100 - 500 units</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>500 - 1000 units</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>1000 - 5000 units</span>
                            </label>
                        </li>
                        <li>
                            <label class="filter-checkbox">
                                <input type="checkbox">
                                <span>5000+ units</span>
                            </label>
                        </li>
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
                        <p class="results-count">Showing 1-24 of 1,247 export products</p>
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
                        <select class="sort-select">
                            <option>Sort by: Featured</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>MOQ: Low to High</option>
                            <option>Newest First</option>
                            <option>Best Rating</option>
                        </select>
                        <select class="per-page-select">
                            <option>Show: 24</option>
                            <option>Show: 48</option>
                            <option>Show: 96</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters -->
                <div class="active-filters">
                    <span class="filter-tag">Textiles & Garments <i class="fas fa-times"></i></span>
                    <span class="filter-tag">Europe <i class="fas fa-times"></i></span>
                    <span class="filter-tag">ISO Certified <i class="fas fa-times"></i></span>
                </div>

                <!-- Products Grid -->
                <div class="products-grid">
                    @for ($i = 1; $i <= 24; $i++)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="Export Product {{ $i }}">
                            <div class="product-badges">
                                @if ($i % 3 == 0)
                                    <span class="badge badge-hot">Export Ready</span>
                                @endif
                                @if ($i % 5 == 0)
                                    <span class="badge badge-new">Certified</span>
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
                            <div class="product-category">Agricultural Products</div>
                            <h3 class="product-title">Premium Export Quality Product {{ $i }}</h3>
                            <div class="product-vendor">
                                <i class="fas fa-building"></i> International Exporters Inc.
                            </div>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-count">(4.5)</span>
                            </div>
                            <div class="export-info">
                                <div class="moq-info">
                                    <i class="fas fa-boxes"></i>
                                    <span class="moq">MOQ: 500 units</span>
                                </div>
                                <div class="destination-info">
                                    <i class="fas fa-globe"></i>
                                    <span class="destination">Ships to: Europe, USA</span>
                                </div>
                            </div>
                            <div class="product-price">
                                <div class="price-group">
                                    <span class="current-price">${{ 50 + ($i * 5) }}</span>
                                    <span class="original-price">${{ 70 + ($i * 5) }}</span>
                                    <span class="price-unit">per unit (FOB)</span>
                                </div>
                                <span class="discount-badge">-{{ 15 + ($i % 10) }}%</span>
                            </div>
                            <button class="btn-add-cart">
                                <i class="fas fa-file-invoice"></i> Request Quote
                            </button>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="page-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">4</button>
                    <button class="page-btn">5</button>
                    <span class="page-dots">...</span>
                    <button class="page-btn">52</button>
                    <button class="page-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
