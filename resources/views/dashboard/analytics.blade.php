@extends('layout.layout')

@php
    $title = 'Analytics';
    $subTitle = 'Reports';
    $script = '<script>window.dashboardSalesLabels = ' . json_encode($salesLabels) . '; window.dashboardSalesData = ' . json_encode($salesData) . ';</script>';

    $summaryCards = [
        ['label' => 'Customers', 'value' => number_format($totalCustomers), 'note' => number_format($newCustomersLast30) . ' new in last 30 days', 'tone' => 'blue'],
        ['label' => 'Customers With Orders', 'value' => number_format($customersWithOrders), 'note' => number_format($customersWithoutOrders) . ' without orders', 'tone' => 'teal'],
        ['label' => 'Orders', 'value' => number_format($totalOrders), 'note' => number_format($ordersLast30) . ' in last 30 days', 'tone' => 'indigo'],
        ['label' => 'Paid Orders', 'value' => number_format($paidOrders), 'note' => $paymentSuccessRate . '% payment success', 'tone' => 'green'],
        ['label' => 'Revenue', 'value' => 'Rs. ' . number_format($totalRevenue, 2), 'note' => 'Rs. ' . number_format($last30DaysRevenue, 2) . ' last 30 days', 'tone' => 'amber'],
        ['label' => 'Invoices', 'value' => number_format($invoiceCount), 'note' => 'Rs. ' . number_format($invoiceValue, 2) . ' paid value', 'tone' => 'cyan'],
        ['label' => 'Wishlist', 'value' => number_format($wishlistCount), 'note' => number_format($wishlistCustomers) . ' customers', 'tone' => 'rose'],
        ['label' => 'Returns', 'value' => number_format($returnCount), 'note' => 'Refund and return requests', 'tone' => 'slate'],
        ['label' => 'Product Reviews', 'value' => number_format($productReviewCount), 'note' => 'All review statuses', 'tone' => 'violet'],
        ['label' => 'Blog Reviews', 'value' => number_format($blogReviewCount), 'note' => 'All review statuses', 'tone' => 'pink'],
        ['label' => 'Products', 'value' => number_format($totalProducts), 'note' => number_format($activeProducts) . ' active products', 'tone' => 'emerald'],
        ['label' => 'Avg. Order Value', 'value' => 'Rs. ' . number_format($averageOrderValue, 2), 'note' => number_format($pendingOrders) . ' pending orders', 'tone' => 'orange'],
    ];

    $exportSections = [
        'Summary',
        'Monthly Sales',
        'Customers',
        'Users By City',
        'Users By Audience',
        'Orders',
        'Invoices',
        'Order Status',
        'Payments',
        'Wishlist Summary',
        'Wishlist Details',
        'Products',
        'Low Stock',
        'Returns',
        'Product Reviews',
        'Blog Reviews',
        'Cart Tracking',
    ];
@endphp

