@extends('layouts.app')

@section('title', 'Home - AlphaVendor Multi Vendor Marketplace')

@section('content')
<!-- Hero Slider -->
<section class="hero-slider">
    <div class="slider-container">
        <div class="slide active">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&h=600&fit=crop" alt="Slide 1">
            <div class="slide-content">
                <h2>Welcome to AlphaVendor</h2>
                <p>Your trusted multi-vendor marketplace</p>
                <a href="#" class="btn-primary">Shop Now</a>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=1600&h=600&fit=crop" alt="Slide 2">
            <div class="slide-content">
                <h2>Discover Amazing Deals</h2>
                <p>Connect with trusted vendors worldwide</p>
                <a href="#" class="btn-primary">Explore Now</a>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1600&h=600&fit=crop" alt="Slide 3">
            <div class="slide-content">
                <h2>Retail, Wholesale & Export</h2>
                <p>Everything you need in one place</p>
                <a href="#" class="btn-primary">Get Started</a>
            </div>
        </div>
    </div>
    <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
    <div class="slider-dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
</section>

<!-- Popular Categories -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Popular Categories</h2>
            <a href="#" class="view-all">
                Browse All Categories
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="categories-grid">
            @foreach($categories as $category)
            <div class="category-card">
                <div class="category-image">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=300&h=200&fit=crop" alt="{{ $category['name'] }}">
                </div>
                <h3>{{ $category['name'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Today Deals -->
<section class="deals-section">
    <div class="container">
        <div class="section-header">
            <h2>Today Deals</h2>
            <a href="#" class="view-all">
                More Products
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="products-grid">
            @for($i = 1; $i <= 8; $i++)
            <div class="product-card">
                <div class="product-image">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop" alt="Product {{ $i }}">
                    <span class="badge sale">-20%</span>
                    <div class="product-actions">
                        <button class="action-btn"><i class="far fa-heart"></i></button>
                        <button class="action-btn"><i class="far fa-eye"></i></button>
                    </div>
                </div>
                <div class="product-info">
                    <h4>Product Name {{ $i }}</h4>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span>(4.5)</span>
                    </div>
                    <div class="price">
                        <span class="current-price">$79.99</span>
                        <span class="old-price">$99.99</span>
                    </div>
                    <button class="btn-cart"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Featured Vendors -->
<section class="vendors-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Vendors</h2>
            <a href="#" class="view-all">
                View All Vendors
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="vendors-grid">
            @foreach(['Retail Store', 'Wholesale Hub', 'Export Experts', 'Fashion World'] as $vendor)
            <div class="vendor-card">
                <div class="vendor-header">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($vendor) }}&size=80&background=FFA500&color=fff" alt="{{ $vendor }}">
                    <div class="vendor-info">
                        <h3>{{ $vendor }}</h3>
                        <div class="vendor-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span>5.0 (234 reviews)</span>
                        </div>
                    </div>
                </div>
                <p>Trusted vendor with wide range of quality products</p>
                <a href="#" class="btn-vendor">Visit Store</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-text">
                <h2>Subscribe to Our Newsletter</h2>
                <p>Get the latest deals and updates delivered to your inbox</p>
            </div>
            <div class="newsletter-form">
                <input type="email" placeholder="Enter your email address">
                <button type="submit" class="btn-subscribe">Subscribe</button>
            </div>
        </div>
    </div>
</section>
@endsection
