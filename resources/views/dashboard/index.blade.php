@extends('layout.layout')

@php
    $title = 'Bharat Biomer Admin Dashboard';
    $subTitle = 'Admin dashboard';
    $script = '<script>window.dashboardSalesLabels = ' . json_encode($salesLabels) . '; window.dashboardSalesData = ' . json_encode($salesData) . ';</script>'
        . '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';

    $dashboardCards = [
        ['label' => 'Total Customers', 'value' => number_format($totalCustomers), 'note' => number_format($newCustomersLast30) . ' new in last 30 days', 'tone' => 'primary'],
        ['label' => 'Total Orders', 'value' => number_format($totalOrders), 'note' => number_format($ordersLast30) . ' orders in last 30 days', 'tone' => 'info'],
        ['label' => 'Paid Orders', 'value' => number_format($paidOrders), 'note' => $paymentSuccessRate . '% payment success rate', 'tone' => 'success'],
        ['label' => 'Revenue', 'value' => 'Rs. ' . number_format($totalRevenue, 2), 'note' => 'Rs. ' . number_format($last30DaysRevenue, 2) . ' in last 30 days', 'tone' => 'warning'],
        ['label' => 'Average Order', 'value' => 'Rs. ' . number_format($averageOrderValue, 2), 'note' => number_format($pendingOrders) . ' pending or processing', 'tone' => 'secondary'],
        ['label' => 'Wishlist Adds', 'value' => number_format($wishlistCount), 'note' => number_format($wishlistCustomers) . ' customers with wishlist', 'tone' => 'danger'],
        ['label' => 'Active Products', 'value' => number_format($activeProducts), 'note' => number_format($totalProducts) . ' total products', 'tone' => 'info'],
        ['label' => 'Categories', 'value' => number_format($totalCategories), 'note' => 'Catalog structure', 'tone' => 'primary'],
    ];
@endphp

@section('content')
    <style>
        .admin-dashboard-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .admin-kpi-card {
            border: 1px solid #e7edf3;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
            height: 100%;
        }

        .admin-kpi-card .card-body {
            padding: 18px;
        }

        .admin-kpi-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .admin-kpi-value {
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .admin-kpi-note {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        .admin-kpi-strip {
            border-radius: 8px 8px 0 0;
            height: 4px;
        }

        .admin-kpi-strip.primary { background: #2563eb; }
        .admin-kpi-strip.info { background: #0891b2; }
        .admin-kpi-strip.success { background: #16a34a; }
        .admin-kpi-strip.warning { background: #d97706; }
        .admin-kpi-strip.secondary { background: #475569; }
        .admin-kpi-strip.danger { background: #dc2626; }
    </style>

    <div class="admin-dashboard-heading flex-wrap">
        <div>
            <h5 class="mb-1">Business Overview</h5>
            <p class="text-secondary-light mb-0">Orders, users, payments, and catalog performance at a glance.</p>
        </div>
        <a href="{{ route('dashboard.analytics') }}" class="btn btn-primary">
            Full Analytics Report
        </a>
    </div>

    <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1 g-3">
        @foreach($dashboardCards as $card)
            <div class="col">
                <div class="admin-kpi-card">
                    <div class="admin-kpi-strip {{ $card['tone'] }}"></div>
                    <div class="card-body">
                        <p class="admin-kpi-label">{{ $card['label'] }}</p>
                        <h6 class="admin-kpi-value">{{ $card['value'] }}</h6>
                        <p class="admin-kpi-note">{{ $card['note'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row gy-4 mt-1">
        <div class="col-xxl-12 col-xl-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h6 class="text-lg mb-1">Sales Statistic</h6>
                            <p class="text-secondary-light mb-0">Paid order revenue for the last 12 months.</p>
                        </div>
                        <span class="badge bg-success-focus text-success-main px-12 py-6">
                            Rs. {{ number_format($last30DaysRevenue, 2) }} last 30 days
                        </span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-12">
                        <h6 class="mb-0">Rs. {{ number_format($totalRevenue, 2) }}</h6>
                        <span class="text-sm fw-semibold rounded-pill bg-success-focus text-success-main border br-success px-8 py-4 line-height-1">
                            {{ $totalRevenue > 0 ? number_format(($last30DaysRevenue / $totalRevenue) * 100, 0) : 0 }}%
                        </span>
                        <span class="text-xs fw-medium">of total revenue in the last 30 days</span>
                    </div>
                    <div id="chart" class="pt-28 apexcharts-tooltip-style-1"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12 col-xl-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex flex-wrap align-items-center gap-1 justify-content-between mb-16">
                        <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center active" id="pills-customers-tab" data-bs-toggle="pill" data-bs-target="#pills-customers" type="button" role="tab" aria-controls="pills-customers" aria-selected="true">
                                    Latest Customers
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-orders" type="button" role="tab" aria-controls="pills-orders" aria-selected="false" tabindex="-1">
                                    Latest Orders
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-customers" role="tabpanel" aria-labelledby="pills-customers-tab" tabindex="0">
                            <div class="table-responsive scroll-sm">
                                <table class="table bordered-table sm-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Customer</th>
                                            <th scope="col">City</th>
                                            <th scope="col">Orders</th>
                                            <th scope="col">Wishlist</th>
                                            <th scope="col">Registered On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestCustomers as $customer)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-primary-light">{{ $customer->name }}</div>
                                                    <div class="text-sm text-secondary-light">{{ $customer->email }}</div>
                                                </td>
                                                <td>{{ $customer->city ?: '-' }}</td>
                                                <td>{{ number_format($customer->orders_count) }}</td>
                                                <td>{{ number_format($customer->wishlists_count) }}</td>
                                                <td>{{ optional($customer->created_at)->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">No registered customers found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-orders" role="tabpanel" aria-labelledby="pills-orders-tab" tabindex="0">
                            <div class="table-responsive scroll-sm">
                                <table class="table bordered-table sm-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order #</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Order Status</th>
                                            <th scope="col">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestOrders as $order)
                                            <tr>
                                                <td>#{{ $order->order_number }}</td>
                                                <td>{{ $order->customer->name ?? $order->name ?? '-' }}</td>
                                                <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                                                <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                                                <td>{{ ucfirst($order->status ?? '-') }}</td>
                                                <td>{{ ucfirst($order->payment_status ?? '-') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">No recent orders found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
