@extends('layouts.app')

@section('title', 'Sellers Directory - AlphaVendor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sellers-mobile.css') }}">
<style>
    .filter-loading {
        position: relative;
    }
    .filter-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #FFA500;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .seller-card {
        transition: all 0.3s ease;
    }
    .seller-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }
</style>
@endpush

@section('content')
<div class="sellers-page" style="background: #f8f9fa; padding: 40px 0; min-height: 80vh;">
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <!-- Page Header -->
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 10px;">Sellers Directory</h1>
            <p style="color: #666; font-size: 16px;">Find trusted retailers, wholesalers, and importers</p>
        </div>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px;">
            <!-- Mobile Filter Toggle Button -->
            <button class="mobile-filter-toggle-sellers" id="mobileFilterToggleSellers">
                <i class="fas fa-filter"></i>
                Filters
            </button>

            <!-- Filter Sidebar Overlay -->
            <div class="filter-sidebar-overlay-sellers" id="filterSidebarOverlaySellers"></div>

            <!-- Left Sidebar - Filters -->
            <div class="filters-sidebar" id="sellersSidebar" style="height: fit-content;">
                <!-- Mobile Filter Header -->
                <div class="filter-sidebar-header-sellers" style="display: none;">
                    <h3>Filters</h3>
                    <button class="filter-close-btn-sellers" id="filterCloseBtnSellers">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h3 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #FFA500;">
                        <i class="fas fa-filter" style="margin-right: 8px;"></i>Filters
                    </h3>

                    <form method="GET" action="{{ route('sellers.index') }}" id="filterForm">
                        <!-- Search Bar -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-search" style="margin-right: 6px; color: #FFA500;"></i>Search Seller
                            </label>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by name..."
                                   style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                                   onfocus="this.style.borderColor='#FFA500'"
                                   onblur="this.style.borderColor='#ddd'">
                        </div>

                        <!-- Seller Type Filter -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-store" style="margin-right: 6px; color: #FFA500;"></i>Seller Type
                            </label>
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="type"
                                           value=""
                                           {{ request('type') === '' || !request()->has('type') ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">All Sellers</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="type"
                                           value="retailer"
                                           {{ request('type') === 'retailer' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">Retailers</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="type"
                                           value="wholesaler"
                                           {{ request('type') === 'wholesaler' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">Wholesalers</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="type"
                                           value="exporter"
                                           {{ request('type') === 'exporter' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">Importers</span>
                                </label>
                            </div>
                        </div>

                        <!-- Location Filter -->
                        @if(isset($locations) && $locations->count() > 0)
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 6px; color: #FFA500;"></i>Location
                            </label>
                            <select name="location"
                                    style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: all 0.3s; cursor: pointer;"
                                    onfocus="this.style.borderColor='#FFA500'"
                                    onblur="this.style.borderColor='#ddd'">
                                <option value="">All Locations</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ request('location') === $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Minimum Rating Filter -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-star" style="margin-right: 6px; color: #FFA500;"></i>Minimum Rating
                            </label>
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="min_rating"
                                           value=""
                                           {{ !request()->has('min_rating') || request('min_rating') === '' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">All Ratings</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="min_rating"
                                           value="4.5"
                                           {{ request('min_rating') === '4.5' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">
                                        <i class="fas fa-star" style="color: #FFB800;"></i> 4.5+
                                    </span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="min_rating"
                                           value="4.0"
                                           {{ request('min_rating') === '4.0' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">
                                        <i class="fas fa-star" style="color: #FFB800;"></i> 4.0+
                                    </span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; border-radius: 6px; transition: background 0.3s;"
                                       onmouseover="this.style.background='#fff4e6'"
                                       onmouseout="this.style.background='transparent'">
                                    <input type="radio"
                                           name="min_rating"
                                           value="3.5"
                                           {{ request('min_rating') === '3.5' ? 'checked' : '' }}
                                           style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-size: 14px; color: #555;">
                                        <i class="fas fa-star" style="color: #FFB800;"></i> 3.5+
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Minimum Products Filter -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-boxes" style="margin-right: 6px; color: #FFA500;"></i>Minimum Products
                            </label>
                            <select name="min_products"
                                    style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: all 0.3s; cursor: pointer;"
                                    onfocus="this.style.borderColor='#FFA500'"
                                    onblur="this.style.borderColor='#ddd'">
                                <option value="">Any Amount</option>
                                <option value="10" {{ request('min_products') === '10' ? 'selected' : '' }}>10+ Products</option>
                                <option value="50" {{ request('min_products') === '50' ? 'selected' : '' }}>50+ Products</option>
                                <option value="100" {{ request('min_products') === '100' ? 'selected' : '' }}>100+ Products</option>
                                <option value="500" {{ request('min_products') === '500' ? 'selected' : '' }}>500+ Products</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 600; color: #333; margin-bottom: 10px; font-size: 14px;">
                                <i class="fas fa-sort" style="margin-right: 6px; color: #FFA500;"></i>Sort By
                            </label>
                            <select name="sort"
                                    style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: all 0.3s; cursor: pointer;"
                                    onfocus="this.style.borderColor='#FFA500'"
                                    onblur="this.style.borderColor='#ddd'">
                                <option value="latest" {{ request('sort') === 'latest' || !request()->has('sort') ? 'selected' : '' }}>Latest First</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="products_high" {{ request('sort') === 'products_high' ? 'selected' : '' }}>Most Products</option>
                                <option value="products_low" {{ request('sort') === 'products_low' ? 'selected' : '' }}>Least Products</option>
                                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rating</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <button type="submit"
                                    style="width: 100%; padding: 12px; background: #FFA500; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-size: 14px;"
                                    onmouseover="this.style.background='#e69500'"
                                    onmouseout="this.style.background='#FFA500'">
                                <i class="fas fa-search" style="margin-right: 8px;"></i>Apply Filters
                            </button>
                            <a href="{{ route('sellers.index') }}"
                               style="width: 100%; padding: 12px; background: white; color: #666; border: 1px solid #ddd; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-align: center; text-decoration: none; font-size: 14px; display: block;"
                               onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#999'"
                               onmouseout="this.style.background='white'; this.style.borderColor='#ddd'">
                                <i class="fas fa-redo" style="margin-right: 8px;"></i>Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Content - Sellers Listing -->
            <div id="sellersContent">
                <!-- Results Count -->
                <div style="background: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <p style="color: #666; font-size: 14px; margin: 0;">
                        <strong style="color: #333;" id="sellersCount">{{ $sellers->total() }}</strong> sellers found
                    </p>
                </div>

                <!-- Sellers Grid -->
                <div id="sellersGrid">
                @if($sellers->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-bottom: 30px;">
                        @foreach($sellers as $seller)
                            @php
                                $businessInfo = $seller->roleApplications->first();
                                $roleIcon = $seller->role === 'retailer' ? 'fa-store' :
                                           ($seller->role === 'wholesaler' ? 'fa-warehouse' : 'fa-globe');
                                $roleColor = $seller->role === 'retailer' ? '#4CAF50' :
                                            ($seller->role === 'wholesaler' ? '#2196F3' : '#9C27B0');
                                $vendorName = $businessInfo->business_name ?? $seller->name;
                                $firstLetter = strtoupper(substr($vendorName, 0, 1));
                                $roleDisplay = $seller->role === 'exporter' ? 'Importer' : ucfirst($seller->role);
                            @endphp
                            <div class="seller-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; border: 1px solid #f0f0f0;">
                                
                                <!-- Vendor Header with Gradient -->
                                <div style="position: relative; background: linear-gradient(135deg, {{ $roleColor }}20 0%, {{ $roleColor }}05 100%); padding: 35px 25px 25px; text-align: center;">
                                    <!-- Vendor Avatar with Badge -->
                                    <div style="position: relative; width: 110px; height: 110px; margin: 0 auto 20px;">
                                        <div style="width: 110px; height: 110px; border-radius: 50%; overflow: hidden; border: 5px solid white; box-shadow: 0 8px 24px rgba(0,0,0,0.12); background: {{ $roleColor }};">
                                            @if($seller->profile_image)
                                                <img src="{{ asset('storage/' . $seller->profile_image) }}"
                                                     alt="{{ $vendorName }}"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, {{ $roleColor }} 0%, {{ $roleColor }}dd 100%); color: white; font-size: 48px; font-weight: 700;">
                                                    {{ $firstLetter }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($seller->vendorBadge && $seller->vendorBadge->is_active)
                                            <div style="position: absolute; top: -5px; right: -5px; width: 38px; height: 38px; background: {{ $seller->vendorBadge->bg_color }}; border: 3px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.25); z-index: 10;">
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
                                    @php
                                        $vendorRating = $seller->getVendorRating();
                                    @endphp
                                    @if($vendorRating > 0)
                                        <div style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; box-shadow: 0 4px 12px rgba(255,215,0,0.3);">
                                            <i class="fas fa-star"></i>
                                            <span>{{ number_format($vendorRating, 1) }}</span>
                                        </div>
                                    @else
                                        <div style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; box-shadow: 0 4px 12px rgba(255,215,0,0.3);">
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
                                                {{ $seller->getVendorProductCount() }}
                                            </div>
                                            <div style="font-size: 11px; color: #666; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                Products
                                            </div>
                                        </div>
                                        <div style="text-align: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                                            <div style="font-size: 22px; font-weight: 800; color: {{ $roleColor }}; margin-bottom: 4px;">
                                                {{ $seller->getVendorSalesCount() }}
                                            </div>
                                            <div style="font-size: 11px; color: #666; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">
                                                Sales
                                            </div>
                                        </div>
                                        <div style="text-align: center; padding: 12px; background: #f8f9fa; border-radius: 10px;">
                                            <div style="font-size: 22px; font-weight: 800; color: #FFB800; margin-bottom: 4px;">
                                                @if($vendorRating > 0)
                                                    {{ number_format($vendorRating, 1) }}
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
                                    <a href="{{ route('sellers.products', $seller->id) }}"
                                        class="visit-store-btn"
                                        style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); color: white; text-align: center; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s; box-shadow: 0 4px 15px rgba(255,165,0,0.3); letter-spacing: 0.3px;">
                                        <i class="fas fa-store"></i>
                                        <span>Visit Store</span>
                                        <i class="fas fa-arrow-right" style="font-size: 13px;"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($sellers->hasPages())
                        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            {{ $sellers->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div style="background: white; padding: 60px 20px; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-store-slash" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                        <h3 style="font-size: 24px; color: #333; margin-bottom: 10px;">No Sellers Found</h3>
                        <p style="color: #666; margin-bottom: 20px;">Try adjusting your filters or search criteria</p>
                        <a href="{{ route('sellers.index') }}"
                           style="display: inline-block; padding: 12px 24px; background: #FFA500; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .seller-card {
        transition: all 0.3s ease;
    }
    .seller-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }
    .filter-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.6;
    }
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #FFA500;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 40px auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const sellersContent = document.getElementById('sellersContent');
    const sellersGrid = document.getElementById('sellersGrid');
    const sellersCount = document.getElementById('sellersCount');
    
    // Mobile filter elements
    const mobileFilterToggle = document.getElementById('mobileFilterToggleSellers');
    const sellersSidebar = document.getElementById('sellersSidebar');
    const filterSidebarOverlay = document.getElementById('filterSidebarOverlaySellers');
    const filterCloseBtn = document.getElementById('filterCloseBtnSellers');
    const filterHeader = document.querySelector('.filter-sidebar-header-sellers');
    
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
        if (sellersSidebar && filterSidebarOverlay) {
            sellersSidebar.classList.add('active');
            filterSidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeFilters() {
        if (sellersSidebar && filterSidebarOverlay) {
            sellersSidebar.classList.remove('active');
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
    
    // Get all filter inputs
    const searchInput = document.querySelector('input[name="search"]');
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const locationSelect = document.querySelector('select[name="location"]');
    const ratingRadios = document.querySelectorAll('input[name="min_rating"]');
    const minProductsSelect = document.querySelector('select[name="min_products"]');
    const sortSelect = document.querySelector('select[name="sort"]');
    
    // Debounce function for search input
    let searchTimeout;
    function debounce(func, delay) {
        return function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(func, delay);
        };
    }
    
    // Function to apply filters dynamically
    function applyFilters() {
        // Show loading state
        sellersContent.classList.add('filter-loading');
        sellersGrid.innerHTML = '<div class="spinner"></div>';
        
        // Close mobile filters
        if (window.innerWidth <= 1024) {
            setTimeout(closeFilters, 300);
        }
        
        // Get form data
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        // Make AJAX request
        fetch('{{ route("sellers.index") }}?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update sellers count
            sellersCount.textContent = data.total;
            
            // Update sellers grid
            sellersGrid.innerHTML = data.html;
            
            // Remove loading state
            sellersContent.classList.remove('filter-loading');
            
            // Update URL without page reload
            const newUrl = '{{ route("sellers.index") }}?' + params.toString();
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            sellersContent.classList.remove('filter-loading');
            sellersGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #e74c3c;">Error loading sellers. Please try again.</div>';
        });
    }
    
    // Add event listeners to all filter inputs
    if (searchInput) {
        searchInput.addEventListener('input', debounce(applyFilters, 500));
    }
    
    typeRadios.forEach(radio => {
        radio.addEventListener('change', applyFilters);
    });
    
    if (locationSelect) {
        locationSelect.addEventListener('change', applyFilters);
    }
    
    ratingRadios.forEach(radio => {
        radio.addEventListener('change', applyFilters);
    });
    
    if (minProductsSelect) {
        minProductsSelect.addEventListener('change', applyFilters);
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilters);
    }
    
    // Prevent form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});
</script>
@endpush
@endsection
