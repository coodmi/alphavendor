@extends('layouts.app')

@section('title', 'Import - AlphaVendor Multi Vendor Marketplace')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/import-mobile.css') }}">
@endpush

@section('content')
<!-- Hero Banner -->
<section class="import-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge"><i class="fas fa-globe"></i> Global Import</span>
                <h1>International Import Marketplace</h1>
                <p>Connect with verified international suppliers and source quality products globally. Import premium products with seamless logistics and documentation support.</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <i class="fas fa-globe-americas"></i>
                        <div>
                            <h3>{{ $locations->count() }}+</h3>
                            <span>Countries</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-ship"></i>
                        <div>
                            <h3>{{ number_format($products->total()) }}+</h3>
                            <span>Import Products</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-handshake"></i>
                        <div>
                            <h3>{{ $categories->count() }}+</h3>
                            <span>Categories</span>
                        </div>
                    </div>
                </div>
                <div class="hero-buttons">
                    <button class="btn-primary">
                        <i class="fas fa-plane-arrival"></i> Start Importing
                    </button>
                    <button class="btn-secondary">
                        <i class="fas fa-file-contract"></i> View Documentation
                    </button>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1548588627-f978862b85e1?w=1100&h=750&fit=crop" alt="Global Import Cargo Logistics">
            </div>
        </div>
    </div>
</section>

