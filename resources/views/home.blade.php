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
<!-- Popular Categories -->
<section class="py-8 md:py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Popular Categories</h2>
            <a href="{{ route('shop') }}" class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
                Browse All Categories
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        @if($categories->count() > 0)
        <!-- Carousel Container -->
        <div class="relative">
            <!-- Left Arrow -->
            <button onclick="scrollCategoriesLeft()" id="catPrevBtn" class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-600 hover:text-yellow-500 hover:bg-yellow-50 hover:shadow-xl transition-all border border-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <!-- Categories Grid - Scrollable with 3 cards per click -->
            <div class="overflow-hidden mx-6">
                <div id="categoriesSlider" class="flex gap-3 md:gap-4 transition-transform duration-500 ease-in-out">
                    @foreach($categories as $category)
                    <a href="{{ route('shop') }}?category={{ $category->slug }}" class="block flex-shrink-0" style="width: calc((100% - 5 * 1rem) / 6);">
                        <div class="bg-white border border-gray-100 rounded-xl p-3 md:p-4 text-center cursor-pointer hover:border-yellow-400 hover:shadow-lg transition-all duration-300 group">
                            <div class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-2 rounded-lg overflow-hidden border-2 border-gray-100 group-hover:border-yellow-400 transition-colors">
                                @php
                                    $catImage = $category->image 
                                        ? (str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image))
                                        : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200&h=200&fit=crop';
                                @endphp
                                <img src="{{ $catImage }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-xs md:text-sm font-semibold text-gray-700 group-hover:text-yellow-600 transition-colors truncate">{{ $category->name }}</h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Arrow -->
            <button onclick="scrollCategoriesRight()" id="catNextBtn" class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-600 hover:text-yellow-500 hover:bg-yellow-50 hover:shadow-xl transition-all border border-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <!-- Dots Indicator -->
            @if($categories->count() > 6)
            <div class="flex justify-center gap-2 mt-4" id="categoryDots">
                <!-- Dots will be generated by JavaScript -->
            </div>
            @endif
        </div>
        @else
        <div class="text-center py-8 bg-gray-50 rounded-xl">
            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No categories available</p>
        </div>
        @endif
    </div>
</section>

<script>
let currentCategoryPosition = 0;
const totalCategories = {{ $categories->count() }};
const cardsPerClick = 3;
const visibleCards = 6;
const maxPosition = Math.max(0, totalCategories - visibleCards);

function getCardWidth() {
    const slider = document.getElementById('categoriesSlider');
    if (slider && slider.children.length > 0) {
        const card = slider.children[0];
        const style = window.getComputedStyle(card);
        const gap = 16; // gap-4 = 1rem = 16px
        return card.offsetWidth + gap;
    }
    return 0;
}

function updateCategorySlider() {
    const slider = document.getElementById('categoriesSlider');
    if (slider) {
        const cardWidth = getCardWidth();
        slider.style.transform = `translateX(-${currentCategoryPosition * cardWidth}px)`;
    }
    
    // Update button states
    const prevBtn = document.getElementById('catPrevBtn');
    const nextBtn = document.getElementById('catNextBtn');
    if (prevBtn) prevBtn.disabled = currentCategoryPosition === 0;
    if (nextBtn) nextBtn.disabled = currentCategoryPosition >= maxPosition;
    
    // Update dots
    updateCategoryDots();
}

function updateCategoryDots() {
    const dotsContainer = document.getElementById('categoryDots');
    if (!dotsContainer) return;
    
    const totalDots = Math.ceil((totalCategories - visibleCards + cardsPerClick) / cardsPerClick);
    const currentDot = Math.floor(currentCategoryPosition / cardsPerClick);
    
    dotsContainer.innerHTML = '';
    for (let i = 0; i < totalDots; i++) {
        const dot = document.createElement('button');
        dot.className = `cat-dot w-2 h-2 rounded-full transition-all ${i === currentDot ? 'bg-yellow-500 w-6' : 'bg-gray-300 hover:bg-gray-400'}`;
        dot.onclick = () => goToCategorySlide(i);
        dotsContainer.appendChild(dot);
    }
}

function scrollCategoriesLeft() {
    if (currentCategoryPosition > 0) {
        currentCategoryPosition = Math.max(0, currentCategoryPosition - cardsPerClick);
        updateCategorySlider();
    }
}

function scrollCategoriesRight() {
    if (currentCategoryPosition < maxPosition) {
        currentCategoryPosition = Math.min(maxPosition, currentCategoryPosition + cardsPerClick);
        updateCategorySlider();
    }
}

function goToCategorySlide(index) {
    currentCategoryPosition = Math.min(maxPosition, index * cardsPerClick);
    updateCategorySlider();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCategorySlider();
});
</script>

