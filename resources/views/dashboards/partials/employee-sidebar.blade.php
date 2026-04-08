<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route('employee.dashboard') }}" class="menu-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Content Management</div>
    <a href="{{ route('employee.products') }}" class="menu-item {{ request()->routeIs('employee.products*') ? 'active' : '' }}">
        <i class="fas fa-box"></i>
        <span>Products</span>
    </a>
    <a href="{{ route('admin.categories') }}" class="menu-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i>
        <span>Categories</span>
    </a>
    <a href="{{ route('admin.brands') }}" class="menu-item {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
        <i class="fas fa-copyright"></i>
        <span>Brands</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Order Management</div>
    <a href="{{ route('employee.orders') }}" class="menu-item {{ request()->routeIs('employee.orders*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
    </a>
    <a href="{{ route('vendor.returns.index') }}" class="menu-item {{ request()->routeIs('vendor.returns*') ? 'active' : '' }}">
        <i class="fas fa-undo"></i>
        <span>Returns & Refunds</span>
        @php
            $pendingReturns = \App\Models\ReturnRequest::where('status', 'pending')->count();
        @endphp
        @if($pendingReturns > 0)
            <span class="badge">{{ $pendingReturns }}</span>
        @endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">User Management</div>
    <a href="{{ route('employee.users') }}" class="menu-item {{ request()->routeIs('employee.users*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Users</span>
    </a>
    <a href="{{ route('employee.applications') }}" class="menu-item {{ request()->routeIs('employee.applications*') ? 'active' : '' }}">
        <i class="fas fa-user-plus"></i>
        <span>Role Applications</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Account</div>
    <a href="{{ route('profile.show') }}" class="menu-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profile</span>
    </a>
</div>
