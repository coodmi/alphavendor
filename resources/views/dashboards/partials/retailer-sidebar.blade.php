<div class="menu-section">
    <div class="menu-section-title">MAIN</div>
    <a href="{{ route('retailer.dashboard') }}" class="menu-item {{ request()->routeIs('retailer.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('retailer.orders') }}" class="menu-item {{ request()->routeIs('retailer.orders') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
        @php
            $pendingOrders = \App\Models\Order::where('vendor_id', auth()->id())->where('status', 'pending')->count();
        @endphp
        @if($pendingOrders > 0)
            <span class="badge">{{ $pendingOrders }}</span>
        @endif
    </a>
    <a href="{{ route('invoices.my') }}" class="menu-item {{ request()->routeIs('invoices.my') ? 'active' : '' }}">
        <i class="fas fa-file-invoice"></i>
        <span>Invoices</span>
    </a>
    <a href="{{ route('vendor.returns.index') }}" class="menu-item {{ request()->routeIs('vendor.returns*') ? 'active' : '' }}">
        <i class="fas fa-undo"></i>
        <span>Returns & Refunds</span>
        @php
            $pendingReturns = \App\Models\ReturnRequest::where('vendor_id', auth()->id())->where('status', 'pending')->count();
        @endphp
        @if($pendingReturns > 0)
            <span class="badge">{{ $pendingReturns }}</span>
        @endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">PRODUCTS</div>
    <a href="{{ route('retailer.products') }}" class="menu-item {{ request()->routeIs('retailer.products') ? 'active' : '' }}">
        <i class="fas fa-box"></i>
        <span>All Products</span>
    </a>
    <a href="{{ route('retailer.products') }}" class="menu-item">
        <i class="fas fa-plus-circle"></i>
        <span>Add Product</span>
    </a>
    <a href="{{ route('retailer.categories') }}" class="menu-item {{ request()->routeIs('retailer.categories') ? 'active' : '' }}">
        <i class="fas fa-tags"></i>
        <span>Categories</span>
    </a>
    <a href="{{ route('retailer.brands') }}" class="menu-item {{ request()->routeIs('retailer.brands') ? 'active' : '' }}">
        <i class="fas fa-copyright"></i>
        <span>Brands</span>
    </a>
    <a href="{{ route('vendor.reviews.index') }}" class="menu-item {{ request()->routeIs('vendor.reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i>
        <span>Product Reviews</span>
        @php
            $unrepliedReviews = \App\Models\Review::whereHas('product', function($q) {
                $q->where('vendor_id', auth()->id());
            })->whereNull('vendor_reply')->where('status', 'approved')->count();
        @endphp
        @if($unrepliedReviews > 0)
            <span class="badge">{{ $unrepliedReviews }}</span>
        @endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">EARNINGS</div>
    <a href="{{ route('wallet.index') }}" class="menu-item {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
        <i class="fas fa-wallet"></i>
        <span>Wallet</span>
    </a>
    <a href="{{ route('withdrawals.index') }}" class="menu-item {{ request()->routeIs('withdrawals.index') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i>
        <span>Withdrawals</span>
    </a>
    <a href="{{ route('vendor.reports.index') }}" class="menu-item {{ request()->routeIs('vendor.reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>Reports</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">SUPPORT</div>
    <a href="{{ route('vendor.tickets.index') }}" class="menu-item {{ request()->routeIs('vendor.tickets.index') ? 'active' : '' }}">
        <i class="fas fa-life-ring"></i>
        <span>Support Tickets</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">ACCOUNT</div>
    <a href="{{ route('verification.index') }}" class="menu-item {{ request()->routeIs('verification*') ? 'active' : '' }}">
        <i class="fas fa-user-check"></i>
        <span>Verification</span>
        @if(auth()->user()->verification_status === 'unverified')
            <span class="badge" style="background: #1a6b73;">!</span>
        @elseif(auth()->user()->verification_status === 'pending')
            <span class="badge" style="background: #3b82f6;">⏳</span>
        @elseif(auth()->user()->verification_status === 'verified')
            <span class="badge" style="background: #10b981;">✓</span>
        @endif
    </a>
    <a href="{{ route('profile.show') }}" class="menu-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profile</span>
    </a>
</div>