<!-- Today Deals -->
<section class="py-10 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Today's Deals</h2>
            <a href="{{ route('shop') }}" class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
                More Products
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        @if($todayDeals->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($todayDeals as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100">
                <div class="relative overflow-hidden">
                    @php
                        $imageUrl = $product->image 
                            ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                            : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-40 md:h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                    @if($product->old_price && $product->old_price > $product->price)
                        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                            -{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                        </span>
                    @endif
                    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                            <i class="far fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                <div class="p-3 md:p-4">
                    <h4 class="font-semibold text-gray-800 mb-1 md:mb-2 text-sm md:text-base line-clamp-2">{{ $product->name }}</h4>
                    <div class="flex items-center gap-1 mb-1 md:mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-500 text-xs ml-1">({{ number_format($product->rating ?? 4.5, 1) }})</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2 md:mb-3">
                        <span class="text-lg md:text-xl font-bold text-yellow-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-xs md:text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    <button class="w-full py-2 md:py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg font-medium hover:from-yellow-600 hover:to-amber-600 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- See More Button -->
        <div class="text-center mt-8">
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-yellow-500 hover:bg-amber-600 text-white font-semibold rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
                <span>See More Products</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No deals available at the moment</p>
            <a href="{{ route('shop') }}" class="inline-block mt-4 px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                Browse All Products
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Promotional Banners Section -->
@if($banners->count() > 0)
<section class="py-10 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Special Offers</h2>
            <a href="{{ route('shop') }}" class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
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

<!-- Hero Promo Banner Section (Text Left, Image Right) -->
@if(isset($promoBanners) && $promoBanners->count() > 0)
@php $promoBanner = $promoBanners->first(); @endphp
<section class="py-10 md:py-16" style="background-color: {{ $promoBanner->background_color ?? '#FFA500' }};">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
            <!-- Text Content (Left) -->
            <div class="w-full md:w-1/2 text-center md:text-left" style="color: {{ $promoBanner->text_color ?? '#FFFFFF' }};">
                @if($promoBanner->subtitle)
                <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold mb-4" style="background-color: rgba(255,255,255,0.2);">
                    {{ $promoBanner->subtitle }}
                </span>
                @endif
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                    {{ $promoBanner->title }}
                </h2>
                @if($promoBanner->description)
                <p class="text-lg md:text-xl mb-6 opacity-90 leading-relaxed">
                    {{ $promoBanner->description }}
                </p>
                @endif
                @if($promoBanner->button_text && $promoBanner->button_link)
                <a href="{{ $promoBanner->button_link }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    {{ $promoBanner->button_text }}
                    <i class="fas fa-arrow-right"></i>
                </a>
                @endif
            </div>
            <!-- Image (Right) -->
            <div class="w-full md:w-1/2">
                <div class="relative">
                    <img src="{{ $promoBanner->image_url }}" alt="{{ $promoBanner->title }}" class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white rounded-xl shadow-lg flex items-center justify-center" style="color: {{ $promoBanner->background_color ?? '#FFA500' }};">
                        <div class="text-center">
                            <span class="block text-2xl font-bold">50%</span>
                            <span class="text-xs font-semibold">OFF</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Retailer Products Section -->
<section class="py-10 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-store text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Retail Products</h2>
                    <p class="text-gray-500 text-sm">Top picks from our retailers</p>
                </div>
            </div>
            <a href="{{ route('retail') }}" class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
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
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
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
                        <span class="text-xl font-bold text-yellow-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    <button class="w-full py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg font-medium hover:from-yellow-600 hover:to-amber-600 transition-all flex items-center justify-center gap-2">
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
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-warehouse text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Wholesale Products</h2>
                    <p class="text-gray-500 text-sm">Bulk deals from wholesalers</p>
                </div>
            </div>
            <a href="{{ route('wholesale') }}" class="text-emerald-500 hover:text-teal-600 flex items-center gap-2 font-medium transition-colors">
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
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-colors">
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
                        <span class="text-xl font-bold text-emerald-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->supplier_location)
                    <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i> {{ $product->supplier_location }}
                    </p>
                    @endif
                    <button class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg font-medium hover:from-emerald-600 hover:to-teal-600 transition-all flex items-center justify-center gap-2">
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
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-globe text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Export Products</h2>
                    <p class="text-gray-500 text-sm">Global trade opportunities</p>
                </div>
            </div>
            <a href="{{ route('export') }}" class="text-orange-500 hover:text-red-600 flex items-center gap-2 font-medium transition-colors">
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
                    <span class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-lg flex items-center gap-1">
                        <i class="fas fa-globe-americas"></i> Export Ready
                    </span>
                    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-orange-500 hover:text-white transition-colors">
                            <i class="far fa-heart text-sm"></i>
                        </button>
                        <button class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-orange-500 hover:text-white transition-colors">
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
                        <span class="text-xl font-bold text-orange-600">${{ number_format($product->price, 2) }}</span>
                        @if($product->old_price && $product->old_price > $product->price)
                            <span class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->supplier_location)
                    <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                        <i class="fas fa-shipping-fast"></i> Ships from {{ $product->supplier_location }}
                    </p>
                    @endif
                    <button class="w-full py-2.5 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg font-medium hover:from-orange-600 hover:to-red-600 transition-all flex items-center justify-center gap-2">
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
