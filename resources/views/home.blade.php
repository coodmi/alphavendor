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

<!-- Promotional Banners Section -->
@if($banners->count() > 0)
<section class="py-10 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Special Offers</h2>
            <a href="{{ route('shop') }}" class="text-orange-500 hover:text-orange-600 flex items-center gap-2 font-medium transition-colors">
                View All Offers
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($banners->count() == 1)
            {{-- Single Banner - Full Width --}}
            <a href="{{ $banners->first()->link ?? '#' }}" class="block group">
                <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                    <img src="{{ $banners->first()->image_url }}" alt="{{ $banners->first()->title ?? 'Banner' }}" class="w-full h-48 md:h-72 lg:h-96 object-cover group-hover:scale-105 transition-transform duration-500">
                    @if($banners->first()->title)
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                        <h3 class="text-white text-xl md:text-2xl font-bold p-4 md:p-6">{{ $banners->first()->title }}</h3>
                    </div>
                    @endif
                </div>
            </a>
        @elseif($banners->count() == 2)
            {{-- Two Banners - Side by Side --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                @foreach($banners as $banner)
                <a href="{{ $banner->link ?? '#' }}" class="block group">
                    <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-48 md:h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($banner->title)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                            <h3 class="text-white text-lg md:text-xl font-bold p-4">{{ $banner->title }}</h3>
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @elseif($banners->count() == 3)
            {{-- Three Banners - One Large + Two Small --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <a href="{{ $banners[0]->link ?? '#' }}" class="block group md:row-span-2">
                    <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow h-full">
                        <img src="{{ $banners[0]->image_url }}" alt="{{ $banners[0]->title ?? 'Banner' }}" class="w-full h-48 md:h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($banners[0]->title)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                            <h3 class="text-white text-lg md:text-2xl font-bold p-4 md:p-6">{{ $banners[0]->title }}</h3>
                        </div>
                        @endif
                    </div>
                </a>
                @foreach($banners->slice(1) as $banner)
                <a href="{{ $banner->link ?? '#' }}" class="block group">
                    <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-48 md:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($banner->title)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                            <h3 class="text-white text-lg font-bold p-4">{{ $banner->title }}</h3>
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @else
            {{-- Four+ Banners - Grid with Auto-Scroll on Mobile --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($banners->take(4) as $banner)
                <a href="{{ $banner->link ?? '#' }}" class="block group">
                    <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}" class="w-full h-48 md:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($banner->title)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                            <h3 class="text-white text-lg font-bold p-4">{{ $banner->title }}</h3>
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif

<!-- Retailer Products Section -->
<section class="py-10 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-store text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Retail Products</h2>
                    <p class="text-gray-500 text-sm">Top picks from our retailers</p>
                </div>
            </div>
            <a href="{{ route('retail') }}" class="text-blue-500 hover:text-blue-600 flex items-center gap-2 font-medium transition-colors">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($retailerProducts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($retailerProducts as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100">
                <div class="relative overflow-hidden">
                    @php
                        $imageUrl = $product->image
                            ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                    @if($product->old_price && $product->old_price > $product->price)
                        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                            -{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                        </span>
                    @endif
                    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors">
                            <i class="far fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    <button class="w-full py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No retail products available yet</p>
        </div>
        @endif
    </div>
</section>

<!-- Wholesaler Products Section -->
<section class="py-10 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-warehouse text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Wholesale Products</h2>
                    <p class="text-gray-500 text-sm">Bulk deals from wholesalers</p>
                </div>
            </div>
            <a href="{{ route('wholesale') }}" class="text-green-500 hover:text-green-600 flex items-center gap-2 font-medium transition-colors">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($wholesalerProducts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wholesalerProducts as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100">
                <div class="relative overflow-hidden">
                    @php
                        $imageUrl = $product->image
                            ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                    @if($product->minimum_order)
                        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                            Min: {{ $product->minimum_order }} pcs
                        </span>
                    @endif
                    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-green-500 hover:text-white transition-colors">
                            <i class="far fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->supplier_location)
                    <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i> {{ $product->supplier_location }}
                    </p>
                    @endif
                    <button class="w-full py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-medium hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-phone"></i> Contact Supplier
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No wholesale products available yet</p>
        </div>
        @endif
    </div>
</section>

<!-- Exporter Products Section -->
<section class="py-10 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-globe text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Export Products</h2>
                    <p class="text-gray-500 text-sm">Global trade opportunities</p>
                </div>
            </div>
            <a href="{{ route('export') }}" class="text-purple-500 hover:text-purple-600 flex items-center gap-2 font-medium transition-colors">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($exporterProducts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($exporterProducts as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100">
                <div class="relative overflow-hidden">
                    @php
                        $imageUrl = $product->image
                            ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                    <span class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-lg flex items-center gap-1">
                        <i class="fas fa-globe-americas"></i> Export Ready
                    </span>
                    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-purple-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-purple-500 hover:text-white transition-colors">
                            <i class="far fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xl font-bold text-purple-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->supplier_location)
                    <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                        <i class="fas fa-shipping-fast"></i> Ships from {{ $product->supplier_location }}
                    </p>
                    @endif
                    <button class="w-full py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg font-medium hover:from-purple-600 hover:to-purple-700 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-envelope"></i> Request Quote
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No export products available yet</p>
        </div>
        @endif
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
