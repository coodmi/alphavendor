@extends('layouts.dashboard')

@section('title', 'Cash on Delivery Orders')
@section('page-title', 'COD Orders')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Cash on Delivery Orders</h2>
            <p style="color: #7f8c8d;">Manage orders that will be paid upon delivery</p>
        </div>
    </div>
</div>

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total COD Orders</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['total'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Pending</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['pending'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Processing</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['processing'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Delivered</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['delivered'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Payment Received</div>
        <div style="font-size: 32px; font-weight: 700;">{{ $stats['paid'] }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Total Amount</div>
        <div style="font-size: 28px; font-weight: 700;">৳{{ number_format($stats['total_amount'], 2) }}</div>
    </div>
</div>

<!-- Filters -->
<div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.cod-orders.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, Name, Phone..."
                style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
        </div>
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Order Status</label>
            <select name="status" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="all">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Payment Status</label>
            <select name="payment_status" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="all">All Payments</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" style="flex: 1; background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.cod-orders.index') }}" style="background: #e5e7eb; color: #6b7280; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Order #</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Customer</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Amount</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Status</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Payment</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; font-weight: 600; color: #2c3e50;">Date</th>
                    <th style="padding: 16px; text-align: center; font-size: 14px; font-weight: 600; color: #2c3e50;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 16px;">
                        <div style="font-weight: 600; color: #667eea;">{{ $order->order_number }}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">{{ $order->orderItems->count() }} items</div>
                    </td>
                    <td style="padding: 16px;">
                        <div style="font-weight: 500; color: #2c3e50;">{{ $order->user->name ?? 'Guest' }}</div>
                        <div style="font-size: 13px; color: #7f8c8d;">{{ $order->phone }}</div>
                        <div style="font-size: 12px; color: #9ca3af;">{{ $order->shipping_city }}</div>
                    </td>
                    <td style="padding: 16px;">
                        <div style="font-weight: 600; color: #2c3e50; font-size: 16px;">৳{{ number_format($order->total, 2) }}</div>
                    </td>
                    <td style="padding: 16px;">
                        @php
                            $statusColors = [
                                'pending' => 'background: #fef3c7; color: #92400e;',
                                'processing' => 'background: #dbeafe; color: #1e40af;',
                                'shipped' => 'background: #e0e7ff; color: #3730a3;',
                                'delivered' => 'background: #d1fae5; color: #065f46;',
                                'cancelled' => 'background: #fee2e2; color: #991b1b;',
                            ];
                        @endphp
                        <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; {{ $statusColors[$order->status] ?? '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td style="padding: 16px;">
                        @if($order->payment_status === 'paid')
                        <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #d1fae5; color: #065f46;">
                            <i class="fas fa-check-circle"></i> Paid
                        </span>
                        @else
                        <span style="padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                            <i class="fas fa-clock"></i> Unpaid
                        </span>
                        @endif
                    </td>
                    <td style="padding: 16px;">
                        <div style="font-size: 13px; color: #2c3e50;">{{ $order->created_at->format('M d, Y') }}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">{{ $order->created_at->format('h:i A') }}</div>
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('admin.orders.show', $order->id) }}" style="background: #667eea; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 13px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($order->payment_status !== 'paid')
                            <button type="button"
                                onclick="openMarkPaidModal(
                                    '{{ $order->id }}',
                                    '{{ $order->order_number }}',
                                    '{{ $order->user->name ?? 'Guest' }}',
                                    '{{ number_format($order->total, 2) }}'
                                )"
                                style="background: #10b981; color: white; padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; display:flex; align-items:center; gap:5px;">
                                <i class="fas fa-check"></i> Mark Paid
                            </button>
                            @else
                            <span style="background:#d1fae5; color:#065f46; padding:8px 14px; border-radius:6px; font-size:13px; font-weight:500;">
                                <i class="fas fa-check-circle"></i> Paid
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 60px 20px; text-align: center;">
                        <i class="fas fa-inbox" style="font-size: 64px; color: #d1d5db; margin-bottom: 16px;"></i>
                        <div style="font-size: 18px; color: #6b7280; margin-bottom: 8px;">No COD Orders Found</div>
                        <div style="font-size: 14px; color: #9ca3af;">Orders paid via Cash on Delivery will appear here</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div style="padding: 20px; border-top: 1px solid #e5e7eb;">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ===== Mark as Paid Modal ===== --}}
<div id="markPaidModal"
     style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px);
            align-items:center; justify-content:center;">
    <div style="background:white; border-radius:20px; width:100%; max-width:440px; margin:16px;
                box-shadow:0 25px 60px rgba(0,0,0,0.25); overflow:hidden; animation: slideUp .25s ease;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#10b981,#059669); padding:28px 28px 24px; text-align:center; position:relative;">
            <button onclick="closeMarkPaidModal()"
                    style="position:absolute; top:14px; right:16px; background:rgba(255,255,255,0.2); border:none;
                           color:white; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px;
                           display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-times"></i>
            </button>
            <div style="width:64px; height:64px; background:rgba(255,255,255,0.2); border-radius:50%;
                        display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                <i class="fas fa-money-bill-wave" style="font-size:28px; color:white;"></i>
            </div>
            <h3 style="color:white; font-size:20px; font-weight:700; margin:0 0 4px;">Confirm Payment</h3>
            <p style="color:rgba(255,255,255,0.85); font-size:14px; margin:0;">Mark this COD order as paid</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px;">
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:18px; margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <span style="font-size:13px; color:#6b7280;">Order Number</span>
                    <span id="modal-order-number" style="font-weight:700; color:#1e40af; font-size:14px;"></span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <span style="font-size:13px; color:#6b7280;">Customer</span>
                    <span id="modal-customer" style="font-weight:600; color:#1f2937; font-size:14px;"></span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:10px; border-top:1px solid #bbf7d0;">
                    <span style="font-size:13px; color:#6b7280;">Amount to Collect</span>
                    <span id="modal-amount" style="font-weight:800; color:#059669; font-size:20px;"></span>
                </div>
            </div>

            <p style="font-size:14px; color:#6b7280; text-align:center; margin:0 0 24px;">
                This action will mark the payment as <strong style="color:#059669;">received</strong> and cannot be undone.
            </p>

            <form id="markPaidForm" method="POST">
                @csrf
                <div style="display:flex; gap:12px;">
                    <button type="button" onclick="closeMarkPaidModal()"
                            style="flex:1; padding:13px; background:#f3f4f6; color:#374151; border:none; border-radius:10px;
                                   cursor:pointer; font-size:15px; font-weight:600; transition:background .2s;">
                        Cancel
                    </button>
                    <button type="submit" id="confirmPaidBtn"
                            style="flex:1; padding:13px; background:linear-gradient(135deg,#10b981,#059669); color:white;
                                   border:none; border-radius:10px; cursor:pointer; font-size:15px; font-weight:700;
                                   display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 14px rgba(16,185,129,0.4);">
                        <i class="fas fa-check-circle"></i> Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity:0; transform:translateY(30px) scale(.97); }
    to   { opacity:1; transform:translateY(0)    scale(1);   }
}
</style>

<script>
function openMarkPaidModal(orderId, orderNumber, customer, amount) {
    document.getElementById('modal-order-number').textContent = orderNumber;
    document.getElementById('modal-customer').textContent     = customer;
    document.getElementById('modal-amount').textContent       = '৳' + amount;
    document.getElementById('markPaidForm').action =
        '{{ url("admin/cod-orders") }}/' + orderId + '/mark-paid';

    const modal = document.getElementById('markPaidModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeMarkPaidModal() {
    document.getElementById('markPaidModal').style.display = 'none';
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target === this) closeMarkPaidModal();
});

// Show spinner on submit
document.getElementById('markPaidForm').addEventListener('submit', function() {
    const btn = document.getElementById('confirmPaidBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
});
</script>
@endsection
