@extends('layouts.app')

@section('title', 'Sellers Directory - AlphaVendor')

@section('content')
<div class="sellers-page" style="background: #f8f9fa; padding: 40px 0; min-height: 80vh;">
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <!-- Page Header -->
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 10px;">Sellers Directory</h1>
            <p style="color: #666; font-size: 16px;">Find trusted retailers, wholesalers, and exporters</p>
        </div>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px;">
            <!-- Left Sidebar - Filters -->
            <div class="filters-sidebar" style="height: fit-content;">
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
                                    <span style="font-size: 14px; color: #555;">Exporters</span>
                                </label>
                            </div>
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
            <div>
                <!-- Results Count -->
                <div style="background: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <p style="color: #666; font-size: 14px; margin: 0;">
                        <strong style="color: #333;">{{ $sellers->total() }}</strong> sellers found
                    </p>
                </div>

                <!-- Sellers Grid -->
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
                            @endphp
                            <div class="seller-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s;">
                                <!-- Vendor Photo/Avatar -->
                                <div style="position: relative; background: linear-gradient(135deg, {{ $roleColor }}15 0%, {{ $roleColor }}05 100%); padding: 30px 20px; text-align: center;">
                                    <div style="width: 120px; height: 120px; margin: 0 auto 15px; border-radius: 50%; overflow: hidden; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15); position: relative; background: {{ $roleColor }};">
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

                                    <!-- Seller Type Badge -->
                                    <span style="background: {{ $roleColor }}; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block;">
                                        <i class="fas {{ $roleIcon }}" style="margin-right: 5px;"></i>
                                        {{ ucfirst($seller->role) }}
                                    </span>

                                    @if($seller->role === 'exporter' && $seller->exporter_rating)
                                        <div style="margin-top: 10px;">
                                            <span style="background: #FFD700; color: #333; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-star"></i>
                                                {{ number_format($seller->exporter_rating, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Seller Info -->
                                <div style="padding: 20px;">
                                    <h3 style="font-size: 18px; font-weight: 700; color: #333; margin-bottom: 8px; text-align: center;">
                                        {{ $vendorName }}
                                    </h3>

                                    <!-- Stats Row -->
                                    <div style="display: flex; justify-content: space-around; padding: 12px 0; margin-bottom: 15px; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0;">
                                        <div style="text-align: center;">
                                            <div style="font-size: 20px; font-weight: 700; color: {{ $roleColor }};">{{ $seller->products_count ?? 0 }}</div>
                                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-top: 2px;">Products</div>
                                        </div>
                                        <div style="width: 1px; background: #e0e0e0;"></div>
                                        <div style="text-align: center;">
                                            <div style="font-size: 20px; font-weight: 700; color: {{ $roleColor }};">{{ $seller->total_sales ?? 0 }}</div>
                                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-top: 2px;">Sales</div>
                                        </div>
                                        <div style="width: 1px; background: #e0e0e0;"></div>
                                        <div style="text-align: center;">
                                            <div style="font-size: 20px; font-weight: 700; color: #FFB800;">
                                                @if($seller->role === 'exporter' && $seller->exporter_rating)
                                                    {{ number_format($seller->exporter_rating, 1) }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-top: 2px;">
                                                <i class="fas fa-star" style="color: #FFB800; font-size: 10px;"></i> Rating
                                            </div>
                                        </div>
                                    </div>

                                    <!-- View Products Button -->
                                    <a href="{{ route('sellers.products', $seller->id) }}"
                                       style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%); color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(255,165,0,0.3);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(255,165,0,0.4)'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(255,165,0,0.3)'">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>View Products</span>
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
</style>
@endpush
@endsection
