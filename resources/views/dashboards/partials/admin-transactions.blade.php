<div id="transactions-section" class="content-section" style="display: none;">
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Transactions</h2>
        <p style="color: #7f8c8d;">All vendor wallet transactions on the platform</p>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
        <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); padding: 20px; border-radius: 10px; color: white;">
            <p style="margin: 0; font-size: 13px; opacity: 0.9;">Total Paid Out</p>
            <h3 style="margin: 5px 0 0 0; font-size: 26px;">৳{{ number_format($transactionStats['total_revenue'] ?? 0, 0) }}</h3>
            <small style="opacity: 0.8;">Completed transactions</small>
        </div>
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); padding: 20px; border-radius: 10px; color: white;">
            <p style="margin: 0; font-size: 13px; opacity: 0.9;">Completed</p>
            <h3 style="margin: 5px 0 0 0; font-size: 26px;">{{ number_format($transactionStats['completed'] ?? 0) }}</h3>
            <small style="opacity: 0.8;">Transactions</small>
        </div>
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); padding: 20px; border-radius: 10px; color: white;">
            <p style="margin: 0; font-size: 13px; opacity: 0.9;">Pending</p>
            <h3 style="margin: 5px 0 0 0; font-size: 26px;">{{ number_format($transactionStats['pending'] ?? 0) }}</h3>
            <small style="opacity: 0.8;">Awaiting delivery</small>
        </div>
        <div style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); padding: 20px; border-radius: 10px; color: white;">
            <p style="margin: 0; font-size: 13px; opacity: 0.9;">Cancelled</p>
            <h3 style="margin: 5px 0 0 0; font-size: 26px;">{{ number_format($transactionStats['cancelled'] ?? 0) }}</h3>
            <small style="opacity: 0.8;">Cancelled orders</small>
        </div>
    </div>

    <!-- Search & Filter -->
    <div style="background: white; padding: 16px 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
        <input type="text" id="txnSearch" placeholder="Search by transaction #, vendor, order..." oninput="filterTxn()"
            style="flex:1; min-width:200px; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
        <select id="txnStatusFilter" onchange="filterTxn()" style="padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <select id="txnTypeFilter" onchange="filterTxn()" style="padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            <option value="">All Types</option>
            <option value="sale">Sale</option>
            <option value="withdrawal">Withdrawal</option>
            <option value="refund">Refund</option>
        </select>
    </div>

    <!-- Table -->
    <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="txnTable">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Transaction #</th>
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Vendor</th>
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Order</th>
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Type</th>
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Amount</th>
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Status</th>
                        <th style="padding: 14px 16px; text-align: left; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions ?? [] as $txn)
                    @php
                        $statusColors = [
                            'completed' => ['bg' => '#d1fae5', 'color' => '#065f46'],
                            'pending'   => ['bg' => '#fef3c7', 'color' => '#92400e'],
                            'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                        ];
                        $sc = $statusColors[$txn->status] ?? ['bg' => '#f3f4f6', 'color' => '#374151'];
                        $typeColors = ['sale' => '#3b82f6', 'withdrawal' => '#8b5cf6', 'refund' => '#ef4444'];
                        $tc = $typeColors[$txn->type] ?? '#6b7280';
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;"
                        data-search="{{ strtolower($txn->transaction_number . ' ' . ($txn->vendor->name ?? '') . ' ' . ($txn->order->order_number ?? '')) }}"
                        data-status="{{ $txn->status }}"
                        data-type="{{ $txn->type }}">
                        <td style="padding: 14px 16px; font-weight: 600; color: #2c3e50; font-size: 13px;">{{ $txn->transaction_number }}</td>
                        <td style="padding: 14px 16px;">
                            <span style="color: #374151; font-weight: 500;">{{ $txn->vendor->name ?? '—' }}</span>
                            <br><small style="color: #9ca3af;">{{ ucfirst($txn->vendor->role ?? '') }}</small>
                        </td>
                        <td style="padding: 14px 16px;">
                            @if($txn->order)
                                <span style="color: #667eea; font-weight: 500;">{{ $txn->order->order_number }}</span>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding: 14px 16px;">
                            <span style="padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $tc }}22; color: {{ $tc }};">
                                {{ ucfirst($txn->type) }}
                            </span>
                        </td>
                        <td style="padding: 14px 16px;">
                            <strong style="color: #10b981; font-size: 15px;">৳{{ number_format($txn->amount, 2) }}</strong>
                        </td>
                        <td style="padding: 14px 16px;">
                            <span style="padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; background: {{ $sc['bg'] }}; color: {{ $sc['color'] }};">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td style="padding: 14px 16px; color: #6b7280; font-size: 13px;">
                            {{ $txn->created_at->format('M d, Y') }}<br>
                            <small>{{ $txn->created_at->format('h:i A') }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 60px; text-align: center; color: #9ca3af;">
                            <i class="fas fa-exchange-alt" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.3;"></i>
                            <p style="font-size: 16px; margin: 0;">No transactions yet.</p>
                            <p style="font-size: 13px; margin-top: 8px;">Transactions are created automatically when orders are placed.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTxn() {
    const search = document.getElementById('txnSearch').value.toLowerCase();
    const status = document.getElementById('txnStatusFilter').value;
    const type   = document.getElementById('txnTypeFilter').value;
    document.querySelectorAll('#txnTable tbody tr[data-search]').forEach(row => {
        const matchSearch = !search || row.dataset.search.includes(search);
        const matchStatus = !status || row.dataset.status === status;
        const matchType   = !type   || row.dataset.type === type;
        row.style.display = (matchSearch && matchStatus && matchType) ? '' : 'none';
    });
}
</script>
