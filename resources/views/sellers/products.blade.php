@extends('layouts.app')

@section('title', (($businessInfo->business_name ?? $seller->name) . ' - Products'))

@section('content')
<div class="seller-products-page" style="background: #f8f9fa; padding: 40px 0; min-height: 80vh;">
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <!-- Seller Header -->
        @php
            $roleColor = $seller->role === 'retailer' ? '#4CAF50' :
                        ($seller->role === 'wholesaler' ? '#2196F3' : '#9C27B0');
            $roleIcon = $seller->role === 'retailer' ? 'fa-store' :
                       ($seller->role === 'wholesaler' ? 'fa-warehouse' : 'fa-globe');
            $vendorName = $businessInfo->business_name ?? $seller->name;
            $firstLetter = strtoupper(substr($vendorName, 0, 1));
        @endphp

        <div style="background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 30px;">
                <div style="display: flex; gap: 25px; align-items: center; flex: 1;">
                    <!-- Vendor Photo/Avatar -->
                    <div style="position: relative; flex-shrink: 0;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 4px solid {{ $roleColor }}20; box-shadow: 0 4px 12px rgba(0,0,0,0.15); background: {{ $roleColor }};">
                            @if($seller->profile_image)
                                <img src="{{ asset('storage/' . $seller->profile_image) }}"
                                     alt="{{ $vendorName }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, {{ $roleColor }} 0%, {{ $roleColor }}dd 100%); color: white; font-size: 40px; font-weight: 700;">
                                    {{ $firstLetter }}
                                </div>
                            @endif
                        </div>
                        
                        @if($seller->vendorBadge && $seller->vendorBadge->is_active)
                            <div style="position: absolute; top: -5px; right: -10px; width: 38px; height: 38px; background: {{ $seller->vendorBadge->bg_color }}; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.25); z-index: 10;">
                                @if($seller->vendorBadge->icon)
                                    @if(str_starts_with($seller->vendorBadge->icon, 'fa'))
                                        <i class="{{ $seller->vendorBadge->icon }}" style="color: {{ $seller->vendorBadge->color }}; font-size: 16px;"></i>
                                    @else
                                        <span style="color: {{ $seller->vendorBadge->color }}; font-size: 18px; line-height: 1;">{{ $seller->vendorBadge->icon }}</span>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>

                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; flex-wrap: wrap;">
                            <span style="background: {{ $roleColor }}; color: white; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase;">
                                <i class="fas {{ $roleIcon }}" style="margin-right: 6px;"></i>
                                {{ $seller->role === 'exporter' ? 'Importer' : ucfirst($seller->role) }}
                            </span>
                            
                            @php
                                $vendorRating = $seller->getVendorRating();
                                $reviewCount = $seller->getVendorReviewCount();
                            @endphp
                            @if($vendorRating > 0)
                                <span style="background: #FFD700; color: #333; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    <i class="fas fa-star"></i>
                                    {{ number_format($vendorRating, 1) }} ({{ $reviewCount }} {{ $reviewCount == 1 ? 'review' : 'reviews' }})
                                </span>
                            @endif
                        </div>
                        <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 12px;">
                            {{ $vendorName }}
                        </h1>

                        @if($businessInfo)
                            <div style="display: flex; flex-wrap: wrap; gap: 20px; color: #666; font-size: 14px;">
                                @if($businessInfo->business_address)
                                    <span><i class="fas fa-map-marker-alt" style="color: {{ $roleColor }}; margin-right: 6px;"></i>
                                        {{ $businessInfo->city ?? '' }}{{ $businessInfo->state ? ', ' . $businessInfo->state : '' }}
                                    </span>
                                @endif
                                @if($businessInfo->business_phone)
                                    <span><i class="fas fa-phone" style="color: {{ $roleColor }}; margin-right: 6px;"></i>{{ $businessInfo->business_phone }}</span>
                                @endif
                                @if($businessInfo->business_email)
                                    <span><i class="fas fa-envelope" style="color: {{ $roleColor }}; margin-right: 6px;"></i>{{ $businessInfo->business_email }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <a href="{{ route('sellers.index') }}"
                   style="padding: 12px 24px; background: white; color: #666; border: 2px solid #ddd; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; flex-shrink: 0;"
                   onmouseover="this.style.borderColor='#0d5c63'; this.style.color='#0d5c63'"
                   onmouseout="this.style.borderColor='#ddd'; this.style.color='#666'">
                    <i class="fas fa-arrow-left"></i>
                    Back to Sellers
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px;">
            <!-- Left Sidebar - Filters -->
            <div class="filters-sidebar" style="height: fit-content;">
                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h3 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #0d5c63;">
                        <i class="fas fa-filter" style="margin-right: 8px;"></i>Filters
                    </h3>

                    <form method="GET" action="{{ route('sellers.products', $seller->id) }}" id="filterForm">
                        <!-- Search -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-search" style="margin-right: 6px; color: #0d5c63;"></i>Search Product
                            </label>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search products..."
                                   style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>

                        <!-- Category Filter -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-th-large" style="margin-right: 6px; color: #0d5c63;"></i>Category
                            </label>
                            <select name="category"
                                    style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-dollar-sign" style="margin-right: 6px; color: #0d5c63;"></i>Price Range
                            </label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="number"
                                       name="min_price"
                                       value="{{ request('min_price') }}"
                                       placeholder="Min"
                                       style="width: 50%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px;">
                                <span style="color: #999;">-</span>
                                <input type="number"
                                       name="max_price"
                                       value="{{ request('max_price') }}"
                                       placeholder="Max"
                                       style="width: 50%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px;">
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-sort" style="margin-right: 6px; color: #0d5c63;"></i>Sort By
                            </label>
                            <select name="sort"
                                    style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <button type="submit"
                                    style="width: 100%; padding: 12px; background: #0d5c63; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 14px;">
                                <i class="fas fa-search" style="margin-right: 8px;"></i>Apply Filters
                            </button>
                            <a href="{{ route('sellers.products', $seller->id) }}"
                               style="width: 100%; padding: 12px; background: white; color: #666; border: 1px solid #ddd; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-align: center; text-decoration: none; font-size: 14px; display: block;">
                                <i class="fas fa-redo" style="margin-right: 8px;"></i>Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Content - Products Listing -->
            <div>
                <!-- Results Count -->
                <div style="background: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <p style="color: #666; font-size: 14px; margin: 0;">
                        <strong style="color: #333;">{{ $products->total() }}</strong> products found
                    </p>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
                        @foreach($products as $product)
                            @php
                                $price = $seller->role === 'retailer' ? $product->retail_price :
                                        ($seller->role === 'wholesaler' ? $product->wholesale_price : $product->export_price);
                                $minOrder = $seller->role === 'wholesaler' ? $product->wholesale_min_order :
                                           ($seller->role === 'exporter' ? $product->export_min_order : 1);
                            @endphp
                            <div class="product-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s;">
                                <!-- Product Image -->
                                <div style="position: relative; padding-top: 100%; background: #f5f5f5; overflow: hidden;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 48px; color: #ccc;"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div style="padding: 15px;">
                                    <h4 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px; line-height: 1.4; height: 44px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $product->name }}
                                    </h4>

                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <span style="font-size: 20px; font-weight: 700; color: #0d5c63;">
                                            {{ currency(($price, 2) }}
                                        </span>
                                        @if($minOrder > 1)
                                            <span style="font-size: 12px; color: #666; background: #f0f0f0; padding: 4px 8px; border-radius: 4px;">
                                                Min: {{ $minOrder }}
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route('product.show', $product->id) }}"
                                       style="display: block; width: 100%; padding: 10px; background: #0d5c63; color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;"
                                       onmouseover="this.style.background='#e69500'"
                                       onmouseout="this.style.background='#0d5c63'">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div style="background: white; padding: 60px 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-box-open" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                        <h3 style="font-size: 24px; color: #333; margin-bottom: 10px;">No Products Found</h3>
                        <p style="color: #666; margin-bottom: 20px;">This seller hasn't listed any products yet or no products match your filters</p>
                        <a href="{{ route('sellers.products', $seller->id) }}"
                           style="display: inline-block; padding: 12px 24px; background: #0d5c63; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }
</style>
@endpush
@endsection
