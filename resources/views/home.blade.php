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
                <a href="{{ route('shop') }}"
                    class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
                    Browse All Categories
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            @if($categories->count() > 0)
                <!-- Carousel Container -->
                <div class="relative">
                    <!-- Left Arrow -->
                    <button onclick="scrollCategoriesLeft()" id="catPrevBtn"
                        class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-600 hover:text-yellow-500 hover:bg-yellow-50 hover:shadow-xl transition-all border border-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <!-- Categories Grid - Scrollable with 3 cards per click -->
                    <div class="overflow-hidden mx-6">
                        <div id="categoriesSlider" class="flex gap-3 md:gap-4 transition-transform duration-500 ease-in-out">
                            @foreach($categories as $category)
                                <a href="{{ route('shop') }}?category={{ $category->slug }}" class="block flex-shrink-0"
                                    style="width: calc((100% - 5 * 1rem) / 6);">
                                    <div
                                        class="bg-white border border-gray-100 rounded-xl p-3 md:p-4 text-center cursor-pointer hover:border-yellow-400 hover:shadow-lg transition-all duration-300 group">
                                        <div
                                            class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-2 rounded-lg overflow-hidden border-2 border-gray-100 group-hover:border-yellow-400 transition-colors">
                                            @php
                                                $catImage = $category->image
                                                    ? (str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image))
                                                    : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200&h=200&fit=crop';
                                            @endphp
                                            <img src="{{ $catImage }}" alt="{{ $category->name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <h3
                                            class="text-xs md:text-sm font-semibold text-gray-700 group-hover:text-yellow-600 transition-colors truncate">
                                            {{ $category->name }}
                                        </h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Arrow -->
                    <button onclick="scrollCategoriesRight()" id="catNextBtn"
                        class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-600 hover:text-yellow-500 hover:bg-yellow-50 hover:shadow-xl transition-all border border-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
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
                let currentCategoryP    osit    ion = 0;
                const totalCategories =     {{ $categories->count() }};
                    cons    t cardsPerClick = 3;    
                const visibleCards = 6;
                    const maxPosition =        Math.    max(0, totalCategories - v        isibleCards);

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
                document.addEventListener('DOMContentLoaded', function () {
                    updateCategorySlider();
                });
            </script>

            <!-- Best Products -->
            <section class="py-10 md:py-16 bg-white">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center mb-6 md:mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Best Products</h2>
                        <a href="{{ route('shop') }}"
                            class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
                            More Products
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($bestProducts->count() > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                            @foreach($bestProducts as $product)
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 block">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $imageUrl = $product->image
                                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-40 md:h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                                        @if($product->old_price && $product->old_price > $product->price)
                                            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                                -{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                            </span>
                                        @endif
                                        <div
                                            class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span
                                                class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                                                <i class="far fa-heart text-sm"></i>
                                            </span>
                                            <span
                                                class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                                                <i class="far fa-eye text-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-3 md:p-4">
                                        <h4 class="font-semibold text-gray-800 mb-1 md:mb-2 text-sm md:text-base line-clamp-2">
                                            {{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-1 md:mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span
                                                class="text-gray-500 text-xs ml-1">({{ number_format($product->rating ?? 4.5, 1) }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2 md:mb-3">
                                            <span
                                                class="text-lg md:text-xl font-bold text-yellow-600">{{ currency_symbol() }}{{ number_format($product->price, 2) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span
                                                    class="text-xs md:text-sm text-gray-400 line-through">{{ currency_symbol() }}{{ number_format($product->old_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <span
                                            class="w-full py-2 md:py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg font-medium hover:from-yellow-600 hover:to-amber-600 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- See More Button -->
                        <div class="text-center mt-8">
                            <a href="{{ route('shop') }}"
                                class="inline-flex items-center gap-2 px-8 py-3 bg-yellow-500 hover:bg-amber-600 text-white font-semibold rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
                                <span>See More Products</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No products available at the moment</p>
                            <a href="{{ route('shop') }}"
                                class="inline-block mt-4 px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
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
                            <a href="{{ route('shop') }}"
                                class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
                                View All Offers
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                        @if($banners->count() == 1)
                            {{-- Single Banner - Full Width --}}
                            <a href="{{ $banners->first()->link ?? '#' }}" class="block group">
                                <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                    <img src="{{ $banners->first()->image_url }}" alt="{{ $banners->first()->title ?? 'Banner' }}"
                                        class="w-full h-48 md:h-72 lg:h-96 object-cover group-hover:scale-105 transition-transform duration-500">
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
                                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}"
                                                class="w-full h-48 md:h-64 object-cover group-hover:scale-105 transition-transform duration-500">
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
                                        <img src="{{ $banners[0]->image_url }}" alt="{{ $banners[0]->title ?? 'Banner' }}"
                                            class="w-full h-48 md:h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
                                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}"
                                                class="w-full h-48 md:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
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
                                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Banner' }}"
                                                class="w-full h-48 md:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
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
                            <div class="w-full md:w-1/2 text-center md:text-left"
                                style="color: {{ $promoBanner->text_color ?? '#FFFFFF' }};">
                                @if($promoBanner->subtitle)
                                    <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold mb-4"
                                        style="background-color: rgba(255,255,255,0.2);">
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
                                    <a href="{{ $promoBanner->button_link }}"
                                        class="inline-flex items-center gap-2 px-8 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                        {{ $promoBanner->button_text }}
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                            <!-- Image (Right) -->
                            <div class="w-full md:w-1/2">
                                <div class="relative">
                                    <img src="{{ $promoBanner->image_url }}" alt="{{ $promoBanner->title }}"
                                        class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-2xl">
                                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white rounded-xl shadow-lg flex items-center justify-center"
                                        style="color: {{ $promoBanner->background_color ?? '#FFA500' }};">
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
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-store text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Retail Products</h2>
                                <p class="text-gray-500 text-sm">Top picks from our retailers</p>
                            </div>
                        </div>
                        <a href="{{ route('retail') }}"
                            class="text-yellow-500 hover:text-amber-600 flex items-center gap-2 font-medium transition-colors">
                            View All
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($retailerProducts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($retailerProducts as $product)
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 block">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $imageUrl = $product->image
                                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                                        @if($product->old_price && $product->old_price > $product->price)
                                            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                                -{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                            </span>
                                        @endif
                                        <div
                                            class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                                                <i class="far fa-heart text-sm"></i>
                                            </span>
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-colors">
                                                <i class="far fa-eye text-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-xl font-bold text-yellow-600">${{ number_format($product->price, 2) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span
                                                    class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <span
                                            class="w-full py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-white rounded-lg font-medium hover:from-yellow-600 hover:to-amber-600 transition-all flex items-center justify-center gap-2">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </span>
                                    </div>
                                </a>
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
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-warehouse text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Wholesale Products</h2>
                                <p class="text-gray-500 text-sm">Bulk deals from wholesalers</p>
                            </div>
                        </div>
                        <a href="{{ route('wholesale') }}"
                            class="text-[#FFA500] hover:text-[#FFB833] flex items-center gap-2 font-medium transition-colors">
                            View All
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($wholesalerProducts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($wholesalerProducts as $product)
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 block">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $imageUrl = $product->image
                                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                                        @if($product->minimum_order)
                                            <span class="absolute top-3 left-3 bg-[#FFA500] text-white text-xs font-bold px-2 py-1 rounded-lg">
                                                Min: {{ $product->minimum_order }} pcs
                                            </span>
                                        @endif
                                        <div
                                            class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-[#FFA500] hover:text-white transition-colors">
                                                <i class="far fa-heart text-sm"></i>
                                            </span>
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-[#FFA500] hover:text-white transition-colors">
                                                <i class="far fa-eye text-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xl font-bold text-[#FFA500]">${{ number_format($product->price, 2) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span
                                                    class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                                            @endif
                                        </div>
                                        @if($product->supplier_location)
                                            <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                                <i class="fas fa-map-marker-alt"></i> {{ $product->supplier_location }}
                                            </p>
                                        @endif
                                        <span
                                            class="w-full py-2.5 bg-gradient-to-r from-[#FFA500] to-[#FFB833] text-white rounded-lg font-medium hover:from-[#FFB833] hover:to-[#FFA500] transition-all flex items-center justify-center gap-2">
                                            <i class="fas fa-phone"></i> Contact Supplier
                                        </span>
                                    </div>
                                </a>
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
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-globe text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Export Products</h2>
                                <p class="text-gray-500 text-sm">Global trade opportunities</p>
                            </div>
                        </div>
                        <a href="{{ route('export') }}"
                            class="text-[#FFA500] hover:text-[#FFB833] flex items-center gap-2 font-medium transition-colors">
                            View All
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($exporterProducts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($exporterProducts as $product)
                                <a href="{{ route('product.show', $product->id) }}"
                                    class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 block">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $imageUrl = $product->image
                                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                                        <span
                                            class="absolute top-3 left-3 bg-[#FFA500] text-white text-xs font-bold px-2 py-1 rounded-lg flex items-center gap-1">
                                            <i class="fas fa-globe-americas"></i> Export Ready
                                        </span>
                                        <div
                                            class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-[#FFA500] hover:text-white transition-colors">
                                                <i class="far fa-heart text-sm"></i>
                                            </span>
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-[#FFA500] hover:text-white transition-colors">
                                                <i class="far fa-eye text-sm"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xl font-bold text-[#FFA500]">${{ number_format($product->price, 2) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span
                                                    class="text-sm text-gray-400 line-through">${{ number_format($product->old_price, 2) }}</span>
                                            @endif
                                        </div>
                                        @if($product->supplier_location)
                                            <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                                <i class="fas fa-shipping-fast"></i> Ships from {{ $product->supplier_location }}
                                            </p>
                                        @endif
                                        <span
                                            class="w-full py-2.5 bg-gradient-to-r from-[#FFA500] to-[#FFB833] text-white rounded-lg font-medium hover:from-[#FFB833] hover:to-[#FFA500] transition-all flex items-center justify-center gap-2">
                                            <i class="fas fa-envelope"></i> Request Quote
                                        </span>
                                    </div>
                                </a>
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
            <section class="vendors-section" style="background: #f8f9fa; padding: 60px 0;">
                <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
                    <div class="section-header"
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
                        <h2 style="font-size: 32px; font-weight: 700; color: #333; margin: 0;">Featured Sellers</h2>
                        <a href="{{ route('sellers.index') }}" class="view-all"
                            style="color: #FFA500; font-weight: 600; text-decoration: none; font-size: 16px; transition: color 0.3s;"
                            onmouseover="this.style.color='#e69500'" onmouseout="this.style.color='#FFA500'">
                            View All Sellers
                            <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                        </a>
                    </div>

                    @if($featuredVendors->count() > 0)
                        <!-- Slider Container -->
                        <div style="position: relative;">
                            <!-- Navigation Buttons -->
                            <button class="vendor-slider-prev"
                                style="position: absolute; left: -20px; top: 50%; transform: translateY(-50%); z-index: 10; width: 50px; height: 50px; border-radius: 50%; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
                                onmouseover="this.style.background='#FFA500'; this.style.color='white'"
                                onmouseout="this.style.background='white'; this.style.color='#333'">
                                <i class="fas fa-chevron-left" style="font-size: 20px;"></i>
                            </button>
                            <button class="vendor-slider-next"
                                style="position: absolute; right: -20px; top: 50%; transform: translateY(-50%); z-index: 10; width: 50px; height: 50px; border-radius: 50%; background: white; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;"
                                onmouseover="this.style.background='#FFA500'; this.style.color='white'"
                                onmouseout="this.style.background='white'; this.style.color='#333'">
                                <i class="fas fa-chevron-right" style="font-size: 20px;"></i>
                            </button>

                            <!-- Slider Wrapper -->
                            <div class="vendor-slider-wrapper" style="overflow: hidden; position: relative;">
                                <div class="vendor-slider-track" style="display: flex; transition: transform 0.5s ease-in-out;">
                                    @foreach($featuredVendors as $vendor)
                                        @php
                                            $businessInfo = $vendor->roleApplications->first() ?? null;
                                            $roleIcon = $vendor->role === 'retailer' ? 'fa-store' :
                                                ($vendor->role === 'wholesaler' ? 'fa-warehouse' : 'fa-globe');
                                            $roleColor = $vendor->role === 'retailer' ? '#4CAF50' :
                                                ($vendor->role === 'wholesaler' ? '#2196F3' : '#9C27B0');
                                            $vendorName = $businessInfo->business_name ?? $vendor->name;
                                            $firstLetter = strtoupper(substr($vendorName, 0, 1));
                                        @endphp

                                        <div class="featured-vendor-card"
                                            style="min-width: calc(25% - 19px); margin-right: 25px; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; flex-shrink: 0;">
                                            <!-- Seller Photo/Avatar -->
                                            <div
                                                style="position: relative; background: linear-gradient(135deg, {{ $roleColor }}15 0%, {{ $roleColor }}05 100%); padding: 30px 20px; text-align: center;">
                                                <div
                                                    style="width: 100px; height: 100px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); position: relative; background: {{ $roleColor }};">
                                                    @if($vendor->profile_image)
                                                        <img src="{{ asset('storage/' . $vendor->profile_image) }}" alt="{{ $vendorName }}"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <div
                                                            style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, {{ $roleColor }} 0%, {{ $roleColor }}dd 100%); color: white; font-size: 40px; font-weight: 700;">
                                                            {{ $firstLetter }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Seller Type Badge -->
                                                <span
                                                    style="background: {{ $roleColor }}; color: white; padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; display: inline-block;">
                                                    <i class="fas {{ $roleIcon }}" style="margin-right: 4px;"></i>
                                                    {{ ucfirst($vendor->role) }}
                                                </span>

                                                @if($vendor->role === 'exporter' && $vendor->exporter_rating)
                                                    <div style="margin-top: 8px;">
                                                        <span
                                                            style="background: #FFD700; color: #333; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                                            <i class="fas fa-star"></i>
                                                            {{ number_format($vendor->exporter_rating, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Seller Info -->
                                            <div style="padding: 20px;">
                                                <h3
                                                    style="font-size: 16px; font-weight: 700; color: #333; margin-bottom: 4px; text-align: center;">
                                                    {{ $vendorName }}
                                                </h3>
                                                <p style="text-align: center; color: #888; font-size: 12px; margin-bottom: 15px;">
                                                    Trusted seller with wide range of quality products
                                                </p>

                                                <!-- Stats Row -->
                                                <div
                                                    style="display: flex; justify-content: space-around; padding: 12px 0; margin-bottom: 15px; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0;">
                                                    <div style="text-align: center;">
                                                        <div style="font-size: 18px; font-weight: 700; color: {{ $roleColor }};">
                                                            {{ $vendor->products_count ?? 0 }}</div>
                                                        <div
                                                            style="font-size: 10px; color: #999; text-transform: uppercase; margin-top: 2px;">
                                                            Products</div>
                                                    </div>
                                                    <div style="width: 1px; background: #e0e0e0;"></div>
                                                    <div style="text-align: center;">
                                                        <div style="font-size: 18px; font-weight: 700; color: {{ $roleColor }};">
                                                            {{ $vendor->total_sales ?? 0 }}</div>
                                                        <div
                                                            style="font-size: 10px; color: #999; text-transform: uppercase; margin-top: 2px;">
                                                            Sales</div>
                                                    </div>
                                                    <div style="width: 1px; background: #e0e0e0;"></div>
                                                    <div style="text-align: center;">
                                                        <div style="font-size: 18px; font-weight: 700; color: #FFB800;">
                                                            @if($vendor->role === 'exporter' && $vendor->exporter_rating)
                                                                {{ number_format($vendor->exporter_rating, 1) }}
                                                            @else
                                                                5.0
                                                            @endif
                                                        </div>
                                                        <div
                                                            style="font-size: 10px; color: #999; text-transform: uppercase; margin-top: 2px;">
                                                            <i class="fas fa-star" style="color: #FFB800; font-size: 9px;"></i> Rating
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Visit Store Button -->
                                                <a href="{{ route('sellers.products', $vendor->id) }}"
                                                    style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(255,165,0,0.3);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(255,165,0,0.4)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(255,165,0,0.3)'">
                                                    <span>Visit Store</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pagination Dots -->
                            <div class="vendor-slider-pagination"
                                style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">
                                <!-- Dots will be generated by JavaScript -->
                            </div>
                        </div>
                    @else
                        <div
                            style="background: white; padding: 60px 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <i class="fas fa-store-slash" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                            <h3 style="font-size: 24px; color: #333; margin-bottom: 10px;">No Featured Sellers</h3>
                            <p style="color: #666; margin-bottom: 20px;">Check back soon for featured sellers</p>
                        </div>
                    @endif
                </div>
            </section>

            <style>
                /* Responsive adjustments */
                @media (max-width: 1200px) {
                    .featured-vendor-card {
                        min-width: calc(33.333% - 17px) !important;
                    }
                }

                @media (max-width: 768px) {
                    .featured-vendor-card {
                        min-width: calc(50% - 13px) !important;
                    }

                    .vendor-slider-prev,
                    .vendor-slider-next {
                        width: 40px !important;
                        height: 40px !important;
                    }
                }

                @media (max-width: 480px) {
                    .featured-vendor-card {
                        min-width: 100% !important;
                    }
                }

                .vendor-slider-dot {
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    background: #ddd;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s;
                    padding: 0;
                }

                .vendor-slider-dot.active {
                    background: #FFA500;
                    width: 30px;
                    border-radius: 6px;
                }

                .vendor-slider-dot:hover {
                    background: #FFB800;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const track = document.querySelector('.vendor-slider-track');
                    const prevBtn = document.querySelector('.vendor-slider-prev');
                    const nextBtn = document.querySelector('.vendor-slider-next');
                    const pagination = document.querySelector('.vendor-slider-pagination');
                    const cards = document.querySelectorAll('.featured-vendor-card');

                    if (!track || !prevBtn || !nextBtn || !pagination || cards.length === 0) {
                        console.log('Slider elements not found');
                        return;
                    }

                    let currentIndex = 0;
                    let cardsPerView = 4;
                    let autoplayInterval;

                    // Calculate cards per view based on screen size
                    function updateCardsPerView() {
                        const width = window.innerWidth;
                        if (width <= 480) cardsPerView = 1;
                        else if (width <= 768) cardsPerView = 2;
                        else if (width <= 1200) cardsPerView = 3;
                        else cardsPerView = 4;
                    }

                    // Get maximum scroll index
                    function getMaxIndex() {
                        // Allow scrolling up to the last item individually, so buttons always work
                        return Math.max(0, cards.length - 1);
                    }

                    // Create pagination dots
                    function createPagination() {
                        if (!pagination) return;
                        pagination.innerHTML = '';
                        const maxIndex = getMaxIndex();
                        const totalDots = Math.min(maxIndex + 1, 10);

                        for (let i = 0; i < totalDots; i++) {
                            const dot = document.createElement('button');
                            dot.className = 'vendor-slider-dot' + (i === 0 ? ' active' : '');
                            dot.style.cssText = 'width: 12px; height: 12px; border-radius: 50%; border: none; background: #ddd; cursor: pointer; transition: all 0.3s;';
                            dot.addEventListener('click', function () {
                                goToSlide(i);
                            });
                            pagination.appendChild(dot);
                        }
                    }

                    // Update slider position
                    function updateSlider() {
                        if (!track || cards.length === 0) return;

                        const cardWidth = cards[0].offsetWidth;
                        const gap = 25;
                        const offset = -(currentIndex * (cardWidth + gap));
                        track.style.transform = `translateX(${offset}px)`;

                        // Update pagination
                        const dots = document.querySelectorAll('.vendor-slider-dot');
                        dots.forEach((dot, index) => {
                            if (index === currentIndex) {
                                dot.classList.add('active');
                                dot.style.background = '#FFA500';
                                dot.style.transform = 'scale(1.2)';
                            } else {
                                dot.classList.remove('active');
                                dot.style.background = '#ddd';
                                dot.style.transform = 'scale(1)';
                            }
                        });

                        // Update button visibility - Always show buttons as per user request
                        if (prevBtn) {
                            prevBtn.style.display = 'flex';
                            prevBtn.style.opacity = '1';
                            prevBtn.style.cursor = 'pointer';
                        }
                        if (nextBtn) {
                            nextBtn.style.display = 'flex';
                            nextBtn.style.opacity = '1';
                            nextBtn.style.cursor = 'pointer';
                        }
                    }

                    function goToSlide(index) {
                        const maxIndex = getMaxIndex();
                        currentIndex = Math.max(0, Math.min(index, maxIndex));
                        updateSlider();
                        resetAutoplay();
                    }

                    function nextSlide() {
                        const maxIndex = getMaxIndex();
                        if (currentIndex < maxIndex) {
                            currentIndex++;
                        } else {
                            currentIndex = 0;
                        }
                        updateSlider();
                    }

                    function prevSlide() {
                        const maxIndex = getMaxIndex();
                        if (currentIndex > 0) {
                            currentIndex--;
                        } else {
                            currentIndex = maxIndex;
                        }
                        updateSlider();
                    }

                    // Autoplay functionality
                    function startAutoplay() {
                        stopAutoplay();
                        autoplayInterval = setInterval(nextSlide, 4000);
                    }

                    function stopAutoplay() {
                        if (autoplayInterval) {
                            clearInterval(autoplayInterval);
                        }
                    }

                    function resetAutoplay() {
                        stopAutoplay();
                        startAutoplay();
                    }

                    // Event listeners
                    if (nextBtn) {
                        nextBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            console.log('Next clicked, current:', currentIndex);
                            nextSlide();
                            resetAutoplay();
                        });
                    }

                    if (prevBtn) {
                        prevBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            console.log('Prev clicked, current:', currentIndex);
                            prevSlide();
                            resetAutoplay();
                        });
                    }

                    // Keyboard navigation
                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'ArrowLeft') {
                            prevSlide();
                            resetAutoplay();
                        }
                        if (e.key === 'ArrowRight') {
                            nextSlide();
                            resetAutoplay();
                        }
                    });

                    // Touch/swipe support
                    let touchStartX = 0;
                    let touchEndX = 0;

                    track.addEventListener('touchstart', function (e) {
                        touchStartX = e.changedTouches[0].screenX;
                    });

                    track.addEventListener('touchend', function (e) {
                        touchEndX = e.changedTouches[0].screenX;
                        handleSwipe();
                    });

                    function handleSwipe() {
                        const diff = touchStartX - touchEndX;
                        if (Math.abs(diff) > 50) {
                            if (diff > 0) {
                                nextSlide();
                            } else {
                                prevSlide();
                            }
                            resetAutoplay();
                        }
                    }

                    // Pause autoplay on hover
                    track.addEventListener('mouseenter', stopAutoplay);
                    track.addEventListener('mouseleave', startAutoplay);

                    // Handle window resize
                    let resizeTimeout;
                    window.addEventListener('resize', function () {
                        clearTimeout(resizeTimeout);
                        resizeTimeout = setTimeout(function () {
                            updateCardsPerView();
                            currentIndex = Math.min(currentIndex, getMaxIndex());
                            createPagination();
                            updateSlider();
                        }, 250);
                    });

                    // Initialize
                    console.log('Initializing slider with', cards.length, 'cards');
                    updateCardsPerView();
                    createPagination();
                    updateSlider();
                    startAutoplay();
                });
            </script>

            <style>
                .featured-vendor-card {
                    transition: all 0.3s ease;
                }

                .featured-vendor-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
                }
            </style>

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