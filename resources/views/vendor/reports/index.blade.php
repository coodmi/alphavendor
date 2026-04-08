@extends('layouts.dashboard')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

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
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .report-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
    }
    
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .report-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
        color: white;
    }
    
    .report-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .report-description {
        color: #7f8c8d;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .report-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .report-btn:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .stat-box {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .stat-value {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .stat-label {
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>

<!-- Quick Stats Overview -->
<div class="stats-overview">
    <div class="stat-box">
        <div class="stat-value" style="color: #2ecc71;">${{ number_format($stats['total_sales'], 2) }}</div>
        <div class="stat-label">Total Sales</div>
    </div>
    <div class="stat-box">
        <div class="stat-value" style="color: #3498db;">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-box">
        <div class="stat-value" style="color: #9b59b6;">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-box">
        <div class="stat-value" style="color: #e67e22;">${{ number_format($stats['total_commission'], 2) }}</div>
        <div class="stat-label">Total Earnings</div>
    </div>
</div>

<!-- Reports Grid -->
<div class="reports-grid">
    <!-- Product Sales Report -->
    <div class="report-card" style="--card-color-1: #667eea; --card-color-2: #764ba2;">
        <div class="report-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="report-title">Product Sales Report</h3>
        <p class="report-description">
            View detailed sales data for each product including units sold, revenue generated, and performance metrics.
        </p>
        <a href="{{ route('vendor.reports.product-sales') }}" class="report-btn">
            <i class="fas fa-arrow-right"></i> View Report
        </a>
    </div>
    
    <!-- Product Wishlist Report -->
    <div class="report-card" style="--card-color-1: #f093fb; --card-color-2: #f5576c;">
        <div class="report-icon">
            <i class="fas fa-heart"></i>
        </div>
        <h3 class="report-title">Product Wishlist Report</h3>
        <p class="report-description">
            See which products customers are adding to their wishlists. Identify popular items and potential bestsellers.
        </p>
        <a href="{{ route('vendor.reports.product-wishlist') }}" class="report-btn">
            <i class="fas fa-arrow-right"></i> View Report
        </a>
    </div>
    
    <!-- Product Stock Report -->
    <div class="report-card" style="--card-color-1: #4facfe; --card-color-2: #00f2fe;">
        <div class="report-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <h3 class="report-title">Product Stock Report</h3>
        <p class="report-description">
            Monitor inventory levels, identify low stock items, and manage out-of-stock products efficiently.
        </p>
        <a href="{{ route('vendor.reports.product-stock') }}" class="report-btn">
            <i class="fas fa-arrow-right"></i> View Report
        </a>
    </div>
    
    <!-- Commission History Report -->
    <div class="report-card" style="--card-color-1: #43e97b; --card-color-2: #38f9d7;">
        <div class="report-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <h3 class="report-title">Commission History</h3>
        <p class="report-description">
            Track all your earnings, commissions, and transactions. View detailed breakdown of your revenue streams.
        </p>
        <a href="{{ route('vendor.reports.commission-history') }}" class="report-btn">
            <i class="fas fa-arrow-right"></i> View Report
        </a>
    </div>
</div>

<!-- Additional Info -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; border-radius: 16px; color: white; text-align: center;">
    <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 15px;">Need Help with Reports?</h2>
    <p style="font-size: 16px; opacity: 0.9; margin-bottom: 25px;">
        Our reports help you make data-driven decisions to grow your business. Export data to CSV for further analysis.
    </p>
    <a href="{{ route('vendor.tickets.index') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 30px; background: white; color: #667eea; text-decoration: none; border-radius: 8px; font-weight: 600;">
        <i class="fas fa-life-ring"></i> Contact Support
    </a>
</div>

@endsection
