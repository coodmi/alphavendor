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
        .btn-download { background: #f97316; color: #fff; }
        .btn-back { background: #f1f5f9; color: #475569; }

        /* Invoice */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; padding-bottom: 24px; border-bottom: 2px solid #f1f5f9; }
        .brand { font-size: 26px; font-weight: 800; color: #f97316; }
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

        .totals { margin-left: auto; width: 280px; }
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
    <div class="totals">
        <div class="totals-row">
            <span>Subtotal</span>
            <span>৳{{ number_format($order->subtotal ?? $order->total, 2) }}</span>
        </div>
        @if($order->delivery_charge)
        <div class="totals-row">
            <span>Delivery</span>
            <span>৳{{ number_format($order->delivery_charge, 2) }}</span>
        </div>
        @endif
        @if($order->commission_amount)
        <div class="totals-row">
            <span>Commission</span>
            <span>৳{{ number_format($order->commission_amount, 2) }}</span>
        </div>
        @endif
        <div class="totals-row total">
            <span>Total</span>
            <span>৳{{ number_format($order->total ?? $order->total_amount, 2) }}</span>
        </div>
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
