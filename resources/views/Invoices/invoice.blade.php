@php
    $subtotal = (float) $order->items->sum('subtotal');
    $itemTax = (float) $order->items->sum(function ($item) {
        $rate = (float) ($item->gst_rate ?? $item->product->tax_rate ?? $item->product->gst_rate ?? 0);
        $storedTax = (float) ($item->tax_amount ?? 0);
        return $storedTax > 0 ? $storedTax : ((float) $item->subtotal * $rate / 100);
    });
    $shipping = (float) ($order->shipping_amount ?? $order->items->sum(fn ($item) => (float) ($item->shipping_charge ?? 0) * (int) $item->quantity));
    $discount = (float) ($order->discount_amount ?? 0);
    $tax = (float) ($order->tax_amount ?? 0);
    $tax = $tax > 0 ? $tax : $itemTax;
    $grandTotal = (float) ($order->total_amount ?? ($subtotal + $shipping + $tax - $discount));
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 0; }
        .invoice { padding: 28px; }
        .top-table, .info-table, .items-table, .totals-table, .meta-table { width: 100%; border-collapse: collapse; }
        .top-table td { vertical-align: top; }
        .logo { max-height: 70px; max-width: 170px; margin-bottom: 8px; }
        .company-name { color: #0f172a; font-size: 20px; font-weight: bold; margin-bottom: 4px; }
        .muted { color: #64748b; line-height: 1.5; }
        .invoice-title { color: #2563eb; font-size: 30px; font-weight: bold; text-align: right; text-transform: uppercase; }
        .meta-table { margin-top: 10px; }
        .meta-table td { padding: 3px 0; text-align: right; }
        .badge { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; display: inline-block; font-size: 10px; font-weight: bold; padding: 4px 8px; text-transform: uppercase; }
        .section-title { color: #0f172a; font-size: 12px; font-weight: bold; margin: 0 0 8px; text-transform: uppercase; }
        .info-table { margin-top: 24px; }
        .info-table td { padding: 12px; vertical-align: top; width: 50%; }
        .info-box { border: 1px solid #e2e8f0; background: #f8fafc; min-height: 105px; }
        .items-table { margin-top: 24px; }
        .items-table th { background: #2563eb; color: #ffffff; font-size: 10px; padding: 8px 6px; text-align: left; text-transform: uppercase; }
        .items-table td { border-bottom: 1px solid #e5e7eb; padding: 8px 6px; vertical-align: top; }
        .items-table tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .product-name { font-weight: bold; color: #111827; }
        .variation { color: #64748b; font-size: 10px; margin-top: 2px; }
        .totals-wrap { width: 100%; margin-top: 18px; }
        .totals-table { float: right; width: 330px; }
        .totals-table td { border-bottom: 1px solid #e5e7eb; padding: 7px 8px; }
        .totals-table .grand td { background: #0f766e; color: #ffffff; font-size: 13px; font-weight: bold; }
        .payment-box { border: 1px solid #e2e8f0; margin-top: 18px; padding: 10px; width: 340px; }
        .footer { border-top: 1px solid #e2e8f0; clear: both; color: #64748b; font-size: 10px; line-height: 1.5; margin-top: 52px; padding-top: 12px; text-align: center; }
    </style>
</head>
<body>
<div class="invoice">
    <table class="top-table">
        <tr>
            <td style="width: 58%;">
                <img src="{{ $company['logo_path'] }}" alt="{{ $company['name'] }}" class="logo">
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="muted">{{ $company['tagline'] }}</div>
                <div class="muted">
                    {{ $company['address'] }}<br>
                    {{ $company['email'] }} | {{ $company['phone'] }}<br>
                    GSTIN: {{ $company['gstin'] }}
                </div>
            </td>
            <td style="width: 42%;">
                <div class="invoice-title">Tax Invoice</div>
                <table class="meta-table">
                    <tr><td><strong>Invoice #:</strong> {{ $order->order_number }}</td></tr>
                    <tr><td><strong>Invoice Date:</strong> {{ optional($order->created_at)->format('d M Y') }}</td></tr>
                    <tr><td><strong>Order Status:</strong> <span class="badge">{{ ucfirst($order->status ?? '-') }}</span></td></tr>
                    <tr><td><strong>Payment:</strong> {{ ucfirst($order->payment_status ?? '-') }}</td></tr>
                    <tr><td><strong>Gateway:</strong> {{ ucfirst($order->payment_gateway ?? '-') }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <div class="info-box">
                    <p class="section-title">Bill To</p>
                    <strong>{{ $order->customer->name ?? $order->name ?? '-' }}</strong><br>
                    {{ $order->email ?: '-' }}<br>
                    {{ $order->phone ?: '-' }}
                </div>
            </td>
            <td>
                <div class="info-box">
                    <p class="section-title">Ship To</p>
                    <strong>{{ $order->name ?: ($order->customer->name ?? '-') }}</strong><br>
                    {{ $order->address ?: '-' }}<br>
                    {{ $order->city ?: '-' }}, {{ $order->state ?: '-' }} - {{ $order->pincode ?: '-' }}
                    @if($order->notes)
                        <br><em>Note: {{ $order->notes }}</em>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 28%;">Product</th>
                <th style="width: 12%;">SKU</th>
                <th style="width: 14%;" class="text-right">Unit</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 12%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                @php
                    $gstRate = (float) ($item->gst_rate ?? $item->product->tax_rate ?? $item->product->gst_rate ?? 0);
                    $lineTax = (float) ($item->tax_amount ?? 0);
                    $lineTax = $lineTax > 0 ? $lineTax : ((float) $item->subtotal * $gstRate / 100);
                    $lineShipping = (float) ($item->shipping_charge ?? 0) * (int) $item->quantity;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="product-name">{{ $item->product_name }}</div>
                        <div class="variation">Variation: {{ $item->variation_name ?: '-' }}</div>
                    </td>
                    <td>{{ $item->sku ?: '-' }}</td>
                    <td class="text-right">Rs. {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-center">{{ number_format($item->quantity) }}</td>
                    <td class="text-right">Rs. {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-wrap">
        <div class="payment-box">
            <p class="section-title">Payment Details</p>
            Gateway: {{ ucfirst($order->payment_gateway ?? '-') }}<br>
            Payment ID: {{ $order->razorpay_payment_id ?? $order->cashfree_payment_id ?? '-' }}<br>
            Shiprocket Order ID: {{ $order->shiprocket_order_id ?? '-' }}
        </div>
        <table class="totals-table">
            <tr><td>Items Subtotal</td><td class="text-right">Rs. {{ number_format($subtotal, 2) }}</td></tr>
            <tr><td>Shipping</td><td class="text-right">Rs. {{ number_format($shipping, 2) }}</td></tr>
            <tr><td>GST / Tax</td><td class="text-right">Rs. {{ number_format($tax, 2) }}</td></tr>
            <tr><td>Discount</td><td class="text-right">Rs. {{ number_format($discount, 2) }}</td></tr>
            <tr class="grand"><td>Grand Total</td><td class="text-right">Rs. {{ number_format($grandTotal, 2) }}</td></tr>
        </table>
    </div>

    <div class="footer">
        Thank you for your order. This is a computer generated invoice and does not require a signature.<br>
        For support, contact {{ $company['email'] }} or {{ $company['phone'] }}.
    </div>
</div>
</body>
</html>
