{{-- resources/views/dashboard/orders/show.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Order Detail';
    $subTitle = 'Order Detail';
    $script   = '';
@endphp

@section('content')

{{-- Back --}}
<div class="mb-3">
    <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline-secondary btn-sm">
        ← Back to Orders
    </a>
</div>

{{-- ── Header Card ── --}}

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-1">Order # {{ $order->order_number }}</h5>
                <span class="text-secondary-light text-sm">
                    Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                </span>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">

                {{-- ── Download Invoice Button ── --}}
                <a href="{{ route('dashboard.orders.invoice', $order->order_number) }}"
                   class="btn btn-success btn-sm px-16" target="_blank">
                    
                    Download Invoice
                </a>

                {{-- ── AJAX Status Changer ── --}}
                <label class="fw-semibold mb-0">Status:</label>
                <select id="statusSelect" class="form-select form-select-sm" style="width:160px;">
                    @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                <button id="saveStatusBtn" class="btn btn-primary btn-sm px-16">
                    Save
                </button>
                <span id="statusMsg" class="text-sm fw-medium" style="display:none;"></span>

            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ── LEFT: Items ── --}}
    <div class="col-12 col-lg-7">

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0"> Items Ordered ({{ $order->items->count() }})</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->product && $item->product->featured_image)
                                            <img src="{{ Storage::url($item->product->featured_image) }}"
                                                 style="width:36px;height:36px;object-fit:contain;border-radius:6px;background:#f5f5f5;padding:3px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-neutral-200 rounded"
                                                 style="width:36px;height:36px;font-size:1rem;"></div>
                                        @endif
                                        <div>
                                            <div class="fw-medium text-sm">{{ $item->product_name }}</div>
                                            @if($item->variation_name)
                                                <div class="text-xs text-secondary-light">{{ $item->variation_name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><code class="text-sm">{{ $item->sku ?? '—' }}</code></td>
                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-semibold text-success-main">₹{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Subtotal</td>
                                <td class="fw-bold">₹{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-semibold text-success-main">Shipping</td>
                                <td class="fw-semibold text-success-main">Free</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold" style="font-size:1rem;">Total</td>
                                <td class="fw-bold text-success-main" style="font-size:1rem;">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- ── RIGHT: Customer + Shipping ── --}}
    <div class="col-12 col-lg-5">

        {{-- Customer Info --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0"> Customer</h6>
            </div>
            <div class="card-body">
                @if($order->customer)
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="w-40-px h-40-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center fw-bold text-primary-600" style="min-width:40px;">
                        {{ strtoupper(substr($order->customer->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $order->customer->name }}</div>
                        <div class="text-sm text-secondary-light">{{ $order->customer->email }}</div>
                    </div>
                </div>
                <a href="{{ route('dashboard.customers.show', $order->customer->id) }}"
                   class="btn btn-outline-primary btn-sm w-100">
                    View Customer Profile →
                </a>
                @else
                    <p class="text-muted mb-0">Customer not found.</p>
                @endif
            </div>
        </div>

        {{-- Shipping Address --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0"> Shipping Address</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-secondary-light fw-medium" style="width:80px;">Name</td>
                        <td class="fw-medium">{{ $order->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary-light fw-medium">Phone</td>
                        <td class="fw-medium">{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary-light fw-medium">Email</td>
                        <td class="fw-medium">{{ $order->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary-light fw-medium">Address</td>
                        <td class="fw-medium">{{ $order->address }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary-light fw-medium">City</td>
                        <td class="fw-medium">{{ $order->city }}, {{ $order->state }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary-light fw-medium">PIN</td>
                        <td class="fw-medium">{{ $order->pincode }}</td>
                    </tr>
                    @if($order->notes)
                    <tr>
                        <td class="text-secondary-light fw-medium">Notes</td>
                        <td class="fw-medium">{{ $order->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('saveStatusBtn').addEventListener('click', function () {
    const btn       = this;
    const status    = document.getElementById('statusSelect').value;
    const msg       = document.getElementById('statusMsg');
    const orderNum  = '{{ $order->order_number }}';

    btn.disabled    = true;
    btn.textContent = 'Saving...';

    fetch(`/dashboard/orders/${orderNum}/status`, {
        method : 'PATCH',
        headers: {
            'Content-Type' : 'application/json',
            'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
            'Accept'       : 'application/json',
        },
        body: JSON.stringify({ status })
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(d => {
        btn.disabled    = false;
        btn.textContent = 'Save';
        msg.style.display = 'inline';
        msg.style.color   = '#198754';
        msg.textContent   = '✓ ' + d.message;
        setTimeout(() => msg.style.display = 'none', 3000);
    })
    .catch(err => {
        btn.disabled    = false;
        btn.textContent = 'Save';
        msg.style.display = 'inline';
        msg.style.color   = '#dc3545';
        msg.textContent   = '✗ Update failed: ' + err.message;
    });
});
</script>
@endpush