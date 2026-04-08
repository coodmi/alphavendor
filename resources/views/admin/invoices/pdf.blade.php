<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Commission Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4CAF50;
        }
        .header h1 {
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 5px;
        }
        .header .invoice-number {
            font-size: 16px;
            font-weight: bold;
            color: #666;
        }
        .header .date {
            font-size: 12px;
            color: #999;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 180px;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            display: table-cell;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border: 1px solid #ddd;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount-col {
            text-align: right;
            font-weight: 500;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0 !important;
        }
        .penalty-row {
            background-color: #fff3cd !important;
        }
        .final-total-row {
            font-weight: bold;
            font-size: 14px;
            background-color: #d4edda !important;
            color: #155724;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Commission Invoice</h1>
        <div class="invoice-number">{{ $invoice->invoice_number }}</div>
        <div class="date">Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Seller Name:</div>
            <div class="info-value">{{ $invoice->seller->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Seller Type:</div>
            <div class="info-value">{{ ucfirst($invoice->seller->role) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Order Number:</div>
            <div class="info-value">{{ $invoice->order->order_number }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Order Amount:</div>
            <div class="info-value">৳{{ number_format($invoice->order_amount, 2) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Delivery Charge:</div>
            <div class="info-value">৳{{ number_format($invoice->delivery_charge, 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 50%;">Description</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 20%;" class="amount-col">Amount (৳)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr class="{{ $item->item_type === 'penalty' ? 'penalty-row' : '' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    @if($item->item_type === 'category_commission')
                        <span class="badge badge-success">Commission</span>
                    @elseif($item->item_type === 'cod_commission')
                        <span class="badge badge-success">COD</span>
                    @else
                        <span class="badge badge-warning">Penalty</span>
                    @endif
                </td>
                <td class="amount-col">{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Commission:</td>
                <td class="amount-col">৳{{ number_format($invoice->total_commission, 2) }}</td>
            </tr>
            
            @if($invoice->penalty_amount > 0)
            <tr class="total-row penalty-row">
                <td colspan="3" style="text-align: right;">Total Penalties:</td>
                <td class="amount-col">৳{{ number_format($invoice->penalty_amount, 2) }}</td>
            </tr>
            @endif
            
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Deduction:</td>
                <td class="amount-col">৳{{ number_format($invoice->total_deduction, 2) }}</td>
            </tr>
            
            <tr class="final-total-row">
                <td colspan="3" style="text-align: right;">Net Vendor Earning:</td>
                <td class="amount-col">৳{{ number_format($invoice->net_vendor_earning, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated invoice and does not require a signature.</p>
        <p>Generated on {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>
