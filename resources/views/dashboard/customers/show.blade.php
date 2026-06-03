{{-- resources/views/dashboard/customers/show.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Customer Detail';
    $subTitle = 'Customer Detail';
@endphp

@section('content')

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h4 class="fw-semibold mb-1">Customer Detail</h4>
            <p class="text-secondary-light mb-0">View and manage customer profile information, order history, and account activity.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard.customers.index') }}">Customers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Customer Detail</li>
            </ol>
        </nav>
    </div>

    <div class="row g-3 mb-24">
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-primary-100 d-flex align-items-center justify-content-center text-primary-600" style="width:72px;height:72px;font-size:1.5rem;">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1">{{ $customer->name }}</h5>
                            <p class="text-secondary-light mb-0">Customer ID #{{ $customer->id }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Contact Information</h6>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="ri-mail-line text-primary-600 fs-18"></i>
                            <div>
                                <p class="text-secondary-light mb-1">Email</p>
                                <p class="mb-0">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="ri-phone-line text-primary-600 fs-18"></i>
                            <div>
                                <p class="text-secondary-light mb-1">Phone</p>
                                <p class="mb-0">{{ $customer->phone ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-calendar-line text-primary-600 fs-18"></i>
                            <div>
                                <p class="text-secondary-light mb-1">Joined</p>
                                <p class="mb-0">{{ $customer->created_at?->format('d M Y') ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-semibold mb-3">Address</h6>
                        <p class="mb-1">{{ $customer->address ?: 'No saved address' }}</p>
                        @if($customer->city || $customer->state || $customer->pincode || $customer->country)
                            <p class="text-secondary-light mb-0">{{ collect([$customer->city, $customer->state, $customer->pincode, $customer->country])->filter()->implode(', ') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-100 text-primary-600 mb-3" style="width:52px;height:52px;">
                                <i class="ri-shopping-bag-line fs-20"></i>
                            </span>
                            <p class="text-secondary-light mb-1">Total Orders</p>
                            <h4 class="fw-semibold mb-0">{{ $totalOrders }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-focus text-success-main mb-3" style="width:52px;height:52px;">
                                <i class="ri-currency-line fs-20"></i>
                            </span>
                            <p class="text-secondary-light mb-1">Total Spent</p>
                            <h4 class="fw-semibold mb-0">₹{{ number_format($totalSpent, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info-focus text-info-main mb-3" style="width:52px;height:52px;">
                                <i class="ri-user-follow-line fs-20"></i>
                            </span>
                            <p class="text-secondary-light mb-1">Audience Type</p>
                            <h4 class="fw-semibold mb-0">{{ $customer->audience_type ? ucfirst($customer->audience_type) : '—' }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning-focus text-warning-main mb-3" style="width:52px;height:52px;">
                                <i class="ri-calendar-event-line fs-20"></i>
                            </span>
                            <p class="text-secondary-light mb-1">Joined</p>
                            <h4 class="fw-semibold mb-0">{{ $customer->created_at?->format('d M Y') ?? '—' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom bg-base">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h5 class="card-title mb-1">Order History</h5>
                    <p class="text-secondary-light mb-0">Recent orders placed by this customer.</p>
                </div>
                <a href="{{ route('dashboard.customers.index') }}" class="btn btn-sm btn-secondary">← Back to Customers</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-data-table" data-page-length="10" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Order #</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $order)
                        <tr>
                            <td class="ps-3 text-secondary-light">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="fw-semibold text-primary-600">#{{ $order->order_number ?? $order->id }}</span>
                            </td>
                            <td>
                                <span class="bg-neutral-200 text-neutral-600 px-12 py-2 rounded-pill fw-medium text-sm">{{ $order->items->count() }} items</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-success-main">₹{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning-focus text-warning-main',
                                        'confirmed' => 'bg-info-focus text-info-main',
                                        'processing' => 'bg-primary-focus text-primary-600',
                                        'shipped' => 'bg-purple-focus text-purple',
                                        'delivered' => 'bg-success-focus text-success-main',
                                        'cancelled' => 'bg-danger-focus text-danger-main',
                                    ];
                                    $statusClass = $statusClasses[$order->status] ?? 'bg-neutral-200 text-neutral-600';
                                @endphp
                                <span class="{{ $statusClass }} px-12 py-2 rounded-pill fw-medium text-sm">{{ ucfirst($order->status ?? 'N/A') }}</span>
                            </td>
                            <td>{{ $order->created_at?->format('d M Y') ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('dashboard.orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary px-14 py-3">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary-light">No orders found for this customer.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
