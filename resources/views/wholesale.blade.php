@extends('layouts.app')

@section('title', 'Wholesale - AlphaVendor Multi Vendor Marketplace')

@section('content')

<!-- Hero Banner with Tailwind -->
<section class="bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 py-20 mb-10 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1 text-white">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white px-4 py-1.5 rounded-full text-sm font-semibold mb-3 border border-white/30">B2B Solutions</span>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">Wholesale Marketplace</h1>
                <p class="text-lg text-white/90 mb-6 max-w-2xl">Connect with verified suppliers and manufacturers. Buy in bulk with competitive wholesale prices for your business.</p>
                
                <!-- Stats -->
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl border border-white/20">
                        <i class="fas fa-industry text-3xl"></i>
                        <div>
                            <h3 class="text-2xl font-bold">2,500+</h3>
                            <span class="text-sm text-white/80">Manufacturers</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl border border-white/20">
                        <i class="fas fa-boxes text-3xl"></i>
                        <div>
                            <h3 class="text-2xl font-bold">50K+</h3>
                            <span class="text-sm text-white/80">Bulk Products</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl border border-white/20">
                        <i class="fas fa-handshake text-3xl"></i>
                        <div>
                            <h3 class="text-2xl font-bold">15K+</h3>
                            <span class="text-sm text-white/80">Business Partners</span>
                        </div>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-wrap gap-4">
                    <button class="bg-white text-orange-500 px-6 py-3 rounded-lg font-semibold hover:bg-orange-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-shopping-cart"></i> Start Ordering
                    </button>
                    <button class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/10 transition-colors flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Register as Buyer
                    </button>
                </div>
            </div>
            
            <!-- Feature Cards -->
            <div class="flex-1 relative">
                <div class="grid grid-cols-1 gap-4 max-w-md mx-auto">
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 transform hover:scale-105 transition-transform">
                        <i class="fas fa-truck text-4xl text-white mb-3"></i>
                        <h4 class="text-xl font-bold text-white mb-1">Fast Shipping</h4>
                        <p class="text-white/80">Global delivery</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 transform hover:scale-105 transition-transform ml-8">
                        <i class="fas fa-shield-alt text-4xl text-white mb-3"></i>
                        <h4 class="text-xl font-bold text-white mb-1">Secure Payment</h4>
                        <p class="text-white/80">100% protected</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 transform hover:scale-105 transition-transform">
                        <i class="fas fa-percentage text-4xl text-white mb-3"></i>
                        <h4 class="text-xl font-bold text-white mb-1">Best Prices</h4>
                        <p class="text-white/80">Bulk discounts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop Section with Tailwind -->
