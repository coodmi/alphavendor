@extends('layouts.app')

@section('title', 'Home - AlphaVendor Multi Vendor Marketplace')

@section('content')
    <!-- Hero Slider -->
    <section class="hero-slider">
        <div class="slider-container">
            @forelse($heroSlides as $index => $slide)
            <div class="slide {{ $index === 0 ? 'active' : '' }}">
                @php
                    $slideImage = str_starts_with($slide->image, 'http') 
                        ? $slide->image 
                        : asset('storage/' . $slide->image);
                @endphp
                <img src="{{ $slideImage }}" alt="{{ $slide->title }}">
                <div class="slide-content">
                    <h2>{{ $slide->title }}</h2>
                    <p>{{ $slide->description }}</p>
                    @if($slide->cta_text && $slide->cta_link)
                    <a href="{{ $slide->cta_link }}" class="btn-primary">{{ $slide->cta_text }}</a>
                    @endif
                </div>
            </div>
            @empty
            <!-- Fallback slide if no slides exist -->
            <div class="slide active">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&h=600&fit=crop" alt="Welcome">
                <div class="slide-content">
                    <h2>Welcome to AlphaVendor</h2>
                    <p>Your trusted multi-vendor marketplace</p>
                    <a href="{{ route('shop') }}" class="btn-primary">Shop Now</a>
                </div>
            </div>
            @endforelse
        </div>
        @if($heroSlides->count() > 1)
        <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots">
            @foreach($heroSlides as $index => $slide)
            <span class="dot {{ $index === 0 ? 'active' : '' }}"></span>
            @endforeach
        </div>
        @endif
    </section>

    <!-- Popular Categories -->
    <section class="py-12 md:py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-10">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Popular Categories</h2>
                    <p class="text-gray-600 text-sm md:text-base">Explore our wide range of product categories</p>
                </div>
                <a href="{{ route('shop') }}"
                    class="mt-4 md:mt-0 inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Browse All Categories
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            @if($categories->count() > 0)
                <!-- Carousel Container -->
                <div class="relative group">
                    <!-- Left Arrow -->
                    <button onclick="scrollCategoriesLeft()" id="catPrevBtn"
                        class="absolute -left-3 md:-left-5 top-1/2 -translate-y-1/2 z-20 w-12 h-12 md:w-14 md:h-14 bg-white shadow-2xl rounded-full flex items-center justify-center text-gray-700 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-teal-600 transition-all duration-300 border-2 border-gray-100 hover:border-teal-500 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700 opacity-0 group-hover:opacity-100">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>

                    <!-- Categories Grid - Scrollable -->
                    <div class="overflow-hidden px-1">
                        <div id="categoriesSlider" class="flex gap-4 md:gap-5 transition-transform duration-500 ease-out">
                            @foreach($categories as $category)
                                <a href="{{ route('shop') }}?category={{ $category->slug }}" 
                                   class="block flex-shrink-0 category-card-mobile"
                                   style="width: calc((100% - 1rem) / 2);">
                                    <div class="bg-white rounded-2xl p-4 md:p-5 text-center cursor-pointer hover:shadow-2xl transition-all duration-300 group/card border-2 border-gray-100 hover:border-teal-500 transform hover:-translate-y-1">
                                        <div class="w-20 h-20 md:w-24 md:h-24 mx-auto mb-3 rounded-xl overflow-hidden border-3 border-gray-100 group-hover/card:border-teal-500 transition-all duration-300 shadow-md group-hover/card:shadow-xl">
                                            @php
                                                $catImage = $category->image
                                                    ? (str_starts_with($category->image, 'http') ? $category->image : asset('storage/' . $category->image))
                                                    : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200&h=200&fit=crop';
                                            @endphp
                                            <img src="{{ $catImage }}" alt="{{ $category->name }}"
                                                class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-500">
                                        </div>
                                        <h3 class="text-sm md:text-base font-bold text-gray-800 group-hover/card:text-teal-700 transition-colors line-clamp-2 min-h-[2.5rem]">
                                            {{ $category->name }}
                                        </h3>
                                        @if($category->products_count ?? 0)
                                            <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} items</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Arrow -->
                    <button onclick="scrollCategoriesRight()" id="catNextBtn"
                        class="absolute -right-3 md:-right-5 top-1/2 -translate-y-1/2 z-20 w-12 h-12 md:w-14 md:h-14 bg-white shadow-2xl rounded-full flex items-center justify-center text-gray-700 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-teal-600 transition-all duration-300 border-2 border-gray-100 hover:border-teal-500 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700 opacity-0 group-hover:opacity-100">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>

                    <!-- Dots Indicator -->
                    @if($categories->count() > 6)
                        <div class="flex justify-center gap-2 mt-6" id="categoryDots">
                            <!-- Dots will be generated by JavaScript -->
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-dashed border-gray-300">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-medium">No categories available at the moment</p>
                    <p class="text-gray-500 text-sm mt-2">Check back soon for new categories</p>
                </div>
            @endif
        </div>
    </section>

    <style>
        @media (min-width: 768px) {
            .category-card-mobile {
                width: calc((100% - 5 * 1.25rem) / 6) !important;
            }
        }
        
        @media (max-width: 767px) {
            .category-card-mobile {
                width: calc((100% - 1rem) / 2) !important;
            }
        }
    </style>

    <script>
        let currentCategoryPosition = 0;
        const totalCategories = {{ $categories->count() }};
        let cardsPerClick = 3;
        let visibleCards = 6;
        
        function updateResponsiveSettings() {
            if (window.innerWidth < 768) {
                cardsPerClick = 2;
                visibleCards = 2;
            } else {
                cardsPerClick = 3;
                visibleCards = 6;
            }
        }
        
        function getMaxPosition() {
            return Math.max(0, totalCategories - visibleCards);
        }

        function getCardWidth() {
            const slider = document.getElementById('categoriesSlider');
            if (slider && slider.children.length > 0) {
                const card = slider.children[0];
                const style = window.getComputedStyle(slider);
                const gap = parseFloat(style.gap) || 16;
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
            const maxPosition = getMaxPosition();
            
            if (prevBtn) {
                prevBtn.disabled = currentCategoryPosition === 0;
                prevBtn.style.opacity = currentCategoryPosition === 0 ? '0.3' : '';
            }
            if (nextBtn) {
                nextBtn.disabled = currentCategoryPosition >= maxPosition;
                nextBtn.style.opacity = currentCategoryPosition >= maxPosition ? '0.3' : '';
            }

            // Update dots
            updateCategoryDots();
        }

        function updateCategoryDots() {
            const dotsContainer = document.getElementById('categoryDots');
            if (!dotsContainer) return;

            const maxPosition = getMaxPosition();
            const totalDots = Math.ceil((totalCategories - visibleCards + cardsPerClick) / cardsPerClick);
            const currentDot = Math.floor(currentCategoryPosition / cardsPerClick);

            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalDots; i++) {
                const dot = document.createElement('button');
                dot.className = `cat-dot h-2 rounded-full transition-all duration-300 ${
                    i === currentDot 
                        ? 'bg-gradient-to-r from-teal-500 to-teal-600 w-8' 
                        : 'bg-gray-300 hover:bg-gray-400 w-2'
                }`;
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
            const maxPosition = getMaxPosition();
            if (currentCategoryPosition < maxPosition) {
                currentCategoryPosition = Math.min(maxPosition, currentCategoryPosition + cardsPerClick);
                updateCategorySlider();
            }
        }

        function goToCategorySlide(index) {
            const maxPosition = getMaxPosition();
            currentCategoryPosition = Math.min(maxPosition, index * cardsPerClick);
            updateCategorySlider();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            updateResponsiveSettings();
            updateCategorySlider();
        });
        
        // Update on window resize
        window.addEventListener('resize', function() {
            updateResponsiveSettings();
            currentCategoryPosition = Math.min(currentCategoryPosition, getMaxPosition());
            updateCategorySlider();
        });
    </script>

            <!-- Best Products -->
            <section class="py-10 md:py-16 bg-white">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center mb-6 md:mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Best Products</h2>
                        <a href="{{ route('shop') }}"
                            class="text-teal-600 hover:text-teal-600 flex items-center gap-2 font-medium transition-colors">
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
                                                class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-teal-600 hover:text-white transition-colors">
                                                <i class="far fa-heart text-sm"></i>
                                            </span>
                                            <span
                                                class="w-8 h-8 md:w-9 md:h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-teal-600 hover:text-white transition-colors">
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
                                                    class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-teal-500' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span
                                                class="text-gray-500 text-xs ml-1">({{ number_format($product->rating ?? 4.5, 1) }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2 md:mb-3">
                                            <span
                                                class="text-lg md:text-xl font-bold text-teal-700">{{ currency_symbol() }}{{ number_format($product->price, 2) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span
                                                    class="text-xs md:text-sm text-gray-400 line-through">{{ currency_symbol() }}{{ number_format($product->old_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <button
                                                onclick="event.preventDefault(); event.stopPropagation(); quickAddToCart({{ $product->id }}, this);"
                                                class="flex-1 py-2 md:py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 text-white rounded-lg font-medium hover:from-teal-700 hover:to-teal-600 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
                                            </button>
                                            <button
                                                onclick="event.preventDefault(); event.stopPropagation(); buyNow({{ $product->id }}, this);"
                                                class="flex-1 py-2 md:py-2.5 bg-gradient-to-r from-teal-700 to-teal-800 text-white rounded-lg font-medium hover:from-teal-800 hover:to-teal-900 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                                                <i class="fas fa-bolt"></i> Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- See More Button -->
                        <div class="text-center mt-8">
                            <a href="{{ route('shop') }}"
                                class="inline-flex items-center gap-2 px-8 py-3 bg-teal-600 hover:bg-teal-600 text-white font-semibold rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
                                <span>See More Products</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No products available at the moment</p>
                            <a href="{{ route('shop') }}"
                                class="inline-block mt-4 px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                                Browse All Products
                            </a>
                        </div>
                    @endif
                </div>
            </section>

    <!-- Popular Brands -->
    <section class="py-12 md:py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 md:mb-10">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Popular Brands</h2>
                    <p class="text-gray-600 text-sm md:text-base">Shop from your favorite brands</p>
                </div>
                <a href="{{ route('shop') }}"
                    class="mt-4 md:mt-0 inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Browse All Brands
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            @if($brands->count() > 0)
                <!-- Carousel Container -->
                <div class="relative group">
                    <!-- Left Arrow -->
                    <button onclick="scrollBrandsLeft()" id="brandPrevBtn"
                        class="absolute -left-3 md:-left-5 top-1/2 -translate-y-1/2 z-20 w-12 h-12 md:w-14 md:h-14 bg-white shadow-2xl rounded-full flex items-center justify-center text-gray-700 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-teal-600 transition-all duration-300 border-2 border-gray-100 hover:border-teal-500 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700 opacity-0 group-hover:opacity-100">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>

                    <!-- Brands Grid - Scrollable -->
                    <div class="overflow-hidden px-1">
                        <div id="brandsSlider" class="flex gap-4 md:gap-5 transition-transform duration-500 ease-out">
                            @foreach($brands as $brand)
                                <a href="{{ route('shop') }}?brand={{ $brand->slug }}" 
                                   class="block flex-shrink-0 brand-card-mobile"
                                   style="width: calc((100% - 1rem) / 2);">
                                    <div class="bg-white rounded-2xl p-4 md:p-5 text-center cursor-pointer hover:shadow-2xl transition-all duration-300 group/card border-2 border-gray-100 hover:border-teal-500 transform hover:-translate-y-1">
                                        <div class="w-20 h-20 md:w-24 md:h-24 mx-auto mb-3 rounded-xl overflow-hidden border-3 border-gray-100 group-hover/card:border-teal-500 transition-all duration-300 shadow-md group-hover/card:shadow-xl bg-gray-50 flex items-center justify-center p-2">
                                            @php
                                                $brandImage = $brand->logo
                                                    ? (str_starts_with($brand->logo, 'http') ? $brand->logo : asset('storage/' . $brand->logo))
                                                    : null;
                                            @endphp
                                            @if($brandImage)
                                                <img src="{{ $brandImage }}" alt="{{ $brand->name }}"
                                                    class="w-full h-full object-contain group-hover/card:scale-110 transition-transform duration-500">
                                            @else
                                                <span class="text-2xl md:text-3xl font-bold text-gray-400 group-hover/card:text-teal-600 transition-colors">
                                                    {{ strtoupper(substr($brand->name, 0, 2)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-sm md:text-base font-bold text-gray-800 group-hover/card:text-teal-700 transition-colors line-clamp-2 min-h-[2.5rem]">
                                            {{ $brand->name }}
                                        </h3>
                                        @if($brand->products_count ?? 0)
                                            <p class="text-xs text-gray-500 mt-1">{{ $brand->products_count }} items</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Arrow -->
                    <button onclick="scrollBrandsRight()" id="brandNextBtn"
                        class="absolute -right-3 md:-right-5 top-1/2 -translate-y-1/2 z-20 w-12 h-12 md:w-14 md:h-14 bg-white shadow-2xl rounded-full flex items-center justify-center text-gray-700 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-teal-600 transition-all duration-300 border-2 border-gray-100 hover:border-teal-500 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-700 opacity-0 group-hover:opacity-100">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>

                    <!-- Dots Indicator -->
                    @if($brands->count() > 6)
                        <div class="flex justify-center gap-2 mt-6" id="brandDots">
                            <!-- Dots will be generated by JavaScript -->
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-dashed border-gray-300">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-tag text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-medium">No brands available at the moment</p>
                    <p class="text-gray-500 text-sm mt-2">Check back soon for new brands</p>
                </div>
            @endif
        </div>
    </section>

    <style>
        @media (min-width: 768px) {
            .brand-card-mobile {
                width: calc((100% - 5 * 1.25rem) / 6) !important;
            }
        }
        
        @media (max-width: 767px) {
            .brand-card-mobile {
                width: calc((100% - 1rem) / 2) !important;
            }
        }
    </style>

    <script>
        let currentBrandPosition = 0;
        const totalBrands = {{ $brands->count() }};
        let brandCardsPerClick = 3;
        let brandVisibleCards = 6;
        
        function updateBrandResponsiveSettings() {
            if (window.innerWidth < 768) {
                brandCardsPerClick = 2;
                brandVisibleCards = 2;
            } else {
                brandCardsPerClick = 3;
                brandVisibleCards = 6;
            }
        }
        
        function getBrandMaxPosition() {
            return Math.max(0, totalBrands - brandVisibleCards);
        }

        function getBrandCardWidth() {
            const slider = document.getElementById('brandsSlider');
            if (slider && slider.children.length > 0) {
                const card = slider.children[0];
                const style = window.getComputedStyle(slider);
                const gap = parseFloat(style.gap) || 16;
                return card.offsetWidth + gap;
            }
            return 0;
        }

        function updateBrandSlider() {
            const slider = document.getElementById('brandsSlider');
            if (slider) {
                const cardWidth = getBrandCardWidth();
                slider.style.transform = `translateX(-${currentBrandPosition * cardWidth}px)`;
            }

            // Update button states
            const prevBtn = document.getElementById('brandPrevBtn');
            const nextBtn = document.getElementById('brandNextBtn');
            const maxPosition = getBrandMaxPosition();
            
            if (prevBtn) {
                prevBtn.disabled = currentBrandPosition === 0;
                prevBtn.style.opacity = currentBrandPosition === 0 ? '0.3' : '';
            }
            if (nextBtn) {
                nextBtn.disabled = currentBrandPosition >= maxPosition;
                nextBtn.style.opacity = currentBrandPosition >= maxPosition ? '0.3' : '';
            }

            // Update dots
            updateBrandDots();
        }

        function updateBrandDots() {
            const dotsContainer = document.getElementById('brandDots');
            if (!dotsContainer) return;

            const maxPosition = getBrandMaxPosition();
            const totalDots = Math.ceil((totalBrands - brandVisibleCards + brandCardsPerClick) / brandCardsPerClick);
            const currentDot = Math.floor(currentBrandPosition / brandCardsPerClick);

            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalDots; i++) {
                const dot = document.createElement('button');
                dot.className = `brand-dot h-2 rounded-full transition-all duration-300 ${
                    i === currentDot 
                        ? 'bg-gradient-to-r from-teal-500 to-teal-600 w-8' 
                        : 'bg-gray-300 hover:bg-gray-400 w-2'
                }`;
                dot.onclick = () => goToBrandSlide(i);
                dotsContainer.appendChild(dot);
            }
        }

        function scrollBrandsLeft() {
            if (currentBrandPosition > 0) {
                currentBrandPosition = Math.max(0, currentBrandPosition - brandCardsPerClick);
                updateBrandSlider();
            }
        }

        function scrollBrandsRight() {
            const maxPosition = getBrandMaxPosition();
            if (currentBrandPosition < maxPosition) {
                currentBrandPosition = Math.min(maxPosition, currentBrandPosition + brandCardsPerClick);
                updateBrandSlider();
            }
        }

        function goToBrandSlide(index) {
            const maxPosition = getBrandMaxPosition();
            currentBrandPosition = Math.min(maxPosition, index * brandCardsPerClick);
            updateBrandSlider();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            updateBrandResponsiveSettings();
            updateBrandSlider();
        });
        
        // Update on window resize
        window.addEventListener('resize', function() {
            updateBrandResponsiveSettings();
            currentBrandPosition = Math.min(currentBrandPosition, getBrandMaxPosition());
            updateBrandSlider();
        });
    </script>

            <!-- Special Offers Section -->
            @if($specialOffers->count() > 0)
                <section class="py-10 md:py-16 bg-gray-50">
                    <div class="container mx-auto px-4">
                        <div class="flex justify-between items-center mb-6 md:mb-8">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Special Offers</h2>
                            <a href="{{ route('special-offers.index') }}"
                                class="text-teal-600 hover:text-teal-600 flex items-center gap-2 font-medium transition-colors">
                                View All Offers
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                            @foreach($specialOffers as $offer)
                                <a href="{{ route('special-offers.show', $offer->slug) }}" class="block group">
                                    <div class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                        <img src="{{ $offer->image_url }}" alt="{{ $offer->name }}"
                                            class="w-full h-48 md:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                                            <div class="p-4 w-full">
                                                <h3 class="text-white text-lg font-bold">{{ $offer->name }}</h3>
                                                @if($offer->badge_text)
                                                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold text-white" 
                                                        style="background-color: {{ $offer->badge_color ?? '#0d5c63' }}">
                                                        {{ $offer->badge_text }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <!-- Promo Banner Slider -->
            @if(isset($promoBanners) && $promoBanners->count() > 0)
                <section class="relative overflow-hidden" id="promoBannerSection">
                    <!-- Slides -->
                    <div id="promoSlider" class="relative">
                        @foreach($promoBanners as $i => $promoBanner)
                        <div class="promo-slide absolute inset-0 transition-opacity duration-700 ease-in-out {{ $i === 0 ? 'opacity-100 relative' : 'opacity-0 pointer-events-none' }}"
                            data-index="{{ $i }}"
                            style="background-color: {{ $promoBanner->background_color ?? '#0d5c63' }}; padding: 50px 0;">
                            <div class="container mx-auto px-4">
                                <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                                    <div class="w-full md:w-1/2 text-center md:text-left">
                                        @if($promoBanner->subtitle)
                                            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold mb-4"
                                                style="background-color: rgba(255,255,255,0.25); color: {{ $promoBanner->text_color ?? '#fff' }};">
                                                {{ $promoBanner->subtitle }}
                                            </span>
                                        @endif
                                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 leading-tight"
                                            style="color: {{ $promoBanner->text_color ?? '#fff' }};">
                                            {{ $promoBanner->title }}
                                        </h2>
                                        @if($promoBanner->description)
                                            <p class="text-lg mb-6 leading-relaxed"
                                                style="color: {{ $promoBanner->text_color ?? '#fff' }}; opacity: 0.9;">
                                                {{ $promoBanner->description }}
                                            </p>
                                        @endif
                                        @if($promoBanner->button_text && $promoBanner->button_link)
                                            <a href="{{ $promoBanner->button_link }}"
                                                class="inline-flex items-center gap-2 px-8 py-3 font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300"
                                                style="background-color: #fff; color: {{ $promoBanner->background_color ?? '#0d5c63' }};">
                                                {{ $promoBanner->button_text }}
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <img src="{{ $promoBanner->image_url }}" alt="{{ $promoBanner->title }}"
                                            class="w-full h-56 md:h-72 object-cover rounded-2xl shadow-2xl">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($promoBanners->count() > 1)
                    <!-- Prev / Next Buttons -->
                    <button onclick="promoSlide(-1)"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white bg-opacity-30 hover:bg-opacity-60 rounded-full flex items-center justify-center text-white transition z-10">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="promoSlide(1)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white bg-opacity-30 hover:bg-opacity-60 rounded-full flex items-center justify-center text-white transition z-10">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Dots -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        @foreach($promoBanners as $i => $b)
                            <button onclick="promoGoTo({{ $i }})"
                                class="promo-dot w-3 h-3 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white scale-125' : 'bg-white opacity-40' }}">
                            </button>
                        @endforeach
                    </div>
                    @endif
                </section>

                <style>
                    #promoSlider { position: relative; }
                    .promo-slide { position: relative; }
                    .promo-slide.is-hidden {
                        position: absolute !important;
                        top: 0; left: 0; right: 0;
                        opacity: 0;
                        pointer-events: none;
                    }
                    .promo-slide.is-active {
                        position: relative !important;
                        opacity: 1;
                        pointer-events: auto;
                    }
                </style>

                <script>
                    let promoIndex = 0;
                    const promoSlides = document.querySelectorAll('.promo-slide');
                    const promoDots  = document.querySelectorAll('.promo-dot');

                    // Init
                    promoSlides.forEach((s, i) => {
                        s.classList.add(i === 0 ? 'is-active' : 'is-hidden');
                    });

                    function promoGoTo(n) {
                        promoSlides[promoIndex].classList.remove('is-active');
                        promoSlides[promoIndex].classList.add('is-hidden');
                        promoDots[promoIndex]?.classList.remove('scale-125');
                        promoDots[promoIndex]?.classList.add('opacity-40');

                        promoIndex = (n + promoSlides.length) % promoSlides.length;

                        promoSlides[promoIndex].classList.remove('is-hidden');
                        promoSlides[promoIndex].classList.add('is-active');
                        promoDots[promoIndex]?.classList.add('scale-125');
                        promoDots[promoIndex]?.classList.remove('opacity-40');
                    }

                    function promoSlide(dir) { promoGoTo(promoIndex + dir); }

                    @if($promoBanners->count() > 1)
                        setInterval(() => promoSlide(1), 7000);
                    @endif
                </script>
            @endif

            <!-- Retailer Products Section -->
            <section class="py-10 md:py-16 bg-white">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center mb-6 md:mb-8">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-teal-600 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-store text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Retail Products</h2>
                                <p class="text-gray-500 text-sm">Top picks from our retailers</p>
                            </div>
                        </div>
                        <a href="{{ route('retail') }}"
                            class="text-teal-600 hover:text-teal-700 flex items-center gap-2 font-medium transition-colors">
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
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-teal-600 hover:text-white transition-colors">
                                                <i class="far fa-heart text-sm"></i>
                                            </span>
                                            <span
                                                class="w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-teal-600 hover:text-white transition-colors">
                                                <i class="far fa-eye text-sm"></i>
                                            </span>
                                        </div>
                                        <div class="absolute bottom-0 left-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                            <button
                                                onclick="event.preventDefault(); event.stopPropagation(); quickAddToCart({{ $product->id }}, this);"
                                                class="flex-1 py-3 bg-gradient-to-r from-teal-600 to-teal-500 text-white font-semibold hover:from-teal-700 hover:to-teal-600 transition-all flex items-center justify-center gap-2">
                                                <i class="fas fa-shopping-cart"></i> Add to Cart
                                            </button>
                                            <button
                                                onclick="event.preventDefault(); event.stopPropagation(); buyNow({{ $product->id }}, this);"
                                                class="flex-1 py-3 bg-gradient-to-r from-teal-700 to-teal-800 text-white font-semibold hover:from-teal-800 hover:to-teal-900 transition-all flex items-center justify-center gap-2">
                                                <i class="fas fa-bolt"></i> Buy Now
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-teal-500' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-xl font-bold text-teal-700"> {{ currency($product->price) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span
                                                    class="text-sm text-gray-400 line-through"> {{ currency($product->old_price) }}</span>
                                            @endif
                                        </div>
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
                                class="w-12 h-12 bg-gradient-to-br from-teal-600 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-warehouse text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Wholesale Products</h2>
                                <p class="text-gray-500 text-sm">Bulk deals from wholesalers</p>
                            </div>
                        </div>
                        <a href="{{ route('wholesale') }}"
                            class="text-teal-600 hover:text-teal-700 flex items-center gap-2 font-medium transition-colors">
                            View All
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($wholesalerProducts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($wholesalerProducts as $product)
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 flex flex-col">
                                    <a href="{{ route('product.show', $product->id) }}" class="block">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $imageUrl = $product->image
                                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                                        @if($product->minimum_order)
                                            <span class="absolute top-3 left-3 bg-teal-600 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                                Min: {{ $product->minimum_order }} pcs
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-4 flex-1">
                                        <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-teal-500' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xl font-bold text-teal-700">{{ currency($product->price) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span class="text-sm text-gray-400 line-through">{{ currency($product->old_price) }}</span>
                                            @endif
                                        </div>
                                        @if($product->supplier_location)
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-map-marker-alt"></i> {{ $product->supplier_location }}
                                            </p>
                                        @endif
                                    </div>
                                    </a>
                                    <div class="flex gap-1 mt-auto">
                                        <button
                                            onclick="quickAddToCart({{ $product->id }}, this);"
                                            class="flex-1 py-3 bg-gradient-to-r from-teal-600 to-teal-500 text-white font-semibold hover:from-teal-700 hover:to-teal-600 transition-all flex items-center justify-center gap-2 text-sm">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                        <a href="{{ route('product.show', $product->id) }}#advance"
                                            class="flex-1 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all flex items-center justify-center gap-2 text-sm text-center">
                                            <i class="fas fa-money-check-alt"></i> Pay Advance
                                        </a>
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

            <!-- Importer Products Section -->
            <section class="py-10 md:py-16 bg-white">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center mb-6 md:mb-8">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-teal-600 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-globe text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Import Products</h2>
                                <p class="text-gray-500 text-sm">Global trade opportunities</p>
                            </div>
                        </div>
                        <a href="{{ route('import') }}"
                            class="text-teal-600 hover:text-teal-700 flex items-center gap-2 font-medium transition-colors">
                            View All
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if($exporterProducts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($exporterProducts as $product)
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100 flex flex-col">
                                    <a href="{{ route('product.show', $product->id) }}" class="block">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $imageUrl = $product->image
                                                ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
                                                : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop';
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-52 object-cover group-hover:scale-110 transition-transform duration-500">
                                        <span class="absolute top-3 left-3 bg-teal-600 text-white text-xs font-bold px-2 py-1 rounded-lg flex items-center gap-1">
                                            <i class="fas fa-globe-americas"></i> Import Ready
                                        </span>
                                    </div>
                                    <div class="p-4 flex-1">
                                        <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2 h-12">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= ($product->rating ?? 4) ? 'text-teal-500' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="text-gray-500 text-xs ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xl font-bold text-teal-700">{{ currency($product->price) }}</span>
                                            @if($product->old_price && $product->old_price > $product->price)
                                                <span class="text-sm text-gray-400 line-through">{{ currency($product->old_price) }}</span>
                                            @endif
                                        </div>
                                        @if($product->supplier_location)
                                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-shipping-fast"></i> Ships from {{ $product->supplier_location }}
                                            </p>
                                        @endif
                                    </div>
                                    </a>
                                    <div class="flex gap-1 mt-auto">
                                        <button
                                            onclick="quickAddToCart({{ $product->id }}, this);"
                                            class="flex-1 py-3 bg-gradient-to-r from-teal-600 to-teal-500 text-white font-semibold hover:from-teal-700 hover:to-teal-600 transition-all flex items-center justify-center gap-2 text-sm">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                        <a href="{{ route('product.show', $product->id) }}#advance"
                                            class="flex-1 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all flex items-center justify-center gap-2 text-sm text-center">
                                            <i class="fas fa-money-check-alt"></i> Pay Advance
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No import products available yet</p>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Featured Vendors -->
            <section class="vendors-section" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%); padding: 80px 0;">
                <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
                    <div class="section-header"
                        style="text-align: center; margin-bottom: 50px;">
                        <h2 style="font-size: 42px; font-weight: 800; color: #1a1a1a; margin: 0 0 15px 0; letter-spacing: -0.5px;">Featured Sellers</h2>
                        <p style="font-size: 18px; color: #666; margin-bottom: 25px;">Discover trusted sellers offering quality products</p>
                        <a href="{{ route('sellers.index') }}" class="view-all-btn"
                            style="display: inline-flex; align-items: center; gap: 8px; color: #0d5c63; font-weight: 600; text-decoration: none; font-size: 16px; padding: 12px 28px; border: 2px solid #0d5c63; border-radius: 30px; transition: all 0.3s;">
                            View All Sellers
                            <i class="fas fa-arrow-right" style="transition: transform 0.3s;"></i>
                        </a>
                    </div>

                    @if($featuredVendors->count() > 0)
                        <!-- Modern Grid Layout -->
                        <div class="vendors-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; margin-bottom: 40px;">
                            @foreach($featuredVendors as $vendor)
                                @php
                                    $businessInfo = $vendor->roleApplications->first() ?? null;
                                    $roleIcon = $vendor->role === 'retailer' ? 'fa-store' :
                                        ($vendor->role === 'wholesaler' ? 'fa-warehouse' : 'fa-globe');
                                    $roleColor = $vendor->role === 'retailer' ? '#4CAF50' :
                                        ($vendor->role === 'wholesaler' ? '#2196F3' : '#9C27B0');
                                    $vendorName = $businessInfo->business_name ?? $vendor->name;
                                    $firstLetter = strtoupper(substr($vendorName, 0, 1));
                                    $roleDisplay = $vendor->role === 'exporter' ? 'Importer' : ucfirst($vendor->role);
                                @endphp

                                <div class="vendor-card"
                                    style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; border: 1px solid #f0f0f0;">
                                    
                                    <!-- Vendor Header with Gradient -->
                                    <div style="position: relative; background: linear-gradient(135deg, {{ $roleColor }}20 0%, {{ $roleColor }}05 100%); padding: 35px 25px 25px; text-align: center;">
                                        <!-- Vendor Avatar with Badge -->
                                        <div style="position: relative; width: 110px; height: 110px; margin: 0 auto 20px;">
                                            <div style="width: 110px; height: 110px; border-radius: 50%; overflow: hidden; border: 5px solid white; box-shadow: 0 8px 24px rgba(0,0,0,0.12); background: {{ $roleColor }};">
                                                @php
                                                    // Generate a consistent random seed based on vendor ID for consistent images
                                                    $seed = $vendor->id;
                                                    $gender = $seed % 2 == 0 ? 'men' : 'women';
                                                    $photoNumber = ($seed % 99) + 1;
                                                    $placeholderImage = "https://randomuser.me/api/portraits/{$gender}/{$photoNumber}.jpg";
                                                @endphp
                                                @if($vendor->profile_image)
                                                    <img src="{{ str_starts_with($vendor->profile_image, 'http') ? $vendor->profile_image : asset('storage/' . $vendor->profile_image) }}" 
                                                        alt="{{ $vendorName }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <img src="{{ $placeholderImage }}" 
                                                        alt="{{ $vendorName }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @endif
                                            </div>
                                            
                                            @if($vendor->vendorBadge && $vendor->vendorBadge->is_active)
                                                <div style="position: absolute; top: -5px; right: -5px; width: 38px; height: 38px; background: {{ $vendor->vendorBadge->bg_color }}; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.25); z-index: 10;">
                                                    @if($vendor->vendorBadge->icon)
                                                        @if(str_starts_with($vendor->vendorBadge->icon, 'fa'))
                                                            <i class="{{ $vendor->vendorBadge->icon }}" style="color: {{ $vendor->vendorBadge->color }}; font-size: 16px;"></i>
                                                        @else
                                                            <span style="color: {{ $vendor->vendorBadge->color }}; font-size: 18px; line-height: 1;">{{ $vendor->vendorBadge->icon }}</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Vendor Name -->
                                        <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; line-height: 1.3;">
                                            {{ Str::limit($vendorName, 25) }}
                                        </h3>
                                        
                                        <!-- Role Badge - Under Name -->
                                        <div style="margin-bottom: 12px;">
                                            <span style="display: inline-block; background: {{ $roleColor }}; color: white; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px {{ $roleColor }}40;">
                                                <i class="fas {{ $roleIcon }}" style="margin-right: 4px;"></i>
                                                {{ $roleDisplay }}
                                            </span>
                                        </div>

                                        <!-- Rating Badge -->
                                        @if($vendor->role === 'exporter' && $vendor->exporter_rating)
                                            <div style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #FFD700 0%, #0d5c63 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; box-shadow: 0 4px 12px rgba(255,215,0,0.3);">
                                                <i class="fas fa-star"></i>
                                                <span>{{ number_format($vendor->exporter_rating, 1) }}</span>
                                            </div>
                                        @else
                                            <div style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #FFD700 0%, #0d5c63 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; box-shadow: 0 4px 12px rgba(255,215,0,0.3);">
                                                <i class="fas fa-star"></i>
                                                <span>5.0</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Vendor Stats -->
                                    <div style="padding: 25px;">
                                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
                                            <div style="text-align: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                                                <div style="font-size: 22px; font-weight: 800; color: {{ $roleColor }}; margin-bottom: 4px;">
                                                    {{ $vendor->products_count ?? 0 }}
                                                </div>
                                                <div style="font-size: 11px; color: #666; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                    Products
                                                </div>
                                            </div>
                                            <div style="text-align: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                                                <div style="font-size: 22px; font-weight: 800; color: {{ $roleColor }}; margin-bottom: 4px;">
                                                    {{ $vendor->total_sales ?? 0 }}
                                                </div>
                                                <div style="font-size: 11px; color: #666; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                    Sales
                                                </div>
                                            </div>
                                            <div style="text-align: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                                                <div style="font-size: 22px; font-weight: 800; color: #FFB800; margin-bottom: 4px;">
                                                    @if($vendor->role === 'exporter' && $vendor->exporter_rating)
                                                        {{ number_format($vendor->exporter_rating, 1) }}
                                                    @else
                                                        5.0
                                                    @endif
                                                </div>
                                                <div style="font-size: 11px; color: #666; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                    <i class="fas fa-star" style="color: #FFB800; font-size: 10px;"></i> Rating
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Visit Store Button -->
                                        <a href="{{ route('sellers.products', $vendor->id) }}"
                                            class="visit-store-btn"
                                            style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; background: linear-gradient(135deg, #0d5c63 0%, #0a4a50 100%); color: white; text-align: center; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(255,165,0,0.3); letter-spacing: 0.3px;">
                                            <i class="fas fa-store"></i>
                                            <span>Visit Store</span>
                                            <i class="fas fa-arrow-right" style="font-size: 13px;"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                                        @php
                                            $businessInfo = $vendor->roleApplications->first() ?? null;
                                            $roleIcon = $vendor->role === 'retailer' ? 'fa-store' :
                                                ($vendor->role === 'wholesaler' ? 'fa-warehouse' : 'fa-globe');
                                            $roleColor = $vendor->role === 'retailer' ? '#4CAF50' :
                                                ($vendor->role === 'wholesaler' ? '#2196F3' : '#9C27B0');
                                            $vendorName = $businessInfo->business_name ?? $vendor->name;
                                            $firstLetter = strtoupper(substr($vendorName, 0, 1));
                                            $roleDisplay = $vendor->role === 'exporter' ? 'Importer' : ucfirst($vendor->role);
                                        @endphp

                    @else
                        <div style="background: white; padding: 80px 20px; border-radius: 20px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                            <i class="fas fa-store-slash" style="font-size: 80px; color: #e0e0e0; margin-bottom: 25px;"></i>
                            <h3 style="font-size: 28px; color: #333; margin-bottom: 12px; font-weight: 700;">No Featured Sellers</h3>
                            <p style="color: #666; font-size: 16px;">Check back soon for featured sellers</p>
                        </div>
                    @endif
                </div>
            </section>

            <style>
                /* Vendor Card Hover Effects */
                .vendor-card {
                    cursor: pointer;
                }

                .vendor-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 12px 40px rgba(0,0,0,0.15) !important;
                    border-color: #0d5c63 !important;
                }

                .visit-store-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(255,165,0,0.4) !important;
                }

                .visit-store-btn:hover i:last-child {
                    transform: translateX(4px);
                }

                .view-all-btn:hover {
                    background: #0d5c63;
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(255,165,0,0.3);
                }

                .view-all-btn:hover i {
                    transform: translateX(4px);
                }

                /* Responsive Grid */
                @media (max-width: 1200px) {
                    .vendors-grid {
                        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
                        gap: 25px !important;
                    }
                }

                @media (max-width: 768px) {
                    .vendors-grid {
                        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)) !important;
                        gap: 20px !important;
                    }

                    .section-header h2 {
                        font-size: 32px !important;
                    }

                    .section-header p {
                        font-size: 16px !important;
                    }
                }

                @media (max-width: 480px) {
                    .vendors-grid {
                        grid-template-columns: 1fr !important;
                        gap: 20px !important;
                    }

                    .section-header h2 {
                        font-size: 28px !important;
                    }
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
                            <input type="email" id="newsletter-email" placeholder="Enter your email address">
                            <button type="button" onclick="subscribeNewsletter()" class="btn-subscribe">Subscribe</button>
                        </div>
                        <div id="newsletter-msg" class="mt-2 text-sm hidden"></div>
                    </div>
                </div>
            </section>

<script>
function subscribeNewsletter() {
    const email = document.getElementById('newsletter-email').value.trim();
    const msg   = document.getElementById('newsletter-msg');
    const btn   = document.querySelector('.btn-subscribe');

    if (!email) {
        msg.textContent = 'Please enter your email address.';
        msg.className = 'mt-2 text-sm text-red-300';
        msg.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Subscribing...';

    fetch('{{ route("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ email })
    })
    .then(r => r.json())
    .then(data => {
        msg.textContent = data.message;
        msg.className = 'mt-2 text-sm ' + (data.success ? 'text-green-300' : 'text-red-300');
        msg.classList.remove('hidden');
        if (data.success) {
            document.getElementById('newsletter-email').value = '';
            btn.textContent = '✓ Subscribed!';
        } else {
            btn.disabled = false;
            btn.textContent = 'Subscribe';
        }
    })
    .catch(() => {
        msg.textContent = 'Something went wrong. Please try again.';
        msg.className = 'mt-2 text-sm text-red-300';
        msg.classList.remove('hidden');
        btn.disabled = false;
        btn.textContent = 'Subscribe';
    });
}
</script>
@endsection


@push('scripts')
<script>
// Quick Add to Cart function
function quickAddToCart(productId, button) {
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.style.background = 'linear-gradient(to right, #27ae60, #229954)';
            showToast('Product added to cart!', 'success');
            updateCartBadge(data.cartCount);
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalContent;
                button.style.background = '';
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to add product');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalContent;
        showToast('Failed to add product to cart', 'error');
    });
}

// Buy Now function
function buyNow(productId, button) {
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    fetch('/cart/buy-now', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            product_id: productId,
            quantity: 1 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/checkout';
        } else {
            throw new Error(data.message || 'Failed to process');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalContent;
        showToast('Failed to process order', 'error');
    });
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; top: 20px; right: 20px;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white; padding: 15px 25px; border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 10000;
        font-size: 16px; font-weight: 500;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.querySelector('.action-link .fas.fa-shopping-bag')?.parentElement.querySelector('span');
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            const cartLink = document.querySelector('.action-link .fas.fa-shopping-bag')?.parentElement;
            if (cartLink) {
                const newBadge = document.createElement('span');
                newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #0d5c63; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
                newBadge.textContent = count;
                cartLink.appendChild(newBadge);
            }
        }
    }
}
</script>
@endpush
