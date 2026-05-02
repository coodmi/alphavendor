{{-- Complete Admin Sidebar Menu --}}
<div class="menu-section">
    <div class="menu-section-title">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.analytics') }}" class="menu-item{{ request()->routeIs('admin.analytics') ? ' active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        <span>Analytics & Reports</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Catalog Management</div>
    <a href="{{ route('admin.products') }}" class="menu-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
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
    <a href="{{ route('admin.attributes.index') }}" class="menu-item {{ request()->routeIs('admin.attributes*') ? 'active' : '' }}">
        <i class="fas fa-sliders-h"></i>
        <span>Attributes</span>
    </a>
    <a href="{{ route('admin.reviews.index') }}" class="menu-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i>
        <span>Reviews & Ratings</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Sales & Orders</div>
    <a href="{{ route('admin.orders') }}" class="menu-item {{ request()->routeIs('admin.orders*') && !request()->routeIs('admin.returns*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
    </a>
    <a href="{{ route('admin.returns.index') }}" class="menu-item {{ request()->routeIs('admin.returns*') ? 'active' : '' }}">
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
    <div class="menu-section-title">User & Role Management</div>
    <a href="{{ route('admin.users') }}" class="menu-item{{ request()->routeIs('admin.users*') ? ' active' : '' }}">
        <i class="fas fa-users-cog"></i>
        <span>All Users</span>
    </a>
    <a href="{{ route('admin.verification.index') }}" class="menu-item{{ request()->routeIs('admin.verification*') ? ' active' : '' }}">
        <i class="fas fa-user-check"></i>
        <span>Verification</span>
        @php
            $pendingVerifications = \App\Models\User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
                ->where('verification_status', 'pending')->count();
        @endphp
        @if($pendingVerifications > 0)
            <span class="badge">{{ $pendingVerifications }}</span>
        @endif
    </a>
    <a href="{{ route('admin.user-permissions') }}" class="menu-item{{ request()->routeIs('admin.user-permissions*') ? ' active' : '' }}">
        <i class="fas fa-user-shield"></i>
        <span>User Permissions</span>
    </a>
    <a href="{{ route('admin.role-settings') }}" class="menu-item{{ request()->routeIs('admin.role-settings*') ? ' active' : '' }}">
        <i class="fas fa-user-tag"></i>
        <span>Role Settings</span>
    </a>
    <a href="{{ route('admin.user-activity') }}" class="menu-item{{ request()->routeIs('admin.user-activity*') ? ' active' : '' }}">
        <i class="fas fa-user-clock"></i>
        <span>User Activity Logs</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Employee Management</div>
    <a href="{{ route('admin.employees') }}" class="menu-item {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
        <i class="fas fa-user-tie"></i>
        <span>All Employees</span>
    </a>
    <a href="{{ route('admin.employee-permissions') }}" class="menu-item {{ request()->routeIs('admin.employee-permissions*') ? 'active' : '' }}">
        <i class="fas fa-user-shield"></i>
        <span>Employee Permissions</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Vendor Management</div>
    <a href="{{ route('admin.vendors') }}" class="menu-item {{ request()->routeIs('admin.vendors*') && !request()->routeIs('admin.vendor-badges*') ? 'active' : '' }}">
        <i class="fas fa-store"></i>
        <span>All Vendors</span>
    </a>
    <a href="{{ route('admin.vendor-badges.index') }}" class="menu-item {{ request()->routeIs('admin.vendor-badges*') ? 'active' : '' }}">
        <i class="fas fa-award"></i>
        <span>Vendor Badges</span>
    </a>
    <a href="{{ route('admin.vendor-applications') }}" class="menu-item {{ request()->routeIs('admin.vendor-applications*') ? 'active' : '' }}">
        <i class="fas fa-user-plus"></i>
        <span>Vendor Applications</span>
        @php
            $pendingApplications = \App\Models\RoleApplication::pending()->count();
        @endphp
        @if($pendingApplications > 0)
            <span class="badge">{{ $pendingApplications }}</span>
        @endif
    </a>
    <a href="{{ route('admin.commissions') }}" class="menu-item {{ request()->routeIs('admin.commissions*') ? 'active' : '' }}">
        <i class="fas fa-percent"></i>
        <span>Commission Settings</span>
        @php
            $categoriesWithoutCommission = \App\Models\Category::whereNull('vendor_id')
                ->whereDoesntHave('commissionSettings')
                ->count();
        @endphp
        @if($categoriesWithoutCommission > 0)
            <span class="ml-auto bg-teal-600 text-white text-xs px-2 py-1 rounded-full">{{ $categoriesWithoutCommission }}</span>
        @endif
    </a>
    <a href="{{ route('admin.delivery.index') }}" class="menu-item {{ request()->routeIs('admin.delivery*') ? 'active' : '' }}">
        <i class="fas fa-shipping-fast"></i>
        <span>Delivery Management</span>
        @php
            $pendingDeliveries = \App\Models\Order::where('delivery_status', 'pending')
                ->whereNotNull('paperfly_tracking_number')
                ->count();
        @endphp
        @if($pendingDeliveries > 0)
            <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingDeliveries }}</span>
        @endif
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Marketing & Promotions</div>
    <a href="{{ route('admin.coupons') }}" class="menu-item {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
        <i class="fas fa-ticket-alt"></i>
        <span>Coupons</span>
    </a>
    <a href="{{ route('admin.special-offers.index') }}" class="menu-item {{ request()->routeIs('admin.special-offers*') ? 'active' : '' }}">
        <i class="fas fa-tag"></i>
        <span>Special Offers</span>
    </a>
    <a href="{{ route('admin.promo-banners') }}" class="menu-item {{ request()->routeIs('admin.promo-banners*') ? 'active' : '' }}">
        <i class="fas fa-bullhorn"></i>
        <span>Promo Banners</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Pages</div>
    <a href="{{ route('admin.home-page') }}" class="menu-item {{ request()->routeIs('admin.home-page*') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('admin.retail-page') }}" class="menu-item {{ request()->routeIs('admin.retail-page*') ? 'active' : '' }}">
        <i class="fas fa-store"></i>
        <span>Retail Page</span>
    </a>
    <a href="{{ route('admin.wholesale-page') }}" class="menu-item {{ request()->routeIs('admin.wholesale-page*') ? 'active' : '' }}">
        <i class="fas fa-warehouse"></i>
        <span>Wholesale</span>
    </a>
    <a href="{{ route('admin.import-page') }}" class="menu-item {{ request()->routeIs('admin.import-page*') ? 'active' : '' }}">
        <i class="fas fa-shipping-fast"></i>
        <span>Import</span>
    </a>
    <a href="{{ route('admin.about-page.index') }}" class="menu-item {{ request()->routeIs('admin.about-page*') ? 'active' : '' }}">
        <i class="fas fa-info-circle"></i>
        <span>About</span>
    </a>
    <a href="{{ route('admin.page-contents.terms') }}" class="menu-item {{ request()->routeIs('admin.page-contents.terms*') ? 'active' : '' }}">
        <i class="fas fa-file-contract"></i>
        <span>Terms & Conditions</span>
    </a>
    <a href="{{ route('admin.page-contents.exchange') }}" class="menu-item {{ request()->routeIs('admin.page-contents.exchange*') ? 'active' : '' }}">
        <i class="fas fa-exchange-alt"></i>
        <span>Exchange Policy</span>
    </a>
    <a href="{{ route('admin.page-contents.privacy') }}" class="menu-item {{ request()->routeIs('admin.page-contents.privacy*') ? 'active' : '' }}">
        <i class="fas fa-shield-alt"></i>
        <span>Privacy Policy</span>
    </a>
    <a href="{{ route('admin.page-contents.return-refund') }}" class="menu-item {{ request()->routeIs('admin.page-contents.return-refund*') ? 'active' : '' }}">
        <i class="fas fa-undo-alt"></i>
        <span>Return & Refund</span>
    </a>
    <a href="{{ route('admin.page-contents.shipping') }}" class="menu-item {{ request()->routeIs('admin.page-contents.shipping*') ? 'active' : '' }}">
        <i class="fas fa-shipping-fast"></i>
        <span>Shipping Info</span>
    </a>
    <a href="{{ route('admin.contact-page.index') }}" class="menu-item {{ request()->routeIs('admin.contact-page*') ? 'active' : '' }}">
        <i class="fas fa-address-book"></i>
        <span>Contact Page</span>
    </a>
    <a href="{{ route('admin.site-settings.index') }}" class="menu-item {{ request()->routeIs('admin.site-settings*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        <span>Header & Footer</span>
    </a>
    <a href="{{ route('admin.shipping-methods.index') }}" class="menu-item {{ request()->routeIs('admin.shipping-methods*') ? 'active' : '' }}">
        <i class="fas fa-truck"></i>
        <span>Shipping Management</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Payment & Finance</div>
    <a href="{{ route('admin.transactions.index') }}" class="menu-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
        <i class="fas fa-exchange-alt"></i>
        <span>Transactions</span>
    </a>
    <a href="{{ route('admin.payment-settings.index') }}" class="menu-item {{ request()->routeIs('admin.payment-settings*') ? 'active' : '' }}">
        <i class="fas fa-credit-card"></i>
        <span>Payment Gateways</span>
    </a>
    <a href="{{ route('admin.otp-settings.index') }}" class="menu-item {{ request()->routeIs('admin.otp-settings*') ? 'active' : '' }}">
        <i class="fas fa-sms"></i>
        <span>OTP & API Settings</span>
    </a>
    <a href="{{ route('admin.manual-payments.index') }}" class="menu-item {{ request()->routeIs('admin.manual-payments*') ? 'active' : '' }}">
        <i class="fas fa-mobile-alt"></i>
        <span>Payment Verification</span>
        @php
            $pendingPaymentsCount = \App\Models\ManualPayment::pending()->count();
        @endphp
        @if($pendingPaymentsCount > 0)
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingPaymentsCount }}</span>
        @endif
    </a>
    <a href="{{ route('admin.cod-orders.index') }}" class="menu-item {{ request()->routeIs('admin.cod-orders*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-usd"></i>
        <span>Cash on Delivery</span>
        @php
            $codOrdersCount = \App\Models\Order::where('payment_method', 'cod')->where('payment_status', 'pending')->count();
        @endphp
        @if($codOrdersCount > 0)
            <span class="ml-auto bg-teal-600 text-white text-xs px-2 py-1 rounded-full">{{ $codOrdersCount }}</span>
        @endif
    </a>
