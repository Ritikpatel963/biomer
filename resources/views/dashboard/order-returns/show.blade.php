@extends('layout.layout')

@php
    $title = 'Return Detail';
    $subTitle = 'Return Detail';
@endphp

@section('content')

<div class="mb-3">
    <a href="{{ route('dashboard.returns.index') }}" class="btn btn-outline-secondary btn-sm">
        ← Back to Returns
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1">Return #{{ $return->id }}</h5>
                <div class="text-secondary-light text-sm">
                    Order #{{ $return->order->order_number ?? 'N/A' }} • Requested {{ optional($return->requested_at ?? $return->created_at)->format('d M Y, h:i A') }}
                </div>
            </div>

            @php
                $badges = [
                    'pending' => 'bg-warning-focus text-warning-main',
                    'approved' => 'bg-success-focus text-success-main',
                    'rejected' => 'bg-danger-focus text-danger-main',
                    'refunded' => 'bg-info-focus text-info-main',
                ];
            @endphp
            <span class="px-16 py-8 rounded-pill fw-semibold {{ $badges[$return->status] ?? 'bg-neutral-200 text-neutral-600' }}">
                {{ ucfirst($return->status) }}
            </span>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Returned Item</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    @if($return->orderItem?->product?->featured_image)
                        <img src="{{ Storage::url($return->orderItem->product->featured_image) }}"
                             alt="{{ $return->orderItem->product_name }}"
                             style="width:72px;height:72px;object-fit:contain;border-radius:10px;background:#f8f9fa;padding:6px;">
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded bg-neutral-200"
                             style="width:72px;height:72px;">Item</div>
                    @endif

                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $return->orderItem->product_name ?? 'Item removed' }}</h6>
                        @if($return->orderItem?->variation_name)
                            <div class="text-secondary-light text-sm mb-1">{{ $return->orderItem->variation_name }}</div>
                        @endif
                        <div class="text-sm">SKU: <span class="fw-medium">{{ $return->orderItem->sku ?? 'N/A' }}</span></div>
                        <div class="text-sm">Qty: <span class="fw-medium">{{ $return->orderItem->quantity ?? 1 }}</span></div>
                        <div class="text-sm">Item subtotal: <span class="fw-medium text-success-main">₹{{ number_format((float) ($return->orderItem->subtotal ?? 0), 2) }}</span></div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-secondary-light text-sm mb-1">Reason</div>
                        <div class="fw-medium">{{ str_replace('_', ' ', ucfirst($return->reason)) }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-secondary-light text-sm mb-1">Requested Refund</div>
                        <div class="fw-medium text-success-main">₹{{ number_format((float) ($return->refund_amount ?? 0), 2) }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-secondary-light text-sm mb-1">Customer Notes</div>
                        <div class="fw-medium">{{ $return->description ?: 'No notes added.' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer & Order</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-secondary-light text-sm mb-1">Customer</div>
                        <div class="fw-medium">{{ $return->customer->name ?? $return->order?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-secondary-light text-sm mb-1">Email</div>
                        <div class="fw-medium">{{ $return->customer->email ?? $return->order?->email ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-secondary-light text-sm mb-1">Phone</div>
                        <div class="fw-medium">{{ $return->order->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-secondary-light text-sm mb-1">Order Total</div>
                        <div class="fw-medium">₹{{ number_format((float) ($return->order->total_amount ?? 0), 2) }}</div>
                    </div>
                </div>

                @if($return->order)
                    <div class="mt-3">
                        <a href="{{ route('dashboard.orders.show', $return->order->order_number) }}" class="btn btn-outline-primary btn-sm">
                            View Full Order
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Update Return</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.returns.update', $return->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending', 'approved', 'rejected', 'refunded'] as $status)
                                <option value="{{ $status }}" {{ old('status', $return->status) === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Refund Amount</label>
                        <input type="number" step="0.01" min="0" name="refund_amount"
                               class="form-control"
                               value="{{ old('refund_amount', $return->refund_amount) }}"
                               placeholder="Enter refund amount">
                        <small class="text-secondary-light">
                            Max item subtotal: ₹{{ number_format((float) ($return->orderItem->subtotal ?? 0), 2) }}
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Return Tracking Number</label>
                        <input type="text" name="return_tracking_number" class="form-control"
                               value="{{ old('return_tracking_number', $return->return_tracking_number) }}"
                               placeholder="Optional tracking number">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Admin Notes</label>
                        <textarea name="admin_notes" rows="5" class="form-control"
                                  placeholder="Add review notes for your team or customer support">{{ old('admin_notes', $return->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Return Update</button>
                </form>

                <hr>

                <div class="row g-3 text-sm">
                    <div class="col-12">
                        <div class="text-secondary-light mb-1">Requested At</div>
                        <div class="fw-medium">{{ optional($return->requested_at ?? $return->created_at)->format('d M Y, h:i A') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-secondary-light mb-1">Approved At</div>
                        <div class="fw-medium">{{ optional($return->approved_at)->format('d M Y, h:i A') ?? 'Not approved yet' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-secondary-light mb-1">Refunded At</div>
                        <div class="fw-medium">{{ optional($return->refunded_at)->format('d M Y, h:i A') ?? 'Not refunded yet' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
