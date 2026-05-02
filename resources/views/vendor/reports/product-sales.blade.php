@extends('layouts.dashboard')

@section('title', 'Product Sales Report')
@section('page-title', 'Product Sales Report')

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
    
    .filters-bar {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .filter-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-input {
        padding: 10px 15px;
        border: 2px solid #e8e8e8;
        border-radius: 8px;
        font-size: 14px;
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
            <i class="fas fa-chart-line"></i> Product Sales Report
        </h2>
        <p style="margin: 0; color: #7f8c8d;">Track your product performance and sales metrics</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('vendor.reports.index') }}" class="btn-primary" style="background: #6c757d;">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <a href="{{ route('vendor.reports.export', 'sales') }}" class="btn-export">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form method="GET" action="{{ route('vendor.reports.product-sales') }}">
        <div class="filter-row">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #2c3e50;">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #2c3e50;">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; font-size: 13px; color: #2c3e50;">Sort By</label>
                <select name="sort_by" class="filter-input">
                    <option value="total_sold" {{ request('sort_by') == 'total_sold' ? 'selected' : '' }}>Units Sold</option>
                    <option value="total_revenue" {{ request('sort_by') == 'total_revenue' ? 'selected' : '' }}>Revenue</option>
                </select>
            </div>
            <div style="align-self: flex-end;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="report-table">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8f9fa;">
                <tr>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Product</th>
                    <th style="padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">SKU</th>
                    <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Units Sold</th>
                    <th style="padding: 15px 20px; text-align: right; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Total Revenue</th>
                    <th style="padding: 15px 20px; text-align: center; font-size: 12px; font-weight: 700; color: #7f8c8d; text-transform: uppercase;">Current Stock</th>
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
                                    <div style="font-size: 12px; color: #7f8c8d;"> {{ currency($product->retail_price ?? 0) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px 20px;">
                            <span style="font-family: monospace; color: #7f8c8d;">{{ $product->sku ?? 'N/A' }}</span>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            <span style="font-size: 20px; font-weight: 700; color: #667eea;">{{ $product->total_sold ?? 0 }}</span>
                        </td>
                        <td style="padding: 15px 20px; text-align: right;">
                            <span style="font-size: 18px; font-weight: 700; color: #2ecc71;"> {{ currency($product->total_revenue ?? 0) }}</span>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            <span style="font-weight: 600; color: {{ $product->stock > 10 ? '#2ecc71' : ($product->stock > 0 ? '#f39c12' : '#e74c3c') }};">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td style="padding: 15px 20px; text-align: center;">
                            @if($product->stock == 0)
                                <span style="padding: 6px 12px; background: #fee; color: #c33; border-radius: 12px; font-size: 11px; font-weight: 700;">OUT OF STOCK</span>
                            @elseif($product->stock <= 10)
                                <span style="padding: 6px 12px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 11px; font-weight: 700;">LOW STOCK</span>
                            @else
                                <span style="padding: 6px 12px; background: #d4edda; color: #155724; border-radius: 12px; font-size: 11px; font-weight: 700;">IN STOCK</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 60px 20px; text-align: center;">
                            <i class="fas fa-chart-line" style="font-size: 64px; color: #ddd; margin-bottom: 15px;"></i>
                            <p style="color: #7f8c8d; font-size: 18px; margin: 0;">No sales data found</p>
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
