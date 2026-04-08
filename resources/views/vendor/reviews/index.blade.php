@extends('layouts.dashboard')

@section('title', 'Product Reviews')
@section('page-title', 'Product Reviews')

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
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .filters-bar {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filter-btn {
        padding: 8px 16px;
        border: 2px solid #e8e8e8;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        font-size: 14px;
    }
    
    .filter-btn:hover {
        border-color: #667eea;
        color: #667eea;
    }
    
    .filter-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    .search-box {
        flex: 1;
        min-width: 250px;
    }
    
    .search-box input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e8e8e8;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .review-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 15px;
    }
    
    .review-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
    }
    
    .stars {
        color: #f39c12;
        font-size: 18px;
    }
    
    .review-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-approved {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-verified {
        background: #cce5ff;
        color: #004085;
    }
    
    .review-product {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        font-size: 13px;
        margin-bottom: 15px;
    }
    
    .review-content {
        margin-bottom: 15px;
    }
    
    .review-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #2c3e50;
    }
    
    .review-text {
        color: #555;
        line-height: 1.6;
    }
    
    .vendor-reply-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }
    
    .reply-form textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e8e8e8;
        border-radius: 8px;
        resize: vertical;
        min-height: 100px;
        font-size: 14px;
    }
    
    .reply-display {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }
    
    .reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .btn-primary {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
    }
    
    .btn-secondary {
        padding: 8px 16px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
    }
    
    .btn-danger {
        padding: 8px 16px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
    }
</style>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value" style="color: #667eea;">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Reviews</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: #f39c12;">{{ number_format($stats['average_rating'], 1) }} ★</div>
        <div class="stat-label">Average Rating</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: #2ecc71;">{{ $stats['replied'] }}</div>
        <div class="stat-label">Replied</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: #e74c3c;">{{ $stats['unreplied'] }}</div>
        <div class="stat-label">Unreplied</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: #3498db;">{{ $stats['approved'] }}</div>
        <div class="stat-label">Approved</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" style="color: #f39c12;">{{ $stats['pending'] }}</div>
        <div class="stat-label">Pending</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-bar">
    <a href="{{ route('vendor.reviews.index', ['filter' => 'all']) }}" 
       class="filter-btn {{ $filter == 'all' ? 'active' : '' }}">
        All Reviews
    </a>
    <a href="{{ route('vendor.reviews.index', ['filter' => 'unreplied']) }}" 
       class="filter-btn {{ $filter == 'unreplied' ? 'active' : '' }}">
        Unreplied
    </a>
    <a href="{{ route('vendor.reviews.index', ['filter' => 'replied']) }}" 
       class="filter-btn {{ $filter == 'replied' ? 'active' : '' }}">
        Replied
    </a>
    <a href="{{ route('vendor.reviews.index', ['filter' => 'approved']) }}" 
       class="filter-btn {{ $filter == 'approved' ? 'active' : '' }}">
        Approved
    </a>
    <a href="{{ route('vendor.reviews.index', ['filter' => 'pending']) }}" 
       class="filter-btn {{ $filter == 'pending' ? 'active' : '' }}">
        Pending
    </a>
    
    <div class="search-box">
        <form action="{{ route('vendor.reviews.index') }}" method="GET">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="search" placeholder="Search reviews..." value="{{ $search }}">
        </form>
    </div>
</div>

<!-- Reviews List -->
@if($reviews->count() > 0)
    @foreach($reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <div class="review-user">
                    <div class="user-avatar">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: #2c3e50;">{{ $review->user->name }}</div>
                        <div style="font-size: 13px; color: #7f8c8d;">{{ $review->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="review-badge badge-{{ $review->status }}">{{ $review->status }}</span>
                        @if($review->is_verified_purchase)
                            <span class="review-badge badge-verified">Verified Purchase</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="review-product">
                <i class="fas fa-box"></i>
                <strong>{{ $review->product->name }}</strong>
            </div>
            
            <div class="review-content">
                @if($review->title)
                    <div class="review-title">{{ $review->title }}</div>
                @endif
                <div class="review-text">{{ $review->comment }}</div>
            </div>
            
            <!-- Vendor Reply Section -->
            <div class="vendor-reply-section">
                @if($review->vendor_reply)
                    <div class="reply-display">
                        <div class="reply-header">
                            <strong style="color: #667eea;">
                                <i class="fas fa-store"></i> Your Reply
                            </strong>
                            <span style="font-size: 12px; color: #7f8c8d;">
                                Replied on {{ $review->vendor_replied_at->format('M d, Y \a\t h:i A') }}
                            </span>
                        </div>
                        <div>{{ $review->vendor_reply }}</div>
                    </div>
                @else
                    @if($review->status === 'approved')
                        <form action="{{ route('vendor.reviews.reply', $review) }}" method="POST" class="reply-form">
                            @csrf
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">
                                <i class="fas fa-reply"></i> Reply to this review
                            </label>
                            <textarea name="vendor_reply" required placeholder="Write your reply here..."></textarea>
                            <div style="margin-top: 10px;">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-paper-plane"></i> Post Reply
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="padding: 15px; background: #fff3cd; border-radius: 8px; color: #856404;">
                            <i class="fas fa-info-circle"></i> This review is pending approval. You can reply once it's approved.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
    
    <!-- Pagination -->
    <div style="margin-top: 30px;">
        {{ $reviews->links() }}
    </div>
@else
    <div style="background: white; padding: 60px; text-align: center; border-radius: 10px;">
        <i class="fas fa-star" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
        <h3 style="color: #7f8c8d; margin: 0;">No reviews found</h3>
        <p style="color: #95a5a6; margin-top: 10px;">
            @if($filter != 'all')
                Try changing the filter or search criteria.
            @else
                Your products haven't received any reviews yet.
            @endif
        </p>
    </div>
@endif

@endsection
