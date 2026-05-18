@php
    $prefix = \App\Support\VendorPortal::routePrefix();
    $reportsRoute = \App\Support\VendorPortal::reportsRoute();
@endphp

<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route($prefix . '.dashboard') }}" class="menu-item {{ request()->routeIs($prefix . '.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
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
    <a href="{{ route($reportsRoute) }}" class="menu-item {{ request()->routeIs($prefix . '.reports*') || request()->routeIs('vendor.reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        <span>Report Analysis</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Management</div>
    <a href="{{ route($prefix . '.products') }}" class="menu-item {{ request()->routeIs($prefix . '.products*') ? 'active' : '' }}">
        <i class="fas fa-box"></i>
        <span>Products</span>
    </a>
    <a href="{{ route($prefix . '.categories') }}" class="menu-item {{ request()->routeIs($prefix . '.categories*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i>
        <span>Categories</span>
    </a>
    <a href="{{ route($prefix . '.brands') }}" class="menu-item {{ request()->routeIs($prefix . '.brands*') ? 'active' : '' }}">
        <i class="fas fa-copyright"></i>
        <span>Brands</span>
    </a>
    <a href="{{ route($prefix . '.certifications') }}" class="menu-item {{ request()->routeIs($prefix . '.certifications*') ? 'active' : '' }}">
        <i class="fas fa-certificate"></i>
        <span>Certifications</span>
    </a>
    <a href="{{ route('vendor.reviews.index') }}" class="menu-item {{ request()->routeIs('vendor.reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i>
        <span>Product Reviews</span>
        @php
            $unrepliedReviews = \App\Models\Review::whereHas('product', function ($q) {
                $q->where('vendor_id', auth()->id());
            })->whereNull('vendor_reply')->where('status', 'approved')->count();
        @endphp
        @if($unrepliedReviews > 0)
            <span class="badge">{{ $unrepliedReviews }}</span>
        @endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Orders</div>
    <a href="{{ route('vendor.orders') }}" class="menu-item {{ request()->routeIs('vendor.orders*') || request()->routeIs($prefix . '.orders*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
    </a>
    <a href="{{ route('invoices.my') }}" class="menu-item {{ request()->routeIs('invoices.my') ? 'active' : '' }}">
        <i class="fas fa-file-invoice"></i>
        <span>Invoices</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Earnings</div>
    <a href="{{ route('wallet.index') }}" class="menu-item {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
        <i class="fas fa-wallet"></i>
        <span>Wallet</span>
    </a>
    <a href="{{ route('withdrawals.index') }}" class="menu-item {{ request()->routeIs('withdrawals.index') || request()->routeIs('withdrawals.create') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i>
        <span>Withdrawals</span>
    </a>
    <a href="{{ route('withdrawals.payment-methods') }}" class="menu-item {{ request()->routeIs('withdrawals.payment-methods*') ? 'active' : '' }}">
        <i class="fas fa-mobile-alt"></i>
        <span>Payment Methods</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Support</div>
    <a href="{{ route('vendor.tickets.index') }}" class="menu-item {{ request()->routeIs('vendor.tickets*') ? 'active' : '' }}">
        <i class="fas fa-life-ring"></i>
        <span>Support Tickets</span>
    </a>
    <a href="{{ route('notifications.page') }}" class="menu-item {{ request()->routeIs('notifications.page') ? 'active' : '' }}">
        <i class="fas fa-bell"></i>
        <span>Notifications</span>
    </a>
    <a href="{{ route('seller.reminders.index') }}" class="menu-item {{ request()->routeIs('seller.reminders*') ? 'active' : '' }}">
        <i class="fas fa-clock"></i>
        <span>Reminders</span>
        @php
            $reminderCount = \App\Models\AdminReminder::unreadForUser(auth()->id())->count();
        @endphp
        @if($reminderCount > 0)
            <span class="badge">{{ $reminderCount }}</span>
        @endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Account</div>
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
