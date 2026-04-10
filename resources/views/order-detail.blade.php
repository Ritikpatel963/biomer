@extends('layout.userpanel')
@section('title', 'Order #{{ $order->order_number }} – Bharat Biomer')

@section('panel')
<style>
.od__back {
    display: inline-flex; align-items: center; gap: 0.4rem;
    color: #2d7a45; font-size: 0.88rem; font-weight: 600;
    text-decoration: none; margin-bottom: 1.25rem; display: block;
}
.od__back:hover { color: #245e36; }

.od__status-card {
    background: #fff; border-radius: 16px; border: 1px solid #e8f0e4;
    padding: 1.5rem; box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem; display: flex; flex-wrap: wrap;
    align-items: center; gap: 1.5rem;
}
.od__order-num { font-size: 1.1rem; font-weight: 800; color: #1a2e1a; }
.od__order-date { font-size: 0.82rem; color: #6b7c6b; margin-top: 2px; }
.od__status-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
.od__total-big { font-size: 1.4rem; font-weight: 800; color: #2d7a45; }

.od__status {
    display: inline-block; padding: 5px 16px; border-radius: 20px;
    font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
}
.od__status--pending    { background:#fff8e1; color:#b45309; border:1px solid #fcd34d; }
.od__status--confirmed  { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.od__status--processing { background:#e8f5fd; color:#1a6fa8; border:1px solid #90caf9; }
.od__status--shipped    { background:#f3e8fd; color:#6d28d9; border:1px solid #c4b5fd; }
.od__status--delivered  { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.od__status--cancelled  { background:#fdecea; color:#c0392b; border:1px solid #f5a9a4; }

.od__progress-card {
    background: #fff; border-radius: 16px; border: 1px solid #e8f0e4;
    padding: 1.5rem; box-shadow: 0 2px 16px rgba(60,120,60,0.06); margin-bottom: 1.5rem;
}
.od__progress-steps { display: flex; align-items: center; }
.od__step { display: flex; flex-direction: column; align-items: center; flex: 1; text-align: center; position: relative; }
.od__step-circle {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2px solid #c8e0c8; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; font-weight: 800; color: #9aab9a; z-index: 1; position: relative;
}
.od__step.done   .od__step-circle { background: #2d7a45; border-color: #2d7a45; color: #fff; }
.od__step.active .od__step-circle { background: #e8f5ed; border-color: #2d7a45; color: #2d7a45; box-shadow: 0 0 0 4px rgba(45,122,69,0.15); }
.od__step-label { font-size: 0.7rem; font-weight: 700; color: #9aab9a; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.3px; }
.od__step.done .od__step-label, .od__step.active .od__step-label { color: #2d7a45; }
.od__step-line { flex: 1; height: 2px; background: #e8f0e4; margin: 0 -1px; margin-top: -20px; }
.od__step-line.done { background: #2d7a45; }

.od__card {
    background: #fff; border-radius: 16px; border: 1px solid #e8f0e4;
    padding: 1.5rem; box-shadow: 0 2px 16px rgba(60,120,60,0.06); margin-bottom: 1.5rem;
}
.od__card-title {
    font-size: 0.95rem; font-weight: 800; color: #1a2e1a;
    margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f0f5ee;
}

.od__item { display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 0; border-bottom: 1px solid #f0f5ee; }
.od__item:last-of-type { border-bottom: none; }
.od__item-img { width: 56px; height: 56px; object-fit: contain; background: #f4faf0; border-radius: 10px; padding: 5px; border: 1px solid #e8f0e4; flex-shrink: 0; }
.od__item-placeholder { width: 56px; height: 56px; background: #f4faf0; border-radius: 10px; border: 1px solid #e8f0e4; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
.od__item-name { font-size: 0.9rem; font-weight: 700; color: #1a2e1a; }
.od__item-meta { font-size: 0.75rem; color: #6b7c6b; margin-top: 2px; }
.od__item-price { font-size: 0.95rem; font-weight: 700; color: #2d7a45; margin-left: auto; white-space: nowrap; }

.od__total-row { display: flex; justify-content: space-between; font-size: 0.88rem; color: #4a6b4a; padding: 0.45rem 0; border-bottom: 1px solid #f5f5f5; }
.od__total-row:last-of-type { border-bottom: none; }
.od__total-row.grand { font-size: 1.05rem; font-weight: 800; color: #1a2e1a; border-top: 2px solid #e8f0e4; border-bottom: none; padding-top: 0.75rem; margin-top: 0.25rem; }
.od__total-row.grand span:last-child { color: #2d7a45; }

.od__detail-row { display: flex; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #f5f5f5; font-size: 0.88rem; }
.od__detail-row:last-child { border-bottom: none; }
.od__detail-label { font-weight: 700; color: #4a6b4a; width: 100px; flex-shrink: 0; }
.od__detail-value { color: #1a2e1a; }

@media (max-width: 576px) {
    .od__status-card { flex-direction: column; align-items: flex-start; }
    .od__status-right { margin-left: 0; }
    .od__step-label { font-size: 0.6rem; }
}
</style>

<a href="{{ route('orders.index') }}" class="od__back">← Back to My Orders</a>

<h1 style="font-size:1.6rem; font-weight:800; color:#1a2e1a; margin-bottom:0.2rem;">Order Details</h1>
<p style="font-size:0.88rem; color:#6b7c6b; margin-bottom:1.5rem;">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>

<div class="od__status-card">
    <div>
        <div class="od__order-num"># {{ $order->order_number }}</div>
        <div class="od__order-date">{{ $order->items->count() }} item(s)</div>
    </div>
    <div class="od__status-right">
        <span class="od__status od__status--{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        <span class="od__total-big">₹{{ number_format($order->total_amount, 2) }}</span>
    </div>
</div>

@php
    $steps   = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    $current = array_search($order->status, $steps);
    if ($current === false) $current = -1;
@endphp
@if($order->status !== 'cancelled')
<div class="od__progress-card">
    <div class="od__progress-steps">
        @foreach($steps as $i => $step)
            <div class="od__step {{ $i < $current ? 'done' : ($i == $current ? 'active' : '') }}">
                <div class="od__step-circle">
                    @if($i < $current) ✓ @else {{ $i + 1 }} @endif
                </div>
                <div class="od__step-label">{{ ucfirst($step) }}</div>
            </div>
            @if(!$loop->last)
                <div class="od__step-line {{ $i < $current ? 'done' : '' }}"></div>
            @endif
        @endforeach
    </div>
</div>
@endif

<div class="row g-4">

    <div class="col-12 col-lg-7">
        <div class="od__card">
            <div class="od__card-title">🛒 Items Ordered</div>

            @foreach($order->items as $item)
            <div class="od__item">
                @if($item->product && $item->product->featured_image)
                    <img src="{{ Storage::url($item->product->featured_image) }}" alt="{{ $item->product_name }}" class="od__item-img">
                @else
                    <div class="od__item-placeholder">🌿</div>
                @endif
                <div style="flex:1; min-width:0;">
                    <div class="od__item-name">{{ $item->product_name }}</div>
                    @if($item->variation_name)
                        <div class="od__item-meta">{{ $item->variation_name }}</div>
                    @endif
                    <div class="od__item-meta">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</div>
                    @if($item->sku)
                        <div class="od__item-meta">SKU: {{ $item->sku }}</div>
                    @endif
                </div>
                <div class="od__item-price">₹{{ number_format($item->subtotal, 2) }}</div>
            </div>
            @endforeach

            <div style="margin-top:1rem;">
                <div class="od__total-row"><span>Subtotal</span><span>₹{{ number_format($order->total_amount - $order->shipping_amount, 2) }}</span></div>
                <div class="od__total-row"><span>Shipping</span><span style="color:#2d7a45; font-weight:700;">@if($order->shipping_amount > 0)₹{{ number_format($order->shipping_amount, 2) }}@else Free @endif</span></div>
                <div class="od__total-row"><span>Tax (GST)</span><span>Included</span></div>
                <div class="od__total-row grand"><span>Total</span><span>₹{{ number_format($order->total_amount, 2) }}</span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="od__card">
            <div class="od__card-title">📦 Shipping Details</div>
            <div class="od__detail-row"><span class="od__detail-label">Name</span><span class="od__detail-value">{{ $order->name }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">Phone</span><span class="od__detail-value">{{ $order->phone }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">Email</span><span class="od__detail-value">{{ $order->email }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">Address</span><span class="od__detail-value">{{ $order->address }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">City</span><span class="od__detail-value">{{ $order->city }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">State</span><span class="od__detail-value">{{ $order->state }}</span></div>
            <div class="od__detail-row"><span class="od__detail-label">PIN Code</span><span class="od__detail-value">{{ $order->pincode }}</span></div>
            @if($order->notes)
            <div class="od__detail-row"><span class="od__detail-label">Notes</span><span class="od__detail-value">{{ $order->notes }}</span></div>
            @endif
        </div>

        <div class="od__card" style="text-align:center;">
            <div class="od__card-title" style="text-align:left;">🙋 Need Help?</div>
            <p style="font-size:0.85rem; color:#6b7c6b; margin-bottom:1rem;">
                For any queries about this order, please contact our support team.
            </p>
            @if($order->canRequestReturn())
            <a href="{{ route('order-returns.create', $order->order_number) }}"
               style="display:inline-block; padding:0.6rem 1.5rem; background:#fff4e5; color:#9a5b00; font-weight:700; font-size:0.85rem; border-radius:8px; text-decoration:none; border:1px solid #f3c77a; margin-bottom:0.75rem;">
                Return Product
            </a>
            @elseif($order->orderReturn)
            <a href="{{ route('order-returns.show', $order->orderReturn->id) }}"
               style="display:inline-block; padding:0.6rem 1.5rem; background:#f4faf0; color:#2d7a45; font-weight:700; font-size:0.85rem; border-radius:8px; text-decoration:none; border:1px solid #a8d5b5; margin-bottom:0.75rem;">
                View Return Status
            </a>
            @endif
            <a href="mailto:support@bharatbiomer.com"
               style="display:inline-block; padding:0.6rem 1.5rem; background:#2d7a45; color:#fff; font-weight:700; font-size:0.85rem; border-radius:8px; text-decoration:none;">
                📧 Email Support
            </a>
            <div style="margin-top:0.75rem;">
                <a href="{{ route('orders.index') }}" style="font-size:0.82rem; color:#2d7a45; font-weight:600; text-decoration:none;">
                    ← Back to All Orders
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
