@extends('layouts.dashboard')

@section('title', 'Product Wishlist Report')
@section('page-title', 'Product Wishlist Report')

@section('sidebar-menu')
    @php
        $userRole = auth()->user()->role;
    @endphp
    @if($userRole === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter')
        @include('dashboards.partials.exporter-sidebar')
    @endif
@endsection

@section('content')
<style>
    .report-header {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .btn-primary {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-export {
        padding: 10px 20px;
        background: #2ecc71;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .report-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
</style>

<!-- Header -->
<div class="report-header">
    <div>
        <h2 style="margin: 0 0 5px 0; font-size: 24px; font-weight: 700; color: #2c3e50;">
            <i class="fas fa-heart"></i> Product Wishlist Report
        </h2>
        <p style="margin: 0; color: #7f8c8d;">See which products customers are saving for later</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('vendor.reports.index') }}" class="btn-primary" style="background: #6c757d;">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <a href="{{ route('vendor.reports.export', 'wishlist') }}" class="btn-export">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>
</div>

<!-- Table -->
<div class="report-table">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Product</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">SKU</th>
                    <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Wishlist Count</th>
                    <th style="padding: 15px 20px; text-align: right; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Price</th>
                    <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Stock</th>
                    <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #f0f0f0;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                        <td style="padding: 15px 20px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                @else
                                    <div style="width: 50px; height: 50px; border-radius: 8px; background: #e8e8e8; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-box" style="color: #999;"></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600; color: #2c3e50;">{{ Str::limit($product->name, 40) }}</div>
                                    <div style="font-size: 12px; color: #7f8c8d;">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px 20px;">
                            <span style="font-family: monospace; color: #7f8c8d;">{{ $product->sku ?? 'N/A' }}</span>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            <span style="font-size: 24px; font-weight: 700; color: #e74c3c;">
                                <i class="fas fa-heart"></i> {{ $product->wishlists_count }}
                            </span>
                        </td>
                        <td style="padding: 15px 20px; text-align: right;">
                            <span style="font-size: 18px; font-weight: 700; color: #2c3e50;">${{ number_format($product->price, 2) }}</span>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            <span style="font-weight: 600; color: {{ $product->stock > 10 ? '#2ecc71' : ($product->stock > 0 ? '#f39c12' : '#e74c3c') }};">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            @if($product->status === 'active')
                                <span style="padding: 6px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 11px; font-weight: 700;">ACTIVE</span>
                            @else
                                <span style="padding: 6px 12px; background: #f8d7da; color: #721c24; border-radius: 12px; font-size: 11px; font-weight: 700;">INACTIVE</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-heart" style="font-size: 64px; color: #ddd; margin-bottom: 15px;"></i>
                            <p style="color: #7f8c8d; font-size: 18px; margin: 0;">No products in wishlists yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
        <div style="padding: 20px; border-top: 2px solid #f0f0f0;">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
