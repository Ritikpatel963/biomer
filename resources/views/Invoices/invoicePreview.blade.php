@extends('layout.layout')

@php
    $title = 'Invoice Preview';
    $subTitle = 'Invoice Preview';
    $script = '<script>
        function printInvoice() {
            var printContents = document.getElementById("invoice").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>';

    $subtotal = $order->items->sum('subtotal');
    $shipping = (float) ($order->shipping_amount ?? 0);
    $discount = (float) ($order->discount_amount ?? 0);
    $itemTax = (float) $order->items->sum(function ($item) {
        $rate = (float) ($item->gst_rate ?? $item->product->tax_rate ?? $item->product->gst_rate ?? 0);
        $storedTax = (float) ($item->tax_amount ?? 0);
        return $storedTax > 0 ? $storedTax : ((float) $item->subtotal * $rate / 100);
    });
    $tax = (float) ($order->tax_amount ?? 0);
    $tax = $tax > 0 ? $tax : $itemTax;
@endphp

@section('content')
    <style>
        .invoice-preview-shell {
            background: #fff;
            border: 1px solid #e7edf3;
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            margin: 0 auto;
            max-width: 960px;
            overflow: hidden;
        }

        .invoice-preview-head {
            border-bottom: 1px solid #eef2f7;
            display: flex;
            gap: 20px;
            justify-content: space-between;
            padding: 28px;
        }

        .invoice-preview-box {
            background: #f8fafc;
            border: 1px solid #e7edf3;
            border-radius: 8px;
            padding: 18px;
        }

        .invoice-preview-total {
            background: #0f766e;
            color: #fff;
            font-weight: 800;
        }

        .invoice-company-meta {
            color: #64748b;
            line-height: 1.6;
            margin-top: 10px;
        }

        @media print {
            .sidebar, .navbar-header, .dashboard-main-body > .d-flex, .card-header, .btn {
                display: none !important;
            }
            .dashboard-main-body {
                margin: 0 !important;
                padding: 0 !important;
            }
            .invoice-preview-shell {
                border: 0;
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h5 class="card-title mb-1">Invoice #{{ $order->order_number }}</h5>
                <p class="text-secondary-light mb-0">{{ optional($order->created_at)->format('d M Y') }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.orders.invoice', $order->order_number) }}" class="btn btn-sm btn-outline-success" target="_blank">Download</a>
                <a href="{{ route('dashboard.orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary">View Order</a>
                <button type="button" class="btn btn-sm btn-primary" onclick="printInvoice()">Print</button>
            </div>
        </div>

        <div class="card-body py-32" id="invoice">
            <div class="invoice-preview-shell">
                <div class="invoice-preview-head flex-wrap">
                    <div>
                        <img src="{{ $company['logo_url'] }}" alt="{{ $company['name'] }}" style="max-width: 170px; max-height: 76px;">
                        <div class="invoice-company-meta">
                            <strong class="text-primary-light">{{ $company['name'] }}</strong><br>
                            {{ $company['tagline'] }}<br>
                            {{ $company['address'] }}<br>
                            {{ $company['email'] }} | {{ $company['phone'] }}<br>
                            GSTIN: {{ $company['gstin'] }}
                        </div>
                    </div>
                    <div class="text-sm-end">
                        <h2 class="mb-2">Tax Invoice</h2>
                        <p class="mb-1"><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                        <p class="mb-1"><strong>Order Status:</strong> {{ ucfirst($order->status ?? '-') }}</p>
                        <p class="mb-0"><strong>Payment:</strong> {{ ucfirst($order->payment_status ?? '-') }}</p>
                        <p class="mb-0"><strong>Gateway:</strong> {{ ucfirst($order->payment_gateway ?? '-') }}</p>
                    </div>
                </div>

                <div class="p-28">
                    <div class="row g-3 mb-24">
                        <div class="col-md-6">
                            <div class="invoice-preview-box h-100">
                                <h6 class="mb-10">Bill To</h6>
                                <p class="mb-1 fw-semibold">{{ $order->customer->name ?? $order->name ?? '-' }}</p>
                                <p class="mb-1">{{ $order->email ?: '-' }}</p>
                                <p class="mb-0">{{ $order->phone ?: '-' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="invoice-preview-box h-100">
                                <h6 class="mb-10">Ship To</h6>
                                <p class="mb-1">{{ $order->address ?: '-' }}</p>
                                <p class="mb-1">{{ $order->city ?: '-' }}, {{ $order->state ?: '-' }}</p>
                                <p class="mb-0">PIN: {{ $order->pincode ?: '-' }}</p>
                                @if($order->notes)
                                    <p class="mb-0 mt-2"><em>Note: {{ $order->notes }}</em></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table bordered-table sm-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Variation</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    @php
                                        $gstRate = (float) ($item->gst_rate ?? $item->product->tax_rate ?? $item->product->gst_rate ?? 0);
                                        $lineTax = (float) ($item->tax_amount ?? 0);
                                        $lineTax = $lineTax > 0 ? $lineTax : ((float) $item->subtotal * $gstRate / 100);
                                        $lineShipping = (float) ($item->shipping_charge ?? 0) * (int) $item->quantity;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->sku ?: '-' }}</td>
                                        <td>{{ $item->variation_name ?: '-' }}</td>
                                        <td class="text-end">Rs. {{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->quantity) }}</td>
                                        <td class="text-end">Rs. {{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No invoice items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end mt-24">
                        <div class="col-lg-6">
                            <div class="invoice-preview-box mb-3">
                                <h6 class="mb-10">Payment Details</h6>
                                <p class="mb-1">Gateway: {{ ucfirst($order->payment_gateway ?? '-') }}</p>
                                <p class="mb-1">Payment ID: {{ $order->razorpay_payment_id ?? $order->cashfree_payment_id ?? '-' }}</p>
                                <p class="mb-0">Shiprocket Order ID: {{ $order->shiprocket_order_id ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <table class="table sm-table mb-0">
                                <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-end">Rs. {{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping</td>
                                        <td class="text-end">Rs. {{ number_format($shipping, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td class="text-end">Rs. {{ number_format($tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td class="text-end">Rs. {{ number_format($discount, 2) }}</td>
                                    </tr>
                                    <tr class="invoice-preview-total">
                                        <td>Total</td>
                                        <td class="text-end">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <p class="text-center text-secondary-light mt-32 mb-0">
                        Thank you for your order. This is a computer generated invoice.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
