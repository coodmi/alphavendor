<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route('importer.dashboard') }}" class="menu-item {{ request()->routeIs('importer.dashboard') ? 'active' : '' }}">
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
</div>

<div class="menu-section">
    <div class="menu-section-title">Management</div>
    <a href="{{ route('importer.products') }}" class="menu-item {{ request()->routeIs('importer.products') ? 'active' : '' }}">
        <i class="fas fa-box"></i>
        <span>Products</span>
    </a>
    <a href="{{ route('importer.categories') }}" class="menu-item {{ request()->routeIs('importer.categories') ? 'active' : '' }}">
        <i class="fas fa-tags"></i>
        <span>Categories</span>
    </a>
    <a href="{{ route('importer.brands') }}" class="menu-item {{ request()->routeIs('importer.brands') ? 'active' : '' }}">
        <i class="fas fa-copyright"></i>
        <span>Brands</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Orders</div>
    <a href="{{ route('vendor.orders') }}" class="menu-item {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Import Orders</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Earnings</div>
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
    <div class="menu-section-title">Support</div>
    <a href="{{ route('vendor.tickets.index') }}" class="menu-item {{ request()->routeIs('vendor.tickets.index') ? 'active' : '' }}">
        <i class="fas fa-life-ring"></i>
        <span>Support Tickets</span>
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
