{{-- resources/views/dashboard/invoices/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Invoices';
    $subTitle = 'Invoices';
@endphp

@section('content')

@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">All Invoices</h5>
        <span class="badge bg-primary-100 text-primary-600 px-16 py-6 fw-medium">
            {{ $orders->total() }} Total
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" id="invoiceTable" data-no-sort-targets="0,7" style="width:100%">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        {{-- S.L --}}
                        <td>{{ str_pad($orders->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>

                        {{-- Invoice Number --}}
                        <td>
                            <span class="fw-semibold text-primary-600">
                                #{{ $order->order_number }}
                            </span>
                        </td>

                        {{-- Customer --}}
                        <td>
                            @if($order->customer)
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-32-px h-32-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center fw-bold text-primary-600"
                                     style="font-size:0.8rem; min-width:32px;">
                                    {{ strtoupper(substr($order->customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-medium text-sm">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-secondary-light">{{ $order->customer->email }}</div>
                                </div>
                            </div>
                            @else
                                <span class="text-muted">{{ $order->name }}</span>
                            @endif
                        </td>

                        {{-- Items Count --}}
                        <td>
                            <span class="bg-primary-focus text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ $order->items->count() }} item(s)
                            </span>
                        </td>

                        {{-- Amount --}}
                        <td>
                            <span class="fw-semibold text-success-main">
                                Rs. {{ number_format($order->total_amount, 2) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            @php
                                $statusColors = [
                                    'pending'    => 'bg-warning-focus text-warning-main',
                                    'confirmed'  => 'bg-info-focus text-info-main',
                                    'processing' => 'bg-primary-focus text-primary-600',
                                    'shipped'    => 'bg-purple-focus text-purple',
                                    'delivered'  => 'bg-success-focus text-success-main',
                                    'cancelled'  => 'bg-danger-focus text-danger-main',
                                ];
                                $colorClass = $statusColors[$order->status] ?? 'bg-secondary-focus text-secondary';
                            @endphp
                            <span class="px-12 py-4 rounded-pill fw-medium text-sm {{ $colorClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td>{{ $order->created_at->format('d M Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                {{-- View Order --}}
                                <a href="{{ route('invoicePreview', $order->order_number) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Preview Invoice">
                                    Preview
                                </a>

                                {{-- Download Invoice --}}
                                <a href="{{ route('dashboard.orders.invoice', $order->order_number) }}"
                                   class="btn btn-sm btn-outline-success"
                                   title="Download Invoice" target="_blank">
                                    Download
                                </a>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No invoices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="d-flex justify-content-center py-3">
            {{ $orders->links() }}
        </div>
        @endif

    </div>
</div>

@endsection
