@extends('layouts.dashboard')

@section('title', 'Vendor Details - ' . $user->name)
@section('page-title', 'Vendor Details')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">

    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="font-size:26px; color:#2c3e50; margin:0;">Vendor Profile</h2>
            <p style="color:#7f8c8d; margin:4px 0 0;">{{ ucfirst($user->role) }} Account</p>
        </div>
        <a href="{{ route('admin.vendors') }}" style="padding:10px 20px; background:#95a5a6; color:white; border-radius:6px; text-decoration:none; font-size:14px;">
            <i class="fas fa-arrow-left"></i> Back to Vendors
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px; border-radius:6px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:24px;">

        <!-- Left: Profile Card -->
        <div style="background:white; border-radius:12px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.08); text-align:center;">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}"
                    style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin-bottom:16px; border:3px solid #667eea;">
            @else
                <div style="width:100px; height:100px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:36px; color:white;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <h3 style="margin:0 0 4px; font-size:20px; color:#2c3e50;">{{ $user->name }}</h3>
            <p style="margin:0 0 12px; color:#7f8c8d; font-size:14px;">{{ $user->email }}</p>

            <span style="padding:4px 14px; border-radius:20px; font-size:13px; font-weight:600;
                background:{{ $user->status === 'active' ? '#d4edda' : '#f8d7da' }};
                color:{{ $user->status === 'active' ? '#155724' : '#721c24' }};">
                {{ ucfirst($user->status ?? 'active') }}
            </span>

            <div style="margin-top:16px; padding-top:16px; border-top:1px solid #eee; text-align:left;">
                <div style="margin-bottom:10px;">
                    <span style="font-size:12px; color:#7f8c8d; display:block;">Role</span>
                    <span style="font-weight:600; color:#2c3e50;">{{ ucfirst($user->role) }}</span>
                </div>
                @if($user->mobile_number)
                <div style="margin-bottom:10px;">
                    <span style="font-size:12px; color:#7f8c8d; display:block;">Phone</span>
                    <span style="font-weight:600; color:#2c3e50;">{{ $user->mobile_number }}</span>
                </div>
                @endif
                <div style="margin-bottom:10px;">
                    <span style="font-size:12px; color:#7f8c8d; display:block;">Joined</span>
                    <span style="font-weight:600; color:#2c3e50;">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span style="font-size:12px; color:#7f8c8d; display:block;">Total Products</span>
                    <span style="font-weight:600; color:#2c3e50;">{{ $user->products->count() }}</span>
                </div>
            </div>

            <!-- Update Status -->
            <form action="{{ route('admin.vendors.update-status', $user) }}" method="POST" style="margin-top:16px;">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                    style="width:100%; padding:8px 12px; border:1px solid #ddd; border-radius:6px; font-size:14px; cursor:pointer;">
                    <option value="active"    {{ ($user->status ?? 'active') === 'active'    ? 'selected' : '' }}>Active</option>
                    <option value="inactive"  {{ ($user->status ?? '') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ ($user->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </form>
        </div>

        <!-- Right: Stats + Products + Orders -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- Stats -->
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
                <div style="background:white; border-radius:10px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.08); text-align:center;">
                    <div style="font-size:28px; font-weight:700; color:#667eea;">{{ $user->products->count() }}</div>
                    <div style="font-size:13px; color:#7f8c8d; margin-top:4px;">Products</div>
                </div>
                <div style="background:white; border-radius:10px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.08); text-align:center;">
                    <div style="font-size:28px; font-weight:700; color:#27ae60;">{{ $user->orders->count() }}</div>
                    <div style="font-size:13px; color:#7f8c8d; margin-top:4px;">Orders</div>
                </div>
                <div style="background:white; border-radius:10px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.08); text-align:center;">
                    <div style="font-size:28px; font-weight:700; color:#e67e22;">
                        ৳{{ number_format($user->orders->sum('total'), 0) }}
                    </div>
                    <div style="font-size:13px; color:#7f8c8d; margin-top:4px;">Total Sales</div>
                </div>
            </div>

            <!-- Products -->
            <div style="background:white; border-radius:12px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                <h4 style="margin:0 0 16px; color:#2c3e50; font-size:16px;">
                    <i class="fas fa-box" style="color:#667eea;"></i> Products ({{ $user->products->count() }})
                </h4>
                @if($user->products->count() > 0)
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:14px;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Product</th>
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Price</th>
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Stock</th>
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->products->take(10) as $product)
                            <tr style="border-top:1px solid #f0f0f0;">
                                <td style="padding:10px 12px;">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" style="width:36px; height:36px; border-radius:4px; object-fit:cover;">
                                        @endif
                                        <span style="font-weight:500; color:#2c3e50;">{{ Str::limit($product->name, 35) }}</span>
                                    </div>
                                </td>
                                <td style="padding:10px 12px; color:#27ae60; font-weight:600;">৳{{ number_format($product->price, 2) }}</td>
                                <td style="padding:10px 12px; color:#7f8c8d;">{{ $product->stock }}</td>
                                <td style="padding:10px 12px;">
                                    <span style="padding:2px 10px; border-radius:12px; font-size:12px; font-weight:600;
                                        background:{{ $product->status === 'active' ? '#d4edda' : '#f8d7da' }};
                                        color:{{ $product->status === 'active' ? '#155724' : '#721c24' }};">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p style="color:#7f8c8d; text-align:center; padding:20px 0;">No products yet.</p>
                @endif
            </div>

            <!-- Recent Orders -->
            <div style="background:white; border-radius:12px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                <h4 style="margin:0 0 16px; color:#2c3e50; font-size:16px;">
                    <i class="fas fa-shopping-cart" style="color:#27ae60;"></i> Recent Orders ({{ $user->orders->count() }})
                </h4>
                @if($user->orders->count() > 0)
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:14px;">
                        <thead>
                            <tr style="background:#f8f9fa;">
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Order #</th>
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Total</th>
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Status</th>
                                <th style="padding:10px 12px; text-align:left; color:#7f8c8d; font-weight:600;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->sortByDesc('created_at')->take(10) as $order)
                            <tr style="border-top:1px solid #f0f0f0;">
                                <td style="padding:10px 12px; font-weight:600; color:#667eea;">#{{ $order->id }}</td>
                                <td style="padding:10px 12px; color:#27ae60; font-weight:600;">৳{{ number_format($order->total, 2) }}</td>
                                <td style="padding:10px 12px;">
                                    <span style="padding:2px 10px; border-radius:12px; font-size:12px; font-weight:600;
                                        background:{{ $order->status === 'delivered' ? '#d4edda' : ($order->status === 'cancelled' ? '#f8d7da' : '#fff3cd') }};
                                        color:{{ $order->status === 'delivered' ? '#155724' : ($order->status === 'cancelled' ? '#721c24' : '#856404') }};">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td style="padding:10px 12px; color:#7f8c8d;">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p style="color:#7f8c8d; text-align:center; padding:20px 0;">No orders yet.</p>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
