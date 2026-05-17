@php $u = auth()->user(); @endphp

{{-- MAIN --}}
<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route('employee.dashboard') }}" class="menu-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
    </a>
</div>

{{-- CATALOG MANAGEMENT --}}
@if($u->hasAnyPermission(['products.view','products.add','products.edit','products.delete','products.approve','categories.manage','brands.manage','attributes.manage','reviews.view','reviews.approve','reviews.reply','reviews.delete']))
<div class="menu-section">
    <div class="menu-section-title">Catalog Management</div>
    @if($u->hasAnyPermission(['products.view','products.add','products.edit','products.delete','products.approve']))
    <a href="{{ route('employee.products') }}" class="menu-item {{ request()->routeIs('employee.products*') ? 'active' : '' }}">
        <i class="fas fa-box"></i>
        <span>Products</span>
    </a>
    @endif
    @if($u->hasPermission('categories.manage'))
    <a href="{{ route('admin.categories') }}" class="menu-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <i class="fas fa-tags"></i>
        <span>Categories</span>
    </a>
    @endif
    @if($u->hasPermission('brands.manage'))
    <a href="{{ route('admin.brands') }}" class="menu-item {{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
        <i class="fas fa-copyright"></i>
        <span>Brands</span>
    </a>
    @endif
    @if($u->hasPermission('attributes.manage'))
    <a href="{{ route('admin.attributes') }}" class="menu-item {{ request()->routeIs('admin.attributes*') ? 'active' : '' }}">
        <i class="fas fa-list-ul"></i>
        <span>Attributes</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['reviews.view','reviews.approve','reviews.reply','reviews.delete']))
    <a href="{{ route('admin.reviews') }}" class="menu-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i>
        <span>Reviews & Ratings</span>
    </a>
    @endif
</div>
@endif

{{-- SALES & ORDERS --}}
@if($u->hasAnyPermission(['orders.view','orders.update_status','orders.cancel','orders.approve','returns.view','returns.approve','returns.reject']))
<div class="menu-section">
    <div class="menu-section-title">Sales & Orders</div>
    @if($u->hasAnyPermission(['orders.view','orders.update_status','orders.cancel','orders.approve']))
    <a href="{{ route('employee.orders') }}" class="menu-item {{ request()->routeIs('employee.orders*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['returns.view','returns.approve','returns.reject']))
    <a href="{{ route('vendor.returns.index') }}" class="menu-item {{ request()->routeIs('vendor.returns*') ? 'active' : '' }}">
        <i class="fas fa-undo"></i>
        <span>Returns & Refunds</span>
        @php $pendingReturns = \App\Models\ReturnRequest::where('status','pending')->count(); @endphp
        @if($pendingReturns > 0)
            <span class="badge">{{ $pendingReturns }}</span>
        @endif
    </a>
    @endif
</div>
@endif

{{-- USER MANAGEMENT --}}
@if($u->hasAnyPermission(['users.view','users.edit','users.block','users.add','verification.view','verification.edit','user_permissions.view','user_permissions.edit','role_settings.manage','activity_logs.view','activity_logs.export','activity_logs.clear']))
<div class="menu-section">
    <div class="menu-section-title">User Management</div>
    @if($u->hasAnyPermission(['users.view','users.edit','users.block','users.add']))
    <a href="{{ route('employee.users') }}" class="menu-item {{ request()->routeIs('employee.users*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>All Users</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['verification.view','verification.edit']))
    <a href="{{ route('admin.verification-management') }}" class="menu-item {{ request()->routeIs('admin.verification*') ? 'active' : '' }}">
        <i class="fas fa-id-card"></i>
        <span>Verification</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['user_permissions.view','user_permissions.edit']))
    <a href="{{ route('employee.applications') }}" class="menu-item {{ request()->routeIs('employee.applications*') ? 'active' : '' }}">
        <i class="fas fa-user-plus"></i>
        <span>Role Applications</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['activity_logs.view','activity_logs.export','activity_logs.clear']))
    <a href="{{ route('admin.user-activity') }}" class="menu-item {{ request()->routeIs('admin.user-activity*') ? 'active' : '' }}">
        <i class="fas fa-history"></i>
        <span>Activity Logs</span>
    </a>
    @endif
</div>
@endif

{{-- VENDOR MANAGEMENT --}}
@if($u->hasAnyPermission(['vendors.view','vendors.edit','vendors.block','vendor_badges.manage','vendor_applications.view','vendor_applications.approve','vendor_applications.reject']))
<div class="menu-section">
    <div class="menu-section-title">Vendor Management</div>
    @if($u->hasAnyPermission(['vendors.view','vendors.edit','vendors.block']))
    <a href="{{ route('admin.vendors') }}" class="menu-item {{ request()->routeIs('admin.vendors*') ? 'active' : '' }}">
        <i class="fas fa-store"></i>
        <span>All Vendors</span>
    </a>
    @endif
    @if($u->hasPermission('vendor_badges.manage'))
    <a href="{{ route('admin.vendor-badges') }}" class="menu-item {{ request()->routeIs('admin.vendor-badges*') ? 'active' : '' }}">
        <i class="fas fa-certificate"></i>
        <span>Vendor Badges</span>
    </a>
    @endif
