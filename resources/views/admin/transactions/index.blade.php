@extends('layouts.dashboard')

@section('title', 'Transactions')
@section('page-title', 'Transactions')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div style="max-width:1200px;">

    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px;">
        <div>
            <h2 style="font-size:26px; color:#2c3e50; margin:0; font-weight:700;">Transaction History</h2>
            <p style="color:#7f8c8d; margin:4px 0 0; font-size:14px;">All vendor wallet transactions on the platform</p>
        </div>
    </div>

    <!-- Stats -->
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:28px;">
        <div style="background:linear-gradient(135deg,#10b981,#34d399); padding:22px; border-radius:12px; color:white; box-shadow:0 4px 15px rgba(16,185,129,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <p style="margin:0; font-size:13px; opacity:0.9;">Total Paid Out</p>
                    <h3 style="margin:6px 0 0; font-size:28px; font-weight:700;">৳{{ number_format($stats['total_revenue'], 0) }}</h3>
                    <small style="opacity:0.8;">Completed transactions</small>
                </div>
                <i class="fas fa-taka-sign" style="font-size:28px; opacity:0.4;"></i>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#3b82f6,#60a5fa); padding:22px; border-radius:12px; color:white; box-shadow:0 4px 15px rgba(59,130,246,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <p style="margin:0; font-size:13px; opacity:0.9;">Completed</p>
                    <h3 style="margin:6px 0 0; font-size:28px; font-weight:700;">{{ number_format($stats['completed']) }}</h3>
                    <small style="opacity:0.8;">Transactions</small>
                </div>
                <i class="fas fa-check-circle" style="font-size:28px; opacity:0.4;"></i>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#f59e0b,#fbbf24); padding:22px; border-radius:12px; color:white; box-shadow:0 4px 15px rgba(245,158,11,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <p style="margin:0; font-size:13px; opacity:0.9;">Pending</p>
                    <h3 style="margin:6px 0 0; font-size:28px; font-weight:700;">{{ number_format($stats['pending']) }}</h3>
                    <small style="opacity:0.8;">Awaiting delivery</small>
                </div>
                <i class="fas fa-clock" style="font-size:28px; opacity:0.4;"></i>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#ef4444,#f87171); padding:22px; border-radius:12px; color:white; box-shadow:0 4px 15px rgba(239,68,68,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <p style="margin:0; font-size:13px; opacity:0.9;">Cancelled</p>
                    <h3 style="margin:6px 0 0; font-size:28px; font-weight:700;">{{ number_format($stats['cancelled']) }}</h3>
                    <small style="opacity:0.8;">Cancelled orders</small>
                </div>
                <i class="fas fa-times-circle" style="font-size:28px; opacity:0.4;"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background:white; padding:18px 22px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.07); margin-bottom:20px;">
        <form method="GET" action="{{ route('admin.transactions.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by transaction #, vendor..."
                style="flex:1; min-width:200px; padding:10px 14px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px;"
                oninput="clearTimeout(window._st); window._st=setTimeout(()=>this.form.submit(),500)">
            <select name="status" onchange="this.form.submit()" style="padding:10px 14px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px; background:white;">
                <option value="">All Status</option>
                <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending"   {{ request('status')==='pending'   ? 'selected' : '' }}>Pending</option>
                <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <select name="type" onchange="this.form.submit()" style="padding:10px 14px; border:1px solid #e0e0e0; border-radius:8px; font-size:14px; background:white;">
                <option value="">All Types</option>
                <option value="sale"       {{ request('type')==='sale'       ? 'selected' : '' }}>Sale</option>
                <option value="withdrawal" {{ request('type')==='withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                <option value="refund"     {{ request('type')==='refund'     ? 'selected' : '' }}>Refund</option>
            </select>
            <button type="submit" style="padding:10px 20px; background:linear-gradient(135deg,#667eea,#764ba2); color:white; border:none; border-radius:8px; cursor:pointer; font-size:14px; font-weight:600;">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->hasAny(['search','status','type']))
            <a href="{{ route('admin.transactions.index') }}" style="padding:10px 16px; background:#f3f4f6; color:#374151; border-radius:8px; text-decoration:none; font-size:14px;">
                <i class="fas fa-times"></i> Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div style="background:white; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.07); overflow:hidden;">
        <div style="padding:18px 22px; border-bottom:1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; color:#2c3e50; font-size:16px; font-weight:600;">
                All Transactions
                <span style="font-size:13px; color:#7f8c8d; font-weight:400; margin-left:8px;">{{ $transactions->total() }} total</span>
            </h3>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f9fa;">
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Transaction #</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Vendor</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Order</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Type</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Amount</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Description</th>
                        <th style="padding:14px 18px; text-align:left; color:#6b7280; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    @php
                        $statusMap = [
                            'completed' => ['bg'=>'#d1fae5','color'=>'#065f46'],
                            'pending'   => ['bg'=>'#fef3c7','color'=>'#92400e'],
                            'cancelled' => ['bg'=>'#fee2e2','color'=>'#991b1b'],
                        ];
                        $sc = $statusMap[$txn->status] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
                        $typeMap = [
                            'sale'       => ['bg'=>'#dbeafe','color'=>'#1d4ed8','icon'=>'fa-shopping-cart'],
                            'withdrawal' => ['bg'=>'#ede9fe','color'=>'#6d28d9','icon'=>'fa-arrow-up'],
                            'refund'     => ['bg'=>'#fee2e2','color'=>'#991b1b','icon'=>'fa-undo'],
                        ];
                        $tc = $typeMap[$txn->type] ?? ['bg'=>'#f3f4f6','color'=>'#374151','icon'=>'fa-circle'];
                    @endphp
                    <tr style="border-bottom:1px solid #f5f5f5; transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                        <td style="padding:14px 18px;">
                            <span style="font-weight:600; color:#2c3e50; font-size:13px; font-family:monospace;">{{ $txn->transaction_number }}</span>
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:14px; flex-shrink:0;">
                                    {{ strtoupper(substr($txn->vendor->name ?? 'V', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; color:#374151; font-size:14px;">{{ $txn->vendor->name ?? '—' }}</div>
                                    <div style="font-size:12px; color:#9ca3af;">{{ ucfirst($txn->vendor->role ?? '') }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:14px 18px;">
                            @if($txn->order)
                                <a href="{{ route('admin.orders.show', $txn->order_id) }}" style="color:#667eea; font-weight:600; text-decoration:none; font-size:13px;">
                                    {{ $txn->order->order_number }}
                                </a>
                            @else
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td style="padding:14px 18px;">
                            <span style="padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; background:{{ $tc['bg'] }}; color:{{ $tc['color'] }}; display:inline-flex; align-items:center; gap:5px;">
                                <i class="fas {{ $tc['icon'] }}" style="font-size:10px;"></i>
                                {{ ucfirst($txn->type) }}
                            </span>
                        </td>
                        <td style="padding:14px 18px;">
                            <span style="font-size:16px; font-weight:700; color:{{ $txn->type === 'withdrawal' ? '#ef4444' : '#10b981' }};">
                                {{ $txn->type === 'withdrawal' ? '-' : '+' }}৳{{ number_format($txn->amount, 2) }}
                            </span>
                        </td>
                        <td style="padding:14px 18px;">
                            <span style="padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td style="padding:14px 18px; color:#6b7280; font-size:13px; max-width:200px;">
                            {{ Str::limit($txn->description ?? '—', 40) }}
                        </td>
                        <td style="padding:14px 18px; color:#6b7280; font-size:13px; white-space:nowrap;">
                            {{ $txn->created_at->format('M d, Y') }}<br>
                            <span style="font-size:12px;">{{ $txn->created_at->format('h:i A') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding:80px; text-align:center; color:#9ca3af;">
                            <i class="fas fa-exchange-alt" style="font-size:56px; display:block; margin-bottom:16px; opacity:0.2;"></i>
                            <p style="font-size:18px; font-weight:600; margin:0 0 8px;">No transactions found</p>
                            <p style="font-size:14px; margin:0;">Transactions are created automatically when orders are placed and delivered.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div style="padding:18px 22px; border-top:1px solid #f0f0f0;">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