</div>


<div class="menu-section">
    <div class="menu-section-title">Communication</div>
    <a href="{{ route('admin.tickets.index') }}" class="menu-item {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
        <i class="fas fa-life-ring"></i>
        <span>Support Tickets</span>
    </a>
    <a href="{{ route('admin.chatbot.index') }}" class="menu-item {{ request()->routeIs('admin.chatbot*') ? 'active' : '' }}">
        <i class="fas fa-comment-dots"></i>
        <span>Alpha AI Chat</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Payments</div>
    <a href="{{ route('admin.advance-payments.index') }}" class="menu-item {{ request()->routeIs('admin.advance-payments*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-usd"></i>
        <span>Advance Payments</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">System Settings</div>
    <a href="{{ route('notifications.page') }}" class="menu-item {{ request()->routeIs('notifications.page') ? 'active' : '' }}">
        <i class="fas fa-bell"></i>
        <span>Notifications</span>
        @php $unread = auth()->check() ? auth()->user()->appNotifications()->whereNull('read_at')->count() : 0; @endphp
        @if($unread > 0)
            <span style="margin-left:auto;background:#ef4444;color:#fff;border-radius:10px;padding:1px 7px;font-size:11px;font-weight:700;">{{ $unread }}</span>
        @endif
    </a>
    <a href="{{ route('admin.otp.index') }}" class="menu-item {{ request()->routeIs('admin.otp*') ? 'active' : '' }}">
        <i class="fas fa-key"></i>
        <span>OTP Management</span>
    </a>
    <a href="{{ route('admin.settings.otp.edit') }}" class="menu-item {{ request()->routeIs('admin.settings.otp*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        <span>OTP/API Settings</span>
    </a>
</div>
