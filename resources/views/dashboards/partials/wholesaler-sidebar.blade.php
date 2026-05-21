<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route('wholesaler.dashboard') }}" class="menu-item {{ request()->routeIs('wholesaler.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('wholesaler.orders') }}" class="menu-item {{ request()->routeIs('wholesaler.orders*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
        @php $pendingOrders = \App\Models\Order::where('vendor_id', auth()->id())->where('status', 'pending')->count(); @endphp
        @if($pendingOrders > 0)<span class="badge">{{ $pendingOrders }}</span>@endif
    </a>
    <a href="{{ route('vendor.returns.index') }}" class="menu-item {{ request()->routeIs('vendor.returns*') ? 'active' : '' }}">
        <i class="fas fa-undo"></i>
        <span>Returns & Refunds</span>
        @php $pendingReturns = \App\Models\ReturnRequest::where('vendor_id', auth()->id())->where('status', 'pending')->count(); @endphp
        @if($pendingReturns > 0)<span class="badge">{{ $pendingReturns }}</span>@endif
    </a>
    <a href="{{ route('advance-payments.user') }}" class="menu-item {{ request()->routeIs('advance-payments.user') ? 'active' : '' }}">
        <i class="fas fa-money-check-alt"></i>
        <span>Advance Payments</span>
        @php $pendingAP = \App\Models\AdvancePayment::where('user_id', auth()->id())->where('status','pending')->count(); @endphp
        @if($pendingAP > 0)<span class="badge">{{ $pendingAP }}</span>@endif
    </a>
    <a href="{{ route('wholesaler.reports.index') }}" class="menu-item {{ request()->routeIs('wholesaler.reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>Report Analysis</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Catalog</div>
    <a href="{{ route('wholesaler.products') }}" class="menu-item {{ request()->routeIs('wholesaler.products') ? 'active' : '' }}">
        <i class="fas fa-box"></i><span>Products</span>
    </a>
    <a href="{{ route('wholesaler.categories') }}" class="menu-item {{ request()->routeIs('wholesaler.categories') ? 'active' : '' }}">
        <i class="fas fa-tags"></i><span>Categories</span>
    </a>
    <a href="{{ route('wholesaler.brands') }}" class="menu-item {{ request()->routeIs('wholesaler.brands') ? 'active' : '' }}">
        <i class="fas fa-copyright"></i><span>Brands</span>
    </a>
    <a href="{{ route('wholesaler.supplier-locations.index') }}" class="menu-item {{ request()->routeIs('wholesaler.supplier-locations*') ? 'active' : '' }}">
        <i class="fas fa-map-marker-alt"></i><span>Supplier Locations</span>
    </a>
    <a href="{{ route('wholesaler.attributes.index') }}" class="menu-item {{ request()->routeIs('wholesaler.attributes*') ? 'active' : '' }}">
        <i class="fas fa-sliders-h"></i><span>Attributes</span>
    </a>
    <a href="{{ route('vendor.reviews.index') }}" class="menu-item {{ request()->routeIs('vendor.reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i><span>Product Reviews</span>
        @php $unrepliedReviews = \App\Models\Review::whereHas('product', fn($q) => $q->where('vendor_id', auth()->id()))->whereNull('vendor_reply')->where('status', 'approved')->count(); @endphp
        @if($unrepliedReviews > 0)<span class="badge">{{ $unrepliedReviews }}</span>@endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Finance</div>
    <a href="{{ route('wallet.index') }}" class="menu-item {{ request()->routeIs('wallet.index') ? 'active' : '' }}">
        <i class="fas fa-wallet"></i><span>Wallet</span>
    </a>
    <a href="{{ route('withdrawals.index') }}" class="menu-item {{ request()->routeIs('withdrawals*') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i><span>Withdrawals</span>
    </a>
    <a href="{{ route('withdrawals.payment-methods') }}" class="menu-item {{ request()->routeIs('withdrawals.payment-methods*') ? 'active' : '' }}">
        <i class="fas fa-credit-card"></i><span>Payment Methods</span>
    </a>
    <a href="{{ route('invoices.my') }}" class="menu-item {{ request()->routeIs('invoices.my') ? 'active' : '' }}">
        <i class="fas fa-file-invoice"></i><span>Invoices</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Support</div>
    <a href="{{ route('vendor.tickets.index') }}" class="menu-item {{ request()->routeIs('vendor.tickets*') ? 'active' : '' }}">
        <i class="fas fa-life-ring"></i><span>Support Tickets</span>
    </a>
    <a href="{{ route('notifications.page') }}" class="menu-item {{ request()->routeIs('notifications*') ? 'active' : '' }}">
        <i class="fas fa-bell"></i><span>Notifications</span>
    </a>
    <a href="{{ route('seller.reminders.index') }}" class="menu-item {{ request()->routeIs('seller.reminders*') ? 'active' : '' }}">
        <i class="fas fa-envelope"></i><span>Reminders</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Account</div>
    <a href="{{ route('verification.index') }}" class="menu-item {{ request()->routeIs('verification*') ? 'active' : '' }}">
        <i class="fas fa-user-check"></i><span>Verification</span>
    </a>
    <a href="{{ route('profile.show') }}" class="menu-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i><span>Profile</span>
    </a>
</div>