@section('content')
    <style>
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .analytics-card {
            --metric-color: #2563eb;
            border: 1px solid #e5eaf0;
            border-radius: 8px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.96), #fff),
                linear-gradient(135deg, color-mix(in srgb, var(--metric-color) 12%, transparent), transparent 58%);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
            min-height: 138px;
            overflow: hidden;
            padding: 18px 18px 16px;
            position: relative;
        }

        .analytics-card::before {
            background: var(--metric-color);
            content: "";
            height: 4px;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
        }

        .analytics-card.blue { --metric-color: #2563eb; }
        .analytics-card.teal { --metric-color: #0f766e; }
        .analytics-card.indigo { --metric-color: #4f46e5; }
        .analytics-card.green { --metric-color: #16a34a; }
        .analytics-card.amber { --metric-color: #d97706; }
        .analytics-card.cyan { --metric-color: #0891b2; }
        .analytics-card.rose { --metric-color: #e11d48; }
        .analytics-card.slate { --metric-color: #475569; }
        .analytics-card.violet { --metric-color: #7c3aed; }
        .analytics-card.pink { --metric-color: #db2777; }
        .analytics-card.emerald { --metric-color: #059669; }
        .analytics-card.orange { --metric-color: #ea580c; }

        .analytics-card-top {
            align-items: center;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .analytics-label {
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        .analytics-chip {
            background: color-mix(in srgb, var(--metric-color) 12%, #fff);
            border: 1px solid color-mix(in srgb, var(--metric-color) 24%, #fff);
            border-radius: 999px;
            color: var(--metric-color);
            flex: 0 0 auto;
            font-size: 11px;
            font-weight: 800;
            line-height: 1;
            padding: 6px 8px;
        }

        .analytics-value {
            color: #0f172a;
            font-size: 26px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .analytics-note {
            color: #64748b;
            font-size: 13px;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .report-section {
            margin-top: 22px;
        }

        .report-section .card-header {
            background: #fff;
            border-bottom: 1px solid #eef2f7;
            padding: 18px 22px;
        }

        .report-section .card-header h6 {
            margin: 0;
        }

        .analytics-export-panel {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
            margin-bottom: 20px;
            padding: 18px;
        }

        .analytics-export-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-top: 14px;
        }

        .analytics-export-option {
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: flex;
            gap: 10px;
            min-height: 42px;
            padding: 10px 12px;
        }

        .analytics-export-option label {
            color: #334155;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        .analytics-export-option input {
            flex: 0 0 auto;
        }

        @media (max-width: 1199px) {
            .analytics-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .analytics-export-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 767px) {
            .analytics-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            .analytics-export-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        }
    </style>

  

    <form action="{{ route('dashboard.analytics.export') }}" method="GET" class="analytics-export-panel">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h6 class="mb-1">Download Report</h6>
                <p class="text-secondary-light mb-0">Choose which sheets should be included in the export.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllReports">Select All</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="clearReports">Clear</button>
                <button type="submit" class="btn btn-primary btn-sm">Download Selected</button>
            </div>
        </div>

        <div class="analytics-export-grid">
            @foreach($exportSections as $section)
                <div class="analytics-export-option">
                    <input class="form-check-input analytics-export-checkbox" type="checkbox" name="sections[]" value="{{ $section }}" id="export-{{ \Illuminate\Support\Str::slug($section) }}" checked>
                    <label for="export-{{ \Illuminate\Support\Str::slug($section) }}">{{ $section }}</label>
                </div>
            @endforeach
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxes = document.querySelectorAll('.analytics-export-checkbox');
                const selectAll = document.getElementById('selectAllReports');
                const clearAll = document.getElementById('clearReports');

                selectAll?.addEventListener('click', function () {
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = true;
                    });
                });

                clearAll?.addEventListener('click', function () {
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = false;
                    });
                });
            });
        </script>
    @endpush

    <div class="analytics-grid">
        @foreach($summaryCards as $card)
            <div class="analytics-card {{ $card['tone'] }}">
                <div class="analytics-card-top">
                    <p class="analytics-label">{{ $card['label'] }}</p>
                    <span class="analytics-chip">Report</span>
                </div>
                <h6 class="analytics-value">{{ $card['value'] }}</h6>
                <p class="analytics-note">{{ $card['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="row report-section gy-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Users From Cities</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr>
                                    <th>City</th>
                                    <th class="text-end">Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usersByCity as $row)
                                    <tr>
                                        <td>{{ $row->label }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">No city data found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Audience Preferences</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Audience</th>
                                    <th class="text-end">Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usersByAudience as $row)
                                    <tr>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">No audience data found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-section">
        <div class="card-header">
            <h6>Customer Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table sm-table admin-data-table mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th class="text-end">Orders</th>
                            <th class="text-end">Order Value</th>
                            <th class="text-end">Wishlist</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCustomers as $customer)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $customer->name }}</div>
                                    <div class="text-sm text-secondary-light">{{ $customer->email }}</div>
                                </td>
                                <td>{{ $customer->phone ?: '-' }}</td>
                                <td>{{ $customer->city ?: '-' }}</td>
                                <td class="text-end">{{ number_format($customer->orders_count) }}</td>
                                <td class="text-end">Rs. {{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}</td>
                                <td class="text-end">{{ number_format($customer->wishlists_count) }}</td>
                                <td>{{ optional($customer->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4">No customers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row report-section gy-4">
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Order Status</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Status</th><th class="text-end">Orders</th><th class="text-end">Value</th></tr>
                            </thead>
                            <tbody>
                                @forelse($orderStatusBreakdown as $row)
                                    <tr>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                        <td class="text-end">Rs. {{ number_format($row->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">No orders found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Payment Status</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Status</th><th class="text-end">Orders</th><th class="text-end">Value</th></tr>
                            </thead>
                            <tbody>
                                @forelse($paymentStatusBreakdown as $row)
                                    <tr>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                        <td class="text-end">Rs. {{ number_format($row->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">No payments found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Payment Gateway</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Gateway</th><th class="text-end">Orders</th><th class="text-end">Value</th></tr>
                            </thead>
                            <tbody>
                                @forelse($paymentGatewayBreakdown as $row)
                                    <tr>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                        <td class="text-end">Rs. {{ number_format($row->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">No gateway data found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-section">
        <div class="card-header">
            <h6>Order And Invoice Details</h6>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#analytics-orders" type="button" role="tab">Orders</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#analytics-invoices" type="button" role="tab">Invoices</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="analytics-orders" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Gateway</th>
                                    <th class="text-end">Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->customer->name ?? $order->name ?? '-' }}</td>
                                        <td>{{ $order->city ?: '-' }}</td>
                                        <td>{{ ucfirst($order->status ?? '-') }}</td>
                                        <td>{{ ucfirst($order->payment_status ?? '-') }}</td>
                                        <td>{{ ucfirst($order->payment_gateway ?? '-') }}</td>
                                        <td class="text-end">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center py-4">No orders found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="analytics-invoices" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Payment Id</th>
                                    <th>Gateway</th>
                                    <th class="text-end">Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->customer->name ?? $order->name ?? '-' }}</td>
                                        <td>{{ $order->razorpay_payment_id ?? $order->cashfree_payment_id ?? '-' }}</td>
                                        <td>{{ ucfirst($order->payment_gateway ?? '-') }}</td>
                                        <td class="text-end">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">No paid invoices found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row report-section gy-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Top Selling Products</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Sales</th></tr>
                            </thead>
                            <tbody>
                                @forelse($topSellingProducts as $product)
                                    <tr>
                                        <td>{{ $product->product_name ?: 'Product #' . $product->product_id }}</td>
                                        <td class="text-end">{{ number_format($product->total_quantity) }}</td>
                                        <td class="text-end">Rs. {{ number_format($product->total_sales, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">No sold products found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Wishlist Products</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Product</th><th class="text-end">Wishlist Adds</th></tr>
                            </thead>
                            <tbody>
                                @forelse($topWishlistProducts as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'Product #' . $item->product_id }}</td>
                                        <td class="text-end">{{ number_format($item->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">No wishlist data found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        {{ $cartReport['message'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row report-section gy-4">
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Low Stock Products</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Product</th><th class="text-end">Stock</th></tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-end">{{ number_format($product->stock_quantity) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">No low stock products found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Return Status</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Status</th><th class="text-end">Returns</th><th class="text-end">Refund</th></tr>
                            </thead>
                            <tbody>
                                @forelse($returnStatusBreakdown as $row)
                                    <tr>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                        <td class="text-end">Rs. {{ number_format($row->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">No returns found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6>Review Status</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table sm-table admin-data-table mb-0">
                            <thead>
                                <tr><th>Type</th><th>Status</th><th class="text-end">Count</th></tr>
                            </thead>
                            <tbody>
                                @forelse($reviewStatusBreakdown['product'] as $row)
                                    <tr>
                                        <td>Product</td>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                @forelse($reviewStatusBreakdown['blog'] as $row)
                                    <tr>
                                        <td>Blog</td>
                                        <td>{{ ucfirst($row->label) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                @if($reviewStatusBreakdown['product']->isEmpty() && $reviewStatusBreakdown['blog']->isEmpty())
                                    <tr><td colspan="3" class="text-center py-4">No reviews found.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
