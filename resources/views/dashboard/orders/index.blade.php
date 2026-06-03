{{-- resources/views/dashboard/orders/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Orders';
    $subTitle = 'Orders';
    $script   = '<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const checkAll = document.getElementById("checkAll");
                        if (checkAll) {
                            checkAll.addEventListener("change", function () {
                                document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                            });
                        }
                    });
                 </script>';
@endphp

@section('content')

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h4 class="fw-semibold mb-1">{{ request('status') ? ucfirst(request('status')) . ' Orders' : 'All Orders' }}</h4>
            <p class="text-secondary-light mb-0">Manage orders, filter by status, and view order details quickly.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </nav>
    </div>

    <div class="row g-3 mb-24">
        @foreach([
            ['label' => 'Total orders', 'value' => $orders->total(), 'color' => 'primary', 'icon' => 'ri-shopping-bag-line'],
            ['label' => 'Pending', 'value' => $statusCounts['pending'], 'color' => 'warning', 'icon' => 'ri-time-line'],
            ['label' => 'Shipped', 'value' => $statusCounts['shipped'], 'color' => 'info', 'icon' => 'ri-truck-line'],
            ['label' => 'Delivered', 'value' => $statusCounts['delivered'], 'color' => 'success', 'icon' => 'ri-checkbox-circle-line'],
        ] as $stat)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-{{ $stat['color'] }}-focus text-{{ $stat['color'] }}-main fs-24" style="width:52px;height:52px;">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-secondary-light mb-1">{{ $stat['label'] }}</p>
                        <h5 class="fw-semibold mb-0">{{ $stat['value'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom bg-base">
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @php
                            $statuses = [
                                'all'        => 'All',
                                'pending'    => 'Pending',
                                'confirmed'  => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped'    => 'Shipped',
                                'delivered'  => 'Delivered',
                                'cancelled'  => 'Cancelled',
                            ];
                            $status = request('status', 'all');
                        @endphp
                        @foreach($statuses as $key => $label)
                        <a href="{{ route('dashboard.orders.index', array_merge(request()->query(), ['status' => $key === 'all' ? null : $key])) }}"
                           class="btn btn-sm {{ $status === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $label }}
                            <span class="badge bg-white text-primary-600 ms-1">{{ $statusCounts[$key] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('dashboard.orders.index') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-neutral-100 border-end-0"><i class="ri-search-line"></i></span>
                            <input type="search" name="search" class="form-control border-start-0" placeholder="Search by order # or customer" value="{{ request('search') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ordersTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Order #</th>
                            <th>Customer</th>
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
                            <td class="ps-3 text-secondary-light">{{ str_pad($orders->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="fw-semibold text-primary-600">{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-medium">{{ $order->name }}</div>
                                    <small class="text-secondary-light">{{ $order->phone }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="bg-neutral-200 text-neutral-600 px-12 py-3 rounded-pill fw-medium text-sm">
                                    {{ $order->items_count }} Items
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold text-success-main">₹{{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $badges = [
                                        'pending'    => 'bg-warning-focus text-warning-main',
                                        'confirmed'  => 'bg-success-focus text-success-main',
                                        'processing' => 'bg-info-focus text-info-main',
                                        'shipped'    => 'bg-primary-focus text-primary-600',
                                        'delivered'  => 'bg-success-focus text-success-main',
                                        'cancelled'  => 'bg-danger-focus text-danger-main',
                                    ];
                                    $cls = $badges[$order->status] ?? 'bg-neutral-200 text-neutral-600';
                                @endphp
                                <span class="{{ $cls }} px-12 py-3 rounded-pill fw-medium text-sm">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('dashboard.orders.show', $order->order_number) }}" class="btn btn-sm btn-outline-primary px-14 py-3">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-secondary-light">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="px-16 py-12 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <p class="text-secondary-light mb-0">Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</p>
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