<!-- Shop Section with Tailwind -->
<section class="py-10">
    <div class="container mx-auto px-4">
        <!-- Mobile Filter Toggle Button -->
        <button class="mobile-filter-toggle-wholesale" id="mobileFilterToggleImport">
            <i class="fas fa-filter"></i>
            Filters & Categories
        </button>

        <!-- Filter Sidebar Overlay -->
        <div class="filter-sidebar-overlay-wholesale" id="filterSidebarOverlayImport"></div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0" id="importSidebar">
                <!-- Mobile Filter Header -->
                <div class="filter-sidebar-header-wholesale" style="display: none;">
                    <h3>Filters</h3>
                    <button class="filter-close-btn-wholesale" id="filterCloseBtnImport">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form method="GET" action="{{ route('import') }}" id="filterForm">
                    <!-- Categories Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-tags text-teal-600"></i> Import Categories
                        </h3>
                        <ul class="space-y-2">
                            @forelse($categories as $category)
                            <li>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600">
                                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $category->products_count }})</span>
                                </label>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 p-2">No categories available</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Supplier Location Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-teal-600"></i> Supplier Location
                        </h3>
                        <ul class="space-y-2">
                            @forelse($locations as $location)
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="location" value="{{ $location }}" {{ request('location') == $location ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600 mr-2">
                                    <span class="text-sm text-gray-700">{{ $location }}</span>
                                </label>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 p-2">No locations available</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-dollar-sign text-teal-600"></i> Price Range
                        </h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <label class="text-xs text-gray-600 block mb-1">Min</label>
                                    <input type="number" name="min_price" value="{{ request('min_price', 0) }}" min="0" max="50000" id="import-min-price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-teal-600 text-sm">
                                </div>
                                <div class="flex-1">
                                    <label class="text-xs text-gray-600 block mb-1">Max</label>
                                    <input type="number" name="max_price" value="{{ request('max_price', 50000) }}" min="0" max="50000" id="import-max-price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-teal-600 text-sm">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition-colors font-medium text-sm">
                                Apply Filter
                            </button>
                        </div>
                    </div>

                    <!-- Minimum Order Filter -->
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-boxes text-teal-600"></i> Minimum Order
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="min_order" value="100" {{ request('min_order') == '100' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600 mr-2">
                                    <span class="text-sm text-gray-700">100+ Units</span>
                                </label>
                            </li>
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="min_order" value="500" {{ request('min_order') == '500' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600 mr-2">
                                    <span class="text-sm text-gray-700">500+ Units</span>
                                </label>
                            </li>
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="min_order" value="1000" {{ request('min_order') == '1000' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600 mr-2">
                                    <span class="text-sm text-gray-700">1000+ Units</span>
                                </label>
                            </li>
                            <li>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <input type="radio" name="min_order" value="5000" {{ request('min_order') == '5000' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600 mr-2">
                                    <span class="text-sm text-gray-700">5000+ Units</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <!-- Brands Filter -->
                    @if(isset($brands) && $brands->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-5 mb-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-teal-600"></i> Brands
                        </h3>
                        <ul class="space-y-2">
                            @foreach($brands as $brand)
                            <li>
                                <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()" class="text-teal-600 focus:ring-teal-600">
                                        <span class="text-sm text-gray-700">{{ $brand->name }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">({{ $brand->products_count ?? 0 }})</span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Clear All Filters -->
                    <a href="{{ route('import') }}" class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
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
                            Showing <strong class="text-gray-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of <strong class="text-gray-900">{{ $products->total() }}</strong> import products
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button class="p-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors" data-view="grid">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors" data-view="list">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if(request()->hasAny(['category', 'location', 'min_price', 'max_price', 'min_order', 'brand']))
                <div class="flex flex-wrap gap-2 mb-6">
                    @if(request('category'))
                        @php $selectedCategory = $categories->firstWhere('id', request('category')); @endphp
                        @if($selectedCategory)
                        <span class="inline-flex items-center gap-2 bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-sm">
                            {{ $selectedCategory->name }}
                            <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="hover:text-teal-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                    @endif
                    @if(request('location'))
                        <span class="inline-flex items-center gap-2 bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-sm">
                            Location: {{ request('location') }}
                            <a href="{{ request()->fullUrlWithoutQuery('location') }}" class="hover:text-teal-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('min_order'))
                        <span class="inline-flex items-center gap-2 bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-sm">
                            Min Order: {{ request('min_order') }}+ Units
                            <a href="{{ request()->fullUrlWithoutQuery('min_order') }}" class="hover:text-teal-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('brand'))
                        @php $selectedBrand = collect($brands ?? [])->firstWhere('id', request('brand')); @endphp
                        @if($selectedBrand)
                        <span class="inline-flex items-center gap-2 bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-sm">
                            {{ $selectedBrand->name }}
                            <a href="{{ request()->fullUrlWithoutQuery('brand') }}" class="hover:text-teal-900">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="inline-flex items-center gap-2 bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-sm">
                            Price: {{ currency_symbol() }}{{ request('min_price', 0) }} - {{ currency_symbol() }}{{ request('max_price', 50000) }}
                            <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}" class="hover:text-teal-900">
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
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-teal-600 hover:text-white transition-colors" title="Add to Wishlist" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-teal-600 hover:text-white transition-colors" title="Quick View" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-eye"></i>
                                </button>
                                <button class="bg-white p-2 rounded-full shadow-md hover:bg-teal-600 hover:text-white transition-colors" title="Compare" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="flex-1 bg-teal-600 text-white py-3 font-semibold flex items-center justify-center gap-2" data-product-id="{{ $product->id }}" onclick="quickAddToCart({{ $product->id }}, this); event.preventDefault(); event.stopPropagation();">
                                <i class="fas fa-shopping-cart"></i>
                                Quick Add
                            </button>
                            <button class="flex-1 bg-teal-700 text-white py-3 font-semibold flex items-center justify-center gap-2" data-product-id="{{ $product->id }}" onclick="buyNow({{ $product->id }}, this); event.preventDefault(); event.stopPropagation();">
                                <i class="fas fa-bolt"></i>
                                Buy Now
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-teal-700 font-semibold mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            <h4 class="text-sm font-bold text-gray-800 mb-2 line-clamp-2 hover:text-teal-600 transition-colors">{{ $product->name }}</h4>
                            <div class="flex items-center gap-1 text-xs text-gray-600 mb-1">
                                <i class="fas fa-industry text-teal-600"></i>
                                <span>{{ $product->vendor->name ?? 'Unknown Vendor' }}</span>
                            </div>
                            @if($product->supplier_location)
                            <div class="flex items-center gap-1 text-xs text-gray-600 mb-2">
                                <i class="fas fa-map-marker-alt text-teal-600"></i>
                                <span>{{ $product->supplier_location }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->rating ?? 0))
                                        <i class="fas fa-star text-teal-500 text-xs"></i>
                                    @elseif($i - 0.5 <= ($product->rating ?? 0))
                                        <i class="fas fa-star-half-alt text-teal-500 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-teal-500 text-xs"></i>
                                    @endif
                                @endfor
                                <span class="text-xs text-gray-600">({{ number_format($product->rating ?? 0, 1) }}) {{ $product->reviews_count ?? 0 }} reviews</span>
                            </div>
                            @if($product->minimum_order)
                            <div class="flex items-center gap-1 text-xs text-gray-600 mb-3">
                                <i class="fas fa-boxes text-teal-600"></i>
                                <span>Min: {{ $product->minimum_order }} units</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-lg font-bold text-gray-900"> {{ currency($product->price) }}</span>
                                @if($product->old_price)
                                    <span class="text-sm text-gray-500 line-through"> {{ currency($product->old_price) }}</span>
                                    <span class="text-xs font-semibold text-red-500">-{{ round((($product->old_price - $product->price) / $product->old_price) * 100) }}%</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Products Found</h3>
                        <p class="text-gray-500">Try adjusting your filters or <a href="{{ route('import') }}" class="text-teal-600 hover:text-teal-700">clear all filters</a>.</p>
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
            newBadge.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #0d5c63; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;';
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
}

// Buy Now function
function buyNow(productId, button) {
    const originalContent = button.innerHTML;

    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

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
            // Redirect to checkout
            window.location.href = '/checkout';
        } else {
            throw new Error(data.message || 'Failed to add product');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = originalContent;
        showToast('Failed to process order', 'error');
    });
}

// Mobile Filter Toggle Functionality for Import
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterToggle = document.getElementById('mobileFilterToggleImport');
    const importSidebar = document.getElementById('importSidebar');
    const filterSidebarOverlay = document.getElementById('filterSidebarOverlayImport');
    const filterCloseBtn = document.getElementById('filterCloseBtnImport');
    const filterHeader = document.querySelector('.filter-sidebar-header-wholesale');

    // Show filter header on mobile
    function updateFilterHeader() {
        if (window.innerWidth <= 1024 && filterHeader) {
            filterHeader.style.display = 'flex';
        } else if (filterHeader) {
            filterHeader.style.display = 'none';
        }
    }

    updateFilterHeader();
    window.addEventListener('resize', updateFilterHeader);

    function openFilters() {
        if (importSidebar && filterSidebarOverlay) {
            importSidebar.classList.add('active');
            filterSidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeFilters() {
        if (importSidebar && filterSidebarOverlay) {
            importSidebar.classList.remove('active');
            filterSidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (mobileFilterToggle) {
        mobileFilterToggle.addEventListener('click', openFilters);
    }

    if (filterCloseBtn) {
        filterCloseBtn.addEventListener('click', closeFilters);
    }

    if (filterSidebarOverlay) {
        filterSidebarOverlay.addEventListener('click', closeFilters);
    }

    // Close filters when applying filter
    const applyFilterBtn = document.querySelector('.bg-teal-600');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            setTimeout(closeFilters, 300);
        });
    }
});
</script>
@endpush
