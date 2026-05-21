@extends('layouts.dashboard')

@section('title', 'My Advance Payments')
@section('page-title', 'My Advance Payments')

@section('sidebar-menu')
    @php $userRole = auth()->user()->role; @endphp
    @if($userRole === 'admin')
        @include('dashboards.partials.admin-sidebar')
    @elseif($userRole === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter' || $userRole === 'importer')
        @include('dashboards.partials.vendor-portal-sidebar')
    @else
        <div class="menu-section">
            <div class="menu-section-title">Account</div>
            <a href="{{ route('orders.my-orders') }}" class="menu-item">
                <i class="fas fa-shopping-bag"></i><span>My Orders</span>
            </a>
            <a href="{{ route('advance-payments.user') }}" class="menu-item active">
                <i class="fas fa-money-check-alt"></i><span>Advance Payments</span>
            </a>
        </div>
    @endif
@endsection

@section('content')
<style>
    .ap-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .ap-header h2 { font-size: 22px; font-weight: 700; color: #1e293b; margin: 0; }
    .ap-header p  { color: #64748b; font-size: 14px; margin: 4px 0 0; }

    .ap-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .ap-stat {
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.07);
        text-align: center;
    }
    .ap-stat .val { font-size: 26px; font-weight: 700; color: #1e293b; }
    .ap-stat .lbl { font-size: 12px; color: #64748b; margin-top: 4px; }

    .ap-table-wrap {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 8px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .ap-table { width: 100%; border-collapse: collapse; }
    .ap-table th {
        background: #f8fafc;
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }
    .ap-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
        vertical-align: middle;
    }
    .ap-table tr:last-child td { border-bottom: none; }
    .ap-table tr:hover td { background: #f8fafc; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pending   { background: #fef9c3; color: #854d0e; }
    .status-approved  { background: #dbeafe; color: #1e40af; }
    .status-paid      { background: #dcfce7; color: #166534; }
    .status-completed { background: #ede9fe; color: #5b21b6; }
    .status-rejected  { background: #fee2e2; color: #991b1b; }
    .status-cancelled { background: #f1f5f9; color: #475569; }

    .product-cell { display: flex; align-items: center; gap: 10px; }
    .product-img  { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
    .product-name { font-weight: 600; color: #1e293b; font-size: 13px; }
    .product-qty  { font-size: 12px; color: #64748b; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }
    .empty-state h3 { font-size: 18px; color: #475569; margin-bottom: 8px; }
    .empty-state p  { font-size: 14px; }

    @media (max-width: 768px) {
        .ap-stats { grid-template-columns: repeat(2, 1fr); }
        .ap-table-wrap { overflow-x: auto; }
        .ap-table { min-width: 600px; }
    }
</style>

<div class="ap-header">
    <div>
        <h2><i class="fas fa-money-check-alt" style="color:#6366f1;margin-right:8px;"></i>My Advance Payments</h2>
        <p>Track all your advance payment requests and their status</p>
    </div>
    <a href="{{ route('shop') }}" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;display:inline-flex;align-items:center;gap:8px;">
        <i class="fas fa-shopping-bag"></i> Browse Products
    </a>
</div>

{{-- Stats --}}
@php
    $total     = $payments->total();
    $pending   = $payments->getCollection()->where('status','pending')->count();
    $approved  = $payments->getCollection()->whereIn('status',['approved','paid','completed'])->count();
    $rejected  = $payments->getCollection()->where('status','rejected')->count();
    $totalPaid = $payments->getCollection()->whereIn('status',['paid','completed'])->sum('advance_amount');
@endphp

<div class="ap-stats">
    <div class="ap-stat">
        <div class="val">{{ $payments->total() }}</div>
        <div class="lbl">Total Requests</div>
    </div>
    <div class="ap-stat">
        <div class="val" style="color:#d97706;">{{ $payments->getCollection()->where('status','pending')->count() }}</div>
        <div class="lbl">Pending</div>
    </div>
    <div class="ap-stat">
        <div class="val" style="color:#16a34a;">{{ $payments->getCollection()->whereIn('status',['approved','paid','completed'])->count() }}</div>
        <div class="lbl">Approved / Paid</div>
    </div>
    <div class="ap-stat">
        <div class="val" style="color:#6366f1;">৳{{ number_format($payments->getCollection()->whereIn('status',['paid','completed'])->sum('advance_amount'), 2) }}</div>
        <div class="lbl">Total Paid</div>
    </div>
</div>

{{-- Table --}}
<div class="ap-table-wrap">
    @if($payments->count() > 0)
    <table class="ap-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Total Amount</th>
                <th>Advance Paid</th>
                <th>Remaining</th>
                <th>Payment Method</th>
                <th>Transaction ID</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td style="font-weight:600;color:#6366f1;">{{ $loop->iteration }}</td>
                <td>
                    <div class="product-cell">
                        @if($payment->product && $payment->product->image)
                            <img src="{{ str_starts_with($payment->product->image,'http') ? $payment->product->image : asset('storage/'.$payment->product->image) }}"
                                 alt="{{ $payment->product->name }}" class="product-img">
                        @else
                            <div style="width:44px;height:44px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-box" style="color:#94a3b8;"></i>
                            </div>
                        @endif
                        <div>
                            <div class="product-name">{{ $payment->product->name ?? 'N/A' }}</div>
                            <div class="product-qty">Qty: {{ $payment->quantity }}</div>
                        </div>
                    </div>
                </td>
                <td><strong>৳{{ number_format($payment->total_amount, 2) }}</strong></td>
                <td style="color:#16a34a;font-weight:600;">৳{{ number_format($payment->advance_amount, 2) }}</td>
                <td style="color:#dc2626;">৳{{ number_format($payment->remaining_amount, 2) }}</td>
                <td>
                    @php
                        $methodIcons = ['bkash'=>'<span style="background:#e91e8c;color:white;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">bKash</span>','nagad'=>'<span style="background:#f97316;color:white;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">Nagad</span>','rocket'=>'<span style="background:#7c3aed;color:white;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">Rocket</span>','bank_transfer'=>'<span style="background:#1d4ed8;color:white;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">Bank</span>'];
                    @endphp
                    {!! $methodIcons[$payment->payment_method] ?? ucfirst($payment->payment_method) !!}
                </td>
                <td style="font-family:monospace;font-size:12px;">{{ $payment->transaction_id ?? '—' }}</td>
                <td>
                    @php
                        $statusClasses = ['pending'=>'status-pending','approved'=>'status-approved','paid'=>'status-paid','completed'=>'status-completed','rejected'=>'status-rejected','cancelled'=>'status-cancelled'];
                        $statusIcons   = ['pending'=>'fa-clock','approved'=>'fa-check-circle','paid'=>'fa-check-double','completed'=>'fa-star','rejected'=>'fa-times-circle','cancelled'=>'fa-ban'];
                    @endphp
                    <span class="status-badge {{ $statusClasses[$payment->status] ?? 'status-pending' }}">
                        <i class="fas {{ $statusIcons[$payment->status] ?? 'fa-circle' }}"></i>
                        {{ ucfirst($payment->status) }}
                    </span>
                    @if($payment->admin_notes)
                        <div style="font-size:11px;color:#64748b;margin-top:4px;max-width:160px;">{{ Str::limit($payment->admin_notes, 60) }}</div>
                    @endif
                </td>
                <td style="font-size:12px;color:#64748b;">
                    {{ $payment->created_at->format('d M Y') }}<br>
                    <span style="font-size:11px;">{{ $payment->created_at->format('h:i A') }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($payments->hasPages())
    <div style="padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:center;">
        {{ $payments->links() }}
    </div>
    @endif

    @else
    <div class="empty-state">
        <i class="fas fa-money-check-alt"></i>
        <h3>No Advance Payments Yet</h3>
        <p>You haven't made any advance payment requests. Browse wholesale or import products to get started.</p>
        <a href="{{ route('shop') }}" style="display:inline-block;margin-top:16px;background:#6366f1;color:white;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:600;">
            Browse Products
        </a>
    </div>
    @endif
</div>
@endsection
