<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; color: #1a1a1a; background: #fff; }
        .page { max-width: 800px; margin: 0 auto; padding: 40px; }

        /* Print/Download buttons - hidden when printing */
        .no-print { margin-bottom: 24px; display: flex; gap: 12px; }
        .btn { padding: 10px 22px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-print { background: #1e293b; color: #fff; }
        .btn-download { background: #1a6b73; color: #fff; }
        .btn-back { background: #f1f5f9; color: #475569; }

        /* Invoice */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; padding-bottom: 24px; border-bottom: 2px solid #f1f5f9; }
        .brand { font-size: 26px; font-weight: 800; color: #1a6b73; }
        .brand span { color: #1e293b; }
        .invoice-meta { text-align: right; }
        .invoice-meta h2 { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
        .invoice-meta p { color: #64748b; font-size: 13px; line-height: 1.6; }

        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-pending { background: #fef9c3; color: #ca8a04; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
        .info-box { background: #f8fafc; border-radius: 10px; padding: 18px; }
        .info-box h4 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 10px; }
        .info-box p { color: #334155; line-height: 1.7; font-size: 13px; }
        .info-box strong { color: #1e293b; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { background: #1e293b; }
        thead th { padding: 12px 16px; text-align: left; color: #fff; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:hover { background: #f8fafc; }
        tbody td { padding: 14px 16px; color: #334155; font-size: 13px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .totals { margin-left: auto; width: 320px; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; color: #475569; border-bottom: 1px solid #f1f5f9; }
        .totals-row.total { font-size: 16px; font-weight: 700; color: #1e293b; border-bottom: none; padding-top: 12px; }

        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; color: #94a3b8; font-size: 12px; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .page { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- Action Buttons -->
    <div class="no-print">
        <a href="javascript:history.back()" class="btn btn-back">← Back</a>
        <button onclick="window.print()" class="btn btn-print">🖨 Print Invoice</button>
        <button onclick="downloadPDF()" class="btn btn-download">⬇ Download PDF</button>
    </div>

    <!-- Header -->
    <div class="header">
        <div>
            <div class="brand">Alpha<span>Vendor</span></div>
            <p style="color:#64748b;font-size:13px;margin-top:6px;">Your trusted multi-vendor marketplace</p>
            @if($siteSettings->contact_email)
                <p style="color:#64748b;font-size:13px;">{{ $siteSettings->contact_email }}</p>
            @endif
        </div>
        <div class="invoice-meta">
            <h2>INVOICE</h2>
            <p><strong>#{{ $order->order_number }}</strong></p>
            <p>Date: {{ $order->created_at->format('d M Y') }}</p>
            <p style="margin-top:8px;">
                <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </p>
        </div>
    </div>

    <!-- Billing & Shipping -->
    <div class="grid-2">
        <div class="info-box">
            <h4>Bill To</h4>
            <p><strong>{{ $order->user->name ?? 'Customer' }}</strong></p>
            <p>{{ $order->user->email ?? '' }}</p>
            <p>{{ $order->phone }}</p>
        </div>
        <div class="info-box">
            <h4>Ship To</h4>
            <p>{{ $order->shipping_address }}</p>
            @if($order->shipping_city)<p>{{ $order->shipping_city }}@if($order->shipping_state), {{ $order->shipping_state }}@endif</p>@endif
            @if($order->shipping_zip)<p>{{ $order->shipping_zip }}</p>@endif
            @if($order->shipping_country)<p>{{ $order->shipping_country }}</p>@endif
        </div>
    </div>

    <!-- Payment Info -->
    <div class="grid-2" style="margin-bottom:32px;">
        <div class="info-box">
            <h4>Payment</h4>
            <p><strong>Method:</strong> {{ ucwords(str_replace('_',' ',$order->payment_method ?? 'N/A')) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->payment_status ?? 'pending') }}</p>
        </div>
        <div class="info-box">
            <h4>Order Info</h4>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
            @if($order->notes)<p><strong>Notes:</strong> {{ $order->notes }}</p>@endif
        </div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? $item->product_name ?? 'Product' }}</strong>
                    @if($item->product->sku ?? null)
                        <br><span style="color:#94a3b8;font-size:12px;">SKU: {{ $item->product->sku }}</span>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">৳{{ number_format($item->price, 2) }}</td>
                <td class="text-right">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    @php
        $subtotal      = (float) ($order->subtotal ?? $order->total ?? 0);
        $delivery      = (float) ($order->delivery_charge ?? 0);
        $totalAmount   = (float) ($order->total ?? $order->total_amount ?? 0);

        // Advance payment for this order
        $advance = \App\Models\AdvancePayment::where('user_id', $order->user_id)
            ->whereHas('product', function($q) use ($order) {
                $q->whereIn('id', $order->items->pluck('product_id'));
            })
            ->whereIn('status', ['approved','paid','completed'])
            ->first();

        $advancePaid    = $advance ? (float) $advance->advance_amount : 0;
        $remainingDue   = $totalAmount - $advancePaid;
    @endphp

    <div class="totals">
        {{-- Subtotal --}}
        <div class="totals-row">
            <span>Subtotal</span>
            <span>৳{{ number_format($subtotal, 2) }}</span>
        </div>

        {{-- Delivery Charge --}}
        <div class="totals-row">
            <span>Delivery Charge</span>
            <span>{{ $delivery > 0 ? '৳' . number_format($delivery, 2) : 'Free' }}</span>
        </div>

        {{-- Total Order Amount --}}
        <div class="totals-row" style="font-weight:600; color:#1e293b; border-bottom:2px solid #e2e8f0; padding-bottom:10px; margin-bottom:4px;">
            <span>Total Order Amount</span>
            <span>৳{{ number_format($totalAmount, 2) }}</span>
        </div>

        {{-- Advance Payment Paid --}}
        @if($advancePaid > 0)
        <div class="totals-row" style="color:#16a34a;">
            <span>
                Advance Payment Paid
                @if($advance)
                    <span style="font-size:11px; background:#dcfce7; color:#16a34a; padding:2px 7px; border-radius:10px; margin-left:4px;">
                        {{ $advance->advance_percentage }}%
                    </span>
                @endif
            </span>
            <span style="font-weight:600;">− ৳{{ number_format($advancePaid, 2) }}</span>
        </div>
        @endif

        {{-- Remaining Due --}}
        @if($advancePaid > 0)
        <div class="totals-row total" style="color:{{ $remainingDue > 0 ? '#dc2626' : '#16a34a' }}; border-top:2px solid #f1f5f9; margin-top:4px;">
            <span>Remaining Due Amount</span>
            <span>৳{{ number_format(max(0, $remainingDue), 2) }}</span>
        </div>
        @else
        <div class="totals-row total">
            <span>Amount Due</span>
            <span>৳{{ number_format($totalAmount, 2) }}</span>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your order! For support, contact us at {{ $siteSettings->contact_email ?? 'support@alphavendor.com' }}</p>
        <p style="margin-top:6px;">© {{ date('Y') }} AlphaVendor. All rights reserved.</p>
    </div>
</div>

<script>
function downloadPDF() {
    window.print();
    // Browser print dialog has "Save as PDF" option
    // For true PDF generation, a server-side library like DomPDF would be needed
}
</script>
</body>
</html>
