<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route('user.dashboard') }}" class="menu-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Shopping</div>
    <a href="{{ route('shop') }}" class="menu-item {{ request()->routeIs('shop') ? 'active' : '' }}">
        <i class="fas fa-shopping-bag"></i>
        <span>Browse Products</span>
    </a>
    <a href="{{ route('orders.my-orders') }}" class="menu-item {{ request()->routeIs('orders.my-orders') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>My Orders</span>
    </a>
    <a href="{{ route('invoices.my') }}" class="menu-item {{ request()->routeIs('invoices.my') ? 'active' : '' }}">
        <i class="fas fa-file-invoice"></i>
        <span>My Invoices</span>
    </a>
    <a href="{{ route('wishlist.index') }}" class="menu-item {{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
        <i class="fas fa-heart"></i>
        <span>Wishlist</span>
    </a>
    <a href="{{ route('customer.returns.index') }}" class="menu-item {{ request()->routeIs('customer.returns.*') ? 'active' : '' }}">
        <i class="fas fa-undo"></i>
        <span>Returns & Refunds</span>
    </a>
    <a href="{{ route('cart.index') }}" class="menu-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Shopping Cart</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Account</div>
    <a href="{{ route('profile.show') }}" class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>My Profile</span>
    </a>
    <a href="{{ route('vendor.tickets.index') }}" class="menu-item {{ request()->routeIs('vendor.tickets.*') ? 'active' : '' }}">
        <i class="fas fa-ticket-alt"></i>
        <span>Support Tickets</span>
    </a>
</div>
