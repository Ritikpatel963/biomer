@extends('layout.layout')

@php
    $title = 'Order Returns';
    $subTitle = 'Order Returns';
@endphp

@section('content')

<div class="d-flex flex-wrap gap-2 mb-4">
    @php
        $statuses = [
            'all' => ['label' => 'All', 'class' => 'btn-outline-secondary'],
            'pending' => ['label' => 'Pending', 'class' => 'btn-outline-warning'],
            'approved' => ['label' => 'Approved', 'class' => 'btn-outline-success'],
            'rejected' => ['label' => 'Rejected', 'class' => 'btn-outline-danger'],
            'refunded' => ['label' => 'Refunded', 'class' => 'btn-outline-info'],
        ];
        $currentStatus = request('status', 'all');
    @endphp

    @foreach($statuses as $key => $status)
        <a href="{{ route('dashboard.returns.index', array_merge(request()->query(), ['status' => $key === 'all' ? null : $key])) }}"
           class="btn btn-sm {{ $currentStatus === $key || ($key === 'all' && !request('status')) ? str_replace('outline-', '', $status['class']) : $status['class'] }}">
            {{ $status['label'] }}
            <span class="badge bg-white text-dark ms-1">{{ $statusCounts[$key] }}</span>
        </a>
    @endforeach
</div>

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">
            {{ request('status') ? ucfirst(request('status')) . ' Returns' : 'All Return Requests' }}
        </h5>

        <form method="GET" action="{{ route('dashboard.returns.index') }}" class="d-flex gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search order, customer, reason..."
                   style="width: 260px;">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            @if(request('search'))
                <a href="{{ route('dashboard.returns.index', request('status') ? ['status' => request('status')] : []) }}"
                   class="btn btn-outline-secondary btn-sm">Clear</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Reason</th>
                        <th>Refund</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $i => $return)
                        <tr>
                            <td>{{ str_pad($returns->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="fw-semibold text-primary-600">{{ $return->order->order_number ?? 'N/A' }}</div>
                                <div class="text-xs text-secondary-light">{{ $return->order?->name ?? 'Guest' }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $return->customer->name ?? $return->order?->name ?? 'N/A' }}</div>
                                <div class="text-xs text-secondary-light">{{ $return->customer->email ?? $return->order?->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $return->orderItem->product_name ?? 'Item removed' }}</div>
                                @if($return->orderItem?->variation_name)
                                    <div class="text-xs text-secondary-light">{{ $return->orderItem->variation_name }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm">{{ str_replace('_', ' ', ucfirst($return->reason)) }}</span>
                            </td>
                            <td class="fw-semibold text-success-main">
                                ₹{{ number_format((float) ($return->refund_amount ?? 0), 2) }}
                            </td>
                            <td>
                                @php
                                    $badges = [
                                        'pending' => 'bg-warning-focus text-warning-main',
                                        'approved' => 'bg-success-focus text-success-main',
                                        'rejected' => 'bg-danger-focus text-danger-main',
                                        'refunded' => 'bg-info-focus text-info-main',
                                    ];
                                @endphp
                                <span class="px-12 py-4 rounded-pill fw-medium text-sm {{ $badges[$return->status] ?? 'bg-neutral-200 text-neutral-600' }}">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td>{{ optional($return->requested_at ?? $return->created_at)->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('dashboard.returns.show', $return->id) }}"
                                   class="w-32-px h-32-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                   title="View Return">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">No return requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($returns->hasPages())
            <div class="p-3 d-flex justify-content-end">
                {{ $returns->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