</div>
@endif

{{-- COMMUNICATION --}}
@if($u->hasAnyPermission(['tickets.view','tickets.reply','tickets.close','chat.view','chat.reply']))
<div class="menu-section">
    <div class="menu-section-title">Communication</div>
    @if($u->hasAnyPermission(['tickets.view','tickets.reply','tickets.close']))
    <a href="{{ route('admin.tickets') }}" class="menu-item {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
        <i class="fas fa-ticket-alt"></i>
        <span>Support Tickets</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['chat.view','chat.reply']))
    <a href="{{ route('admin.chat') }}" class="menu-item {{ request()->routeIs('admin.chat*') ? 'active' : '' }}">
        <i class="fas fa-comments"></i>
        <span>Chat</span>
    </a>
    @endif
</div>
@endif

{{-- PAYMENTS --}}
@if($u->hasAnyPermission(['advance_payments.view','advance_payments.approve','advance_payments.reject']))
<div class="menu-section">
    <div class="menu-section-title">Payments</div>
    <a href="{{ route('admin.advance-payments.index') }}" class="menu-item {{ request()->routeIs('admin.advance-payments*') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i>
        <span>Advance Payments</span>
    </a>
</div>
@endif

{{-- MARKETING --}}
@if($u->hasAnyPermission(['coupons.manage','special_offers.manage','banners.manage','promo_banners.manage']))
<div class="menu-section">
    <div class="menu-section-title">Marketing</div>
    @if($u->hasPermission('coupons.manage'))
    <a href="{{ route('admin.coupons') }}" class="menu-item {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
        <i class="fas fa-ticket-alt"></i>
        <span>Coupons</span>
    </a>
    @endif
    @if($u->hasPermission('special_offers.manage'))
    <a href="{{ route('admin.special-offers') }}" class="menu-item {{ request()->routeIs('admin.special-offers*') ? 'active' : '' }}">
        <i class="fas fa-percentage"></i>
        <span>Special Offers</span>
    </a>
    @endif
    @if($u->hasPermission('banners.manage'))
    <a href="{{ route('admin.banners') }}" class="menu-item {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
        <i class="fas fa-image"></i>
        <span>Banners</span>
    </a>
    @endif
</div>
@endif

{{-- FINANCE --}}
@if($u->hasAnyPermission(['transactions.view','payment_gateways.edit','payment_verification.manage','cod.view','cod.manage']))
<div class="menu-section">
    <div class="menu-section-title">Finance</div>
    @if($u->hasPermission('transactions.view'))
    <a href="{{ route('admin.transactions') }}" class="menu-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Transactions</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['payment_verification.manage']))
    <a href="{{ route('admin.manual-payments') }}" class="menu-item {{ request()->routeIs('admin.manual-payments*') ? 'active' : '' }}">
        <i class="fas fa-check-circle"></i>
        <span>Payment Verification</span>
    </a>
    @endif
    @if($u->hasAnyPermission(['cod.view','cod.manage']))
    <a href="{{ route('admin.cod-orders') }}" class="menu-item {{ request()->routeIs('admin.cod-orders*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-usd"></i>
        <span>Cash on Delivery</span>
    </a>
    @endif
</div>
@endif

{{-- DELIVERY & COMMISSION --}}
@if($u->hasAnyPermission(['commission.manage','delivery.manage']))
<div class="menu-section">
    <div class="menu-section-title">Delivery & Commission</div>
    @if($u->hasPermission('commission.manage'))
    <a href="{{ route('admin.commission') }}" class="menu-item {{ request()->routeIs('admin.commission*') ? 'active' : '' }}">
        <i class="fas fa-percentage"></i>
        <span>Commission Settings</span>
    </a>
    @endif
    @if($u->hasPermission('delivery.manage'))
    <a href="{{ route('admin.delivery') }}" class="menu-item {{ request()->routeIs('admin.delivery*') ? 'active' : '' }}">
        <i class="fas fa-truck"></i>
        <span>Delivery Management</span>
    </a>
    @endif
</div>
@endif

{{-- ANALYTICS --}}
@if($u->hasAnyPermission(['analytics.view','analytics.export']))
<div class="menu-section">
    <div class="menu-section-title">Analytics</div>
    <a href="{{ route('admin.analytics') }}" class="menu-item {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>Analytics & Reports</span>
    </a>
</div>
@endif

{{-- ACCOUNT --}}
<div class="menu-section">
    <div class="menu-section-title">Account</div>
    <a href="{{ route('profile.show') }}" class="menu-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i>
        <span>Profile</span>
    </a>
</div>
