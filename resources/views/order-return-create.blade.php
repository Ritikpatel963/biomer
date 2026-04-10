@extends('layout.frontlayout')
@section('title', 'Create Return Request – Bharat Biomer')

@push('styles')
<style>
* { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }

.orc__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.orc__header {
    background: #f4faf0;
    border-bottom: 1px solid #e8f0e4;
    padding: 1.25rem 1.5rem;
}
.orc__order-num { font-size: 1.1rem; font-weight: 800; color: #1a2e1a; margin-bottom: 0.25rem; }
.orc__order-date { font-size: 0.85rem; color: #6b7c6b; }

.orc__body { padding: 1.5rem; }
.orc__section { margin-bottom: 2rem; }
.orc__section-title { font-size: 1rem; font-weight: 700; color: #1a2e1a; margin-bottom: 1rem; }

.orc__items { display: grid; gap: 1rem; }
.orc__item {
    display: flex; align-items: center; gap: 1rem;
    padding: 1rem; background: #f9fbf8; border-radius: 10px;
    border: 1px solid #e8f0e4;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.orc__item input[type="radio"] { margin-top: 0.2rem; }
.orc__item:has(input:checked) {
    border-color: #2d7a45;
    background: #eef8f1;
    box-shadow: 0 0 0 3px rgba(45,122,69,0.08);
}
.orc__item-img {
    width: 60px; height: 60px; object-fit: contain;
    border-radius: 8px; background: #fff; border: 1px solid #e8f0e4;
}
.orc__item-details { flex: 1; }
.orc__item-name { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; margin-bottom: 0.25rem; }
.orc__item-meta { font-size: 0.8rem; color: #6b7c6b; }

.orc__form .form-label { font-size: 0.9rem; font-weight: 600; color: #1a2e1a; margin-bottom: 0.5rem; }
.orc__form .form-select,
.orc__form .form-control {
    border: 1.5px solid #e8f0e4; border-radius: 8px;
    padding: 0.75rem; font-size: 0.9rem;
}
.orc__form .form-select:focus,
.orc__form .form-control:focus {
    border-color: #2d7a45; box-shadow: 0 0 0 0.2rem rgba(45,122,69,0.1);
}

.orc__actions {
    display: flex; gap: 1rem; justify-content: flex-end;
    padding-top: 1rem; border-top: 1px solid #f0f5ee;
}
.orc__btn {
    padding: 0.75rem 2rem; border-radius: 8px;
    font-size: 0.9rem; font-weight: 600; border: none;
    text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    transition: all 0.2s;
}
.orc__btn--primary { background: #2d7a45; color: #fff; }
.orc__btn--primary:hover { background: #245e36; color: #fff; }
.orc__btn--outline { background: transparent; color: #2d7a45; border: 1.5px solid #2d7a45; }
.orc__btn--outline:hover { background: #f0faf4; }

.orc__alert {
    background: #fff8e1; border: 1px solid #fcd34d;
    border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;
}
.orc__alert-icon { color: #b45309; margin-right: 0.5rem; }
.orc__alert-text { color: #92400e; font-size: 0.9rem; }

@media (max-width: 768px) {
    .orc__item { flex-direction: column; text-align: center; }
    .orc__actions { flex-direction: column; }
    .orc__btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">Create Return Request</h1>
            <p style="font-size:0.9rem; color:#6b7c6b; margin-bottom:1.75rem;">Request a return for order #{{ $order->order_number }}</p>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="orc__alert">
                <span class="orc__alert-icon">⚠️</span>
                <span class="orc__alert-text">Returns are only accepted for delivered orders within 30 days of delivery. Refund amount cannot exceed the order total.</span>
            </div>

            <div class="orc__card">
                <div class="orc__header">
                    <div class="orc__order-num">Order #{{ $order->order_number }}</div>
                    <div class="orc__order-date">Ordered on {{ $order->created_at->format('d M Y') }} • Total: ₹{{ number_format($order->total_amount, 2) }}</div>
                </div>

                <div class="orc__body">
                    <form action="{{ route('order-returns.store', $order->order_number) }}" method="POST" class="orc__form">
                        @csrf

                    <div class="orc__section">
                        <h3 class="orc__section-title">Select Item to Return</h3>
                        <div class="orc__items">
                            @foreach($order->orderItems as $item)
                            <label class="orc__item">
                                <input type="radio" name="order_item_id" value="{{ $item->id }}"
                                       {{ old('order_item_id') == $item->id || ($loop->first && !old('order_item_id')) ? 'checked' : '' }}>
                                @if($item->product->featured_image)
                                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product->name }}" class="orc__item-img">
                                @else
                                    <div class="orc__item-img" style="display:flex; align-items:center; justify-content:center; background:#f0f5ee;">📦</div>
                                @endif
                                <div class="orc__item-details">
                                    <div class="orc__item-name">{{ $item->product->name }}</div>
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
                            <div class="text-danger mt-2" style="font-size:0.85rem;">{{ $message }}</div>
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
                                          required maxlength="500"></textarea>
                                <small class="text-muted">Maximum 500 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="refund_amount" class="form-label">Refund Amount (₹) *</label>
                                <input type="number" name="refund_amount" id="refund_amount" class="form-control"
                                       step="0.01" min="0" max="{{ $order->total_amount }}"
                                       value="{{ $order->total_amount }}" required>
                                <small class="text-muted">Maximum refund: ₹{{ number_format($order->total_amount, 2) }}</small>
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
@endsection