<section class="py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <form method="GET" action="{{ route('wholesale') }}" id="filterForm">
                    <!-- Categories Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-tags text-orange-500"></i> Categories
                        </h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                            <li>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-orange-500 focus:ring-orange-500">
                                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $category->products_count }})</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Minimum Order Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-boxes text-orange-500"></i> Minimum Order
                        </h3>
                        <ul class="space-y-2">
                            @foreach($minOrderRanges as $value => $label)
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="minimum_order" value="{{ $value }}" {{ request('minimum_order') == $value ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-orange-500 focus:ring-orange-500 mr-2">
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-dollar-sign text-orange-500"></i> Price Range
                        </h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <label class="text-xs text-gray-600 block mb-1">Min</label>
                                    <input type="number" name="min_price" value="{{ request('min_price', 0) }}" min="0" max="10000" id="wholesale-min-price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs text-gray-600 block mb-1">Max</label>
                                    <input type="number" name="max_price" value="{{ request('max_price', 10000) }}" min="0" max="10000" id="wholesale-max-price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium text-sm">
                                Apply Filter
                            </button>
                        </div>
                    </div>

                    <!-- Supplier Location Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-orange-500"></i> Supplier Location
                        </h3>
                        <ul class="space-y-2">
                            @foreach($supplierLocations as $location)
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="supplier_location" value="{{ $location->country }}" {{ request('supplier_location') == $location->country ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-orange-500 focus:ring-orange-500 mr-2">
                                    <span class="text-sm text-gray-700">{{ $location->country }}</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Brands Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-orange-500"></i> Brands
                        </h3>
                        <ul class="space-y-2">
                            @foreach($brands as $brand)
                            <li>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-orange-500 focus:ring-orange-500">
                                        <span class="text-sm text-gray-700">{{ $brand->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $brand->products_count }})</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Clear All Filters -->
                    <a href="{{ route('wholesale') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                        <i class="fas fa-times"></i> Clear All Filters
                    </a>
                </form>
            </aside>

            <!-- Products Area -->
            <div class="flex-1">
                <!-- Toolbar -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-600">
                            Showing <strong class="text-gray-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of <strong class="text-gray-900">{{ $products->total() }}</strong> wholesale products
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button class="p-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if(request()->hasAny(['category', 'minimum_order', 'supplier_location', 'brand', 'min_price', 'max_price']))
                <div class="flex flex-wrap gap-2 mb-6">
                    @if(request('category'))
                        @php $selectedCategory = $categories->firstWhere('id', request('category')); @endphp
                        @if($selectedCategory)
                        <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full text-sm">
                            {{ $selectedCategory->name }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="hover:text-orange-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                    @endif
                    @if(request('minimum_order'))
                        <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full text-sm">
                            Min Order: {{ $minOrderRanges[request('minimum_order')] ?? request('minimum_order') }}
                            <a href="{{ request()->fullUrlWithoutQuery('minimum_order') }}" class="hover:text-orange-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('supplier_location'))
                        <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full text-sm">
                            Location: {{ request('supplier_location') }}
                            <a href="{{ request()->fullUrlWithoutQuery('supplier_location') }}" class="hover:text-orange-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('brand'))
                        @php $selectedBrand = $brands->firstWhere('id', request('brand')); @endphp
                        @if($selectedBrand)
                        <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full text-sm">
                            {{ $selectedBrand->name }}
                            <a href="{{ request()->fullUrlWithoutQuery('brand') }}" class="hover:text-orange-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full text-sm">
                            Price: ${{ request('min_price', 0) }} - ${{ request('max_price', 10000) }}
                            <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}" class="hover:text-orange-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
                @endif

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($products as $product)
                    <a href="{{ route('product.show', $product->id) }}?quantity={{ $product->minimum_order ?? 1 }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow group block">
                        <div class="relative overflow-hidden aspect-square">
                            @if($product->image)
                                @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @endif
                            @else
                                <img src="https://via.placeholder.com/300x300?text=No+Image" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @endif
                            @if($product->badge)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">{{ $product->badge }}</span>
                            @endif
                            <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-orange-500 hover:text-white transition-colors" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-orange-500 hover:text-white transition-colors" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-orange-500 hover:text-white transition-colors" title="Compare" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <button class="absolute bottom-0 left-0 right-0 bg-orange-500 text-white py-3 font-semibold opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2" data-product-id="{{ $product->id }}" onclick="quickAddToCart({{ $product->id }}, this); event.preventDefault(); event.stopPropagation();">
                                <i class="fas fa-shopping-cart"></i>
                                Quick Add
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-orange-600 font-semibold mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h4 class="text-sm font-bold text-gray-800 mb-2 line-clamp-2 hover:text-orange-500 transition-colors">{{ $product->name }}</h4>
                            <div class="flex items-center gap-1 text-xs text-gray-600 mb-1">
                                <i class="fas fa-industry text-orange-500"></i>
                                <span>{{ $product->vendor->name ?? 'Unknown Vendor' }}</span>
                            </div>
                            @if($product->supplier_location)
                            <div class="flex items-center gap-1 text-xs text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt text-orange-500"></i>
                                <span>{{ $product->supplier_location }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating))
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @elseif($i - 0.5 <= $product->rating)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-yellow-400 text-xs"></i>
                                    @endif
                                @endfor
                                <span class="text-xs text-gray-600">({{ number_format($product->rating, 1) }}) {{ $product->reviews_count }} reviews</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-600 mb-3">
                                <i class="fas fa-boxes text-orange-500"></i>
                                <span>Min: {{ $product->minimum_order }} units</span>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @if($product->old_price)
                                    <span class="text-sm text-gray-500 line-through">${{ number_format($product->old_price, 2) }}</span>
                                    <span class="text-xs font-semibold text-red-500">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Products Found</h3>
                        <p class="text-gray-500">Try adjusting your filters or <a href="{{ route('wholesale') }}" class="text-orange-500 hover:text-orange-600">clear all filters</a>.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Price Range Slider - Synchronize sliders with inputs
document.addEventListener('DOMContentLoaded', function() {
    const rangeMin = document.querySelector("#wholesale-range-min");
    const rangeMax = document.querySelector("#wholesale-range-max");
    const minPriceInput = document.querySelector('#wholesale-min-price');
    const maxPriceInput = document.querySelector('#wholesale-max-price');

    if (!rangeMin || !rangeMax || !minPriceInput || !maxPriceInput) {
        return;
    }

    // Update min slider
    rangeMin.addEventListener('input', function() {
        let min = parseInt(this.value);
        let max = parseInt(rangeMax.value);

        if (min > max - 100) {
            min = max - 100;
            this.value = min;
        }

        minPriceInput.value = min;
    });

    // Update max slider
    rangeMax.addEventListener('input', function() {
        let min = parseInt(rangeMin.value);
        let max = parseInt(this.value);

        if (max < min + 100) {
            max = min + 100;
            this.value = max;
        }

        maxPriceInput.value = max;
    });

    // Update min input
    minPriceInput.addEventListener('input', function() {
        let min = parseInt(this.value) || 0;
        let max = parseInt(maxPriceInput.value) || 10000;

        if (min < 0) {
            min = 0;
            this.value = min;
        }
        if (min > max - 100) {
            min = max - 100;
            this.value = min;
        }

        rangeMin.value = min;
    });

    // Update max input
    maxPriceInput.addEventListener('input', function() {
        let min = parseInt(minPriceInput.value) || 0;
        let max = parseInt(this.value) || 10000;

        if (max > 10000) {
            max = 10000;
            this.value = max;
        }
        if (max < min + 100) {
            max = min + 100;
            this.value = max;
        }

        rangeMax.value = max;
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 10000;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease-out;
    `;

    const icon = type === 'success' ? '✓' : '✕';
    toast.innerHTML = `<span style="font-size: 20px;">${icon}</span>${message}`;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add CSS animations
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement.querySelector('span');
    if (count > 0) {
        if (badge) {
            badge.textContent = count;
        } else {
            const cartLink = document.querySelector('.action-link .fas.fa-shopping-bag').parentElement;
            const newBadge = document.createElement('span');
            newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #FFA500; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
            newBadge.textContent = count;
            cartLink.appendChild(newBadge);
        }
    }
}

// Quick Add to Cart function
function quickAddToCart(productId, button) {
    const originalContent = button.innerHTML;

    // Disable button and show loading
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
            // Show success state
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.style.background = '#27ae60';

            // Show toast notification
            showToast('Product added to cart successfully!', 'success');

            // Update cart badge
            updateCartBadge(data.cartCount);

            // Reset button after 2 seconds
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
}</script>
@endpush