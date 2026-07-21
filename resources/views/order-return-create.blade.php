@extends('layout.frontlayout')
@section('title', 'Create Return Request – Bharat Biomer')

@section('content')
<div class="container my-4 order-return-create-page">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="orc__page-title">Create Return Request</h1>
            <p class="orc__page-subtitle">Request a return for order #{{ $order->order_number }}</p>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="orc__alert">
                <span class="orc__alert-icon"><iconify-icon icon="mdi:alert-circle-outline" class="icon"></iconify-icon></span>
                <span class="orc__alert-text">Returns are only accepted for delivered orders within 30 days of delivery. Refund amount cannot exceed the order total.</span>
            </div>

            <div class="orc__card">
                <div class="orc__header">
                    <div class="orc__order-num">Order #{{ $order->order_number }}</div>
                    <div class="orc__order-date">Ordered on {{ $order->created_at->format('d M Y') }} • Total: ₹{{ number_format($order->total_amount, 2) }}</div>
                </div>

                @php
                    $selectedOrderItemId = old('order_item_id', optional($order->items->first())->id);
                    $selectedOrderItem = $order->items->firstWhere('id', (int) $selectedOrderItemId) ?? $order->items->first();
                    $orderRefundMax = (float) ($order->net_amount ?? $order->total_amount ?? 0);
                    $selectedRefundMax = min((float) ($selectedOrderItem?->subtotal ?? 0), $orderRefundMax);
                @endphp

                <div class="orc__body">
                    <form action="{{ route('order-returns.store', $order->order_number) }}" method="POST" class="orc__form">
                        @csrf

                    <div class="orc__section">
                        <h3 class="orc__section-title">Select Item to Return</h3>
                        <div class="orc__items">
                            @foreach($order->items as $item)
                            <label class="orc__item">
                                <input type="radio" name="order_item_id" value="{{ $item->id }}"
                                       data-refund-amount="{{ number_format(min((float) $item->subtotal, $orderRefundMax), 2, '.', '') }}"
                                       {{ old('order_item_id') == $item->id || ($loop->first && !old('order_item_id')) ? 'checked' : '' }}>
                                @if($item->product?->featured_image)
                                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product_name }}" class="orc__item-img">
                                @else
                                    <div class="orc__item-img orc__item-img--placeholder"><iconify-icon icon="mdi:package-variant" class="icon"></iconify-icon></div>
                                @endif
                                <div class="orc__item-details">
                                    <div class="orc__item-name">{{ $item->product_name }}</div>
                                    <div class="orc__item-meta">
                                        Quantity: {{ $item->quantity }} • Price: ₹{{ number_format($item->price, 2) }}
                                        @if($item->variation_name)
                                            • {{ $item->variation_name }}
                                        @endif
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('order_item_id')
                            <div class="text-danger mt-2 orc__field-error">{{ $message }}</div>
                        @enderror
                    </div>

                        <div class="orc__section">
                            <h3 class="orc__section-title">Return Details</h3>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Return *</label>
                                <select name="reason" id="reason" class="form-select" required>
                                    <option value="">Select a reason</option>
                                    <option value="defective">Defective/Damaged Product</option>
                                    <option value="wrong_item">Wrong Item Received</option>
                                    <option value="not_as_described">Not as Described</option>
                                    <option value="damaged">Damaged During Shipping</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea name="description" id="description" class="form-control" rows="4"
                                          placeholder="Please provide details about why you're returning this order..."
                                          required minlength="10" maxlength="500">{{ old('description') }}</textarea>
                                <small class="text-muted">Minimum 10 characters, maximum 500 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="refund_amount" class="form-label">Refund Amount (₹) *</label>
                                <input type="number" name="refund_amount" id="refund_amount" class="form-control"
                                       step="0.01" min="0" max="{{ number_format($selectedRefundMax, 2, '.', '') }}"
                                       value="{{ old('refund_amount', number_format($selectedRefundMax, 2, '.', '')) }}" required>
                                <small class="text-muted">Maximum refund: ₹<span id="refundAmountMaxLabel">{{ number_format($selectedRefundMax, 2) }}</span></small>
                            </div>
                        </div>

                        <div class="orc__actions">
                            <a href="{{ route('orders.show', $order->order_number) }}" class="orc__btn orc__btn--outline">
                                ← Back to Order
                            </a>
                            <button type="submit" class="orc__btn orc__btn--primary">
                                Submit Return Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var refundInput = document.getElementById('refund_amount');
    var refundMaxLabel = document.getElementById('refundAmountMaxLabel');

    document.querySelectorAll('input[name="order_item_id"]').forEach(function (input) {
        input.addEventListener('change', function () {
            if (!refundInput || !refundMaxLabel) return;

            var amount = Number(input.dataset.refundAmount || 0);
            var formatted = amount.toFixed(2);

            refundInput.max = formatted;
            refundInput.value = formatted;
            refundMaxLabel.textContent = amount.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    });
});
</script>
@endsection
