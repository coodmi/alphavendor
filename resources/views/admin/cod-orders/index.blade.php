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
            <select name="status" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
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
            <select name="payment_status" style="width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
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
                        <div style="font-weight: 500; color: #2c3e50;">{{ $order->customer_name }}</div>
                        <div style="font-size: 13px; color: #7f8c8d;">{{ $order->customer_phone }}</div>
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
                            <form method="POST" action="{{ route('admin.cod-orders.mark-paid', $order->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" onclick="return confirm('Mark this order as paid?')" style="background: #10b981; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px;">
                                    <i class="fas fa-check"></i> Mark Paid
                                </button>
                            </form>
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
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
