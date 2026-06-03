@extends('layout.frontlayout')
@section('title', 'Order Confirmed – Bharat Biomer')

@section('content')
<style>
/* ═══════════════════════════════════════════
   ORDER SUCCESS PAGE
═══════════════════════════════════════════ */
.suc__section {
    padding: 4rem 0 6rem;
    background: #f8fbf6;
    min-height: 70vh;
}

/* ── Hero ── */
.suc__hero {
    text-align: center;
    padding: 2.5rem 1.5rem 2rem;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e8f0e4;
    box-shadow: 0 4px 24px rgba(60,120,60,0.08);
    margin-bottom: 2rem;
}
.suc__icon-wrap {
    width: 88px; height: 88px;
    background: linear-gradient(135deg, #e8f5ed, #b8e8cc);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
}
.suc__title {
    font-size: 2rem;
    font-weight: 800;
    color: #1a2e1a;
    margin-bottom: 0.4rem;
}
.suc__subtitle {
    font-size: 0.95rem;
    color: #6b7c6b;
    margin-bottom: 1.5rem;
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
}
.suc__order-num {
    display: inline-block;
    background: #e8f5ed;
    border: 1.5px solid #a8d5b5;
    border-radius: 30px;
    padding: 0.5rem 1.75rem;
    font-size: 0.95rem; font-weight: 700;
    color: #2d7a45; letter-spacing: 0.5px;
}

/* ── Info pills ── */
.suc__info-row {
    display: flex; flex-wrap: wrap;
    gap: 1rem; justify-content: center;
    margin-top: 1.75rem;
}
.suc__info-pill {
    background: #f4faf0;
    border: 1px solid #d4e8d0;
    border-radius: 12px;
    padding: 0.85rem 1.4rem;
    text-align: center; min-width: 120px;
}
.suc__info-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.5px;
    color: #4a6b4a; margin-bottom: 3px;
}
.suc__info-value { font-size: 0.92rem; font-weight: 700; color: #1a2e1a; }

/* ── Cards ── */
.suc__card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    padding: 1.75rem;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem;
}
.suc__card-title {
    font-size: 1rem; font-weight: 800; color: #1a2e1a;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f5ee;
}

/* ── Order Items ── */
.suc__item {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f5ee;
}
.suc__item:last-of-type { border-bottom: none; }
.suc__item-img {
    width: 52px; height: 52px;
    object-fit: contain; background: #f4faf0;
    border-radius: 8px; padding: 5px;
    border: 1px solid #e8f0e4; flex-shrink: 0;
}
.suc__item-img-placeholder {
    width: 52px; height: 52px;
    background: #f4faf0; border-radius: 8px;
    border: 1px solid #e8f0e4; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}
.suc__item-name { font-size: 0.9rem; font-weight: 700; color: #1a2e1a; line-height: 1.3; }
.suc__item-meta { font-size: 0.75rem; color: #6b7c6b; }
.suc__item-price { font-size: 0.92rem; font-weight: 700; color: #2d7a45; margin-left: auto; white-space: nowrap; }

/* ── Totals ── */
.suc__total-row {
    display: flex; justify-content: space-between;
    font-size: 0.88rem; color: #4a6b4a;
    padding: 0.45rem 0;
    border-bottom: 1px solid #f5f5f5;
}
.suc__total-row:last-of-type { border-bottom: none; }
.suc__total-row.grand {
    font-size: 1.05rem; font-weight: 800; color: #1a2e1a;
    border-top: 2px solid #e8f0e4; border-bottom: none;
    padding-top: 0.75rem; margin-top: 0.25rem;
}
.suc__total-row.grand span:last-child { color: #2d7a45; }

/* ── Status Badge ── */
.suc__status {
    display: inline-block;
    padding: 4px 14px; border-radius: 20px;
    font-size: 0.76rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.4px;
}
.suc__status--pending    { background:#fff8e1; color:#b45309; border:1px solid #fcd34d; }
.suc__status--confirmed  { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.suc__status--processing { background:#e8f5fd; color:#1a6fa8; border:1px solid #90caf9; }
.suc__status--shipped    { background:#f3e8fd; color:#6d28d9; border:1px solid #c4b5fd; }
.suc__status--delivered  { background:#e8f5ed; color:#2d7a45; border:1px solid #a8d5b5; }
.suc__status--cancelled  { background:#fdecea; color:#c0392b; border:1px solid #f5a9a4; }

/* ── Address Grid ── */
.suc__address-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.suc__detail-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.3px;
    color: #4a6b4a; margin-bottom: 2px;
}
.suc__detail-value { font-size: 0.9rem; color: #1a2e1a; font-weight: 500; }

/* ── Timeline ── */
.suc__timeline { list-style: none; padding: 0; margin: 0; }
.suc__tl-item {
    display: flex; gap: 1rem; align-items: flex-start;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f5ee;
}
.suc__tl-item:last-child { border-bottom: none; }
.suc__tl-icon {
    width: 36px; height: 36px;
    border-radius: 50%; background: #e8f5ed;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.suc__tl-title { font-size: 0.9rem; font-weight: 700; color: #1a2e1a; margin-bottom: 2px; }
.suc__tl-desc  { font-size: 0.8rem; color: #6b7c6b; }

/* ── CTA Buttons ── */
.suc__btn-primary {
    display: block; width: 100%;
    padding: 0.85rem;
    background: #2d7a45; color: #fff;
    font-weight: 700; font-size: 0.95rem;
    border-radius: 10px; text-decoration: none;
    text-align: center; transition: background 0.2s;
    margin-bottom: 0.75rem;
}
.suc__btn-primary:hover { background: #245e36; color: #fff; }
.suc__btn-outline {
    display: block; width: 100%; padding: 0.85rem;
    background: transparent; color: #2d7a45;
    font-weight: 700; font-size: 0.95rem;
    border-radius: 10px; text-decoration: none;
    text-align: center;
    border: 1.5px solid #2d7a45;
    transition: background 0.2s;
}
.suc__btn-outline:hover { background: #f0faf4; color: #2d7a45; }

@media (max-width: 576px) {
    .suc__address-grid { grid-template-columns: 1fr; }
    .suc__title { font-size: 1.6rem; }
}
</style>

<section class="suc__section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                {{-- ══════════════════════
                     SUCCESS HERO
                ══════════════════════ --}}
                <div class="suc__hero">
                    <div class="suc__icon-wrap">
                        <svg width="42" height="42" fill="none" stroke="#2d7a45" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <h1 class="suc__title">Order Confirmed! 🎉</h1>
                    <p class="suc__subtitle">
                        Thank you, <strong>{{ $order->name }}</strong>! Your order has been received
                        and our team will process it shortly.
                    </p>
                    <div class="suc__order-num">Order #{{ $order->order_number }}</div>

                    {{-- Info Pills --}}
                    <div class="suc__info-row">
                        <div class="suc__info-pill">
                            <div class="suc__info-label">Date</div>
                            <div class="suc__info-value">{{ $order->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="suc__info-pill">
                            <div class="suc__info-label">Items</div>
                            <div class="suc__info-value">{{ $order->items->count() }}</div>
                        </div>
                        <div class="suc__info-pill">
                            <div class="suc__info-label">Total</div>
                            <div class="suc__info-value" style="color:#2d7a45;">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </div>
                        </div>
                        <div class="suc__info-pill">
                            <div class="suc__info-label">Status</div>
                            <div>
                                <span class="suc__status suc__status--{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">

                    {{-- ══════════════════════
                         LEFT — Items + Address
                    ══════════════════════ --}}
                    <div class="col-12 col-lg-7">

                        {{-- Items Ordered --}}
                        <div class="suc__card">
                            <div class="suc__card-title"><iconify-icon icon="fa6-solid:cart-shopping" class="icon"></iconify-icon> Items Ordered</div>

                            @foreach($order->items as $item)
                            <div class="suc__item">
                                @if($item->product && $item->product->featured_image)
                                    <img src="{{ Storage::url($item->product->featured_image) }}"
                                         alt="{{ $item->product_name }}"
                                         class="suc__item-img">
                                @else
                                    <div class="suc__item-img-placeholder"><iconify-icon icon="mdi:leaf" class="icon"></iconify-icon></div>
                                @endif
                                <div style="flex:1; min-width:0;">
                                    <div class="suc__item-name">{{ $item->product_name }}</div>
                                    @if($item->variation_name)
                                        <div class="suc__item-meta">{{ $item->variation_name }}</div>
                                    @endif
                                    <div class="suc__item-meta">
                                        Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}
                                    </div>
                                </div>
                                <div class="suc__item-price">
                                    ₹{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                            @endforeach

                            {{-- Totals --}}
                            <div style="margin-top:1rem;">
                                <div class="suc__total-row">
                                    <span>Subtotal</span>
                                    <span>₹{{ number_format($order->total_amount - $order->shipping_amount, 2) }}</span>
                                </div>
                                <div class="suc__total-row">
                                    <span>Shipping</span>
                                    <span style="color:#2d7a45; font-weight:700;">
                                        @if($order->shipping_amount > 0)
                                            ₹{{ number_format($order->shipping_amount, 2) }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                </div>
                                <div class="suc__total-row">
                                    <span>Tax (GST)</span>
                                    <span>Included</span>
                                </div>
                                <div class="suc__total-row grand">
                                    <span>Total Paid</span>
                                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        <div class="suc__card">
                            <div class="suc__card-title"><iconify-icon icon="mdi:package-variant" class="icon"></iconify-icon> Shipping Address</div>
                            <div class="suc__address-grid">
                                <div>
                                    <div class="suc__detail-label">Name</div>
                                    <div class="suc__detail-value">{{ $order->name }}</div>
                                </div>
                                <div>
                                    <div class="suc__detail-label">Phone</div>
                                    <div class="suc__detail-value">{{ $order->phone }}</div>
                                </div>
                                <div>
                                    <div class="suc__detail-label">Email</div>
                                    <div class="suc__detail-value">{{ $order->email }}</div>
                                </div>
                                <div>
                                    <div class="suc__detail-label">PIN Code</div>
                                    <div class="suc__detail-value">{{ $order->pincode }}</div>
                                </div>
                                <div style="grid-column: 1 / -1;">
                                    <div class="suc__detail-label">Address</div>
                                    <div class="suc__detail-value">
                                        {{ $order->address }},
                                        {{ $order->city }}, {{ $order->state }}, India — {{ $order->pincode }}
                                    </div>
                                </div>
                                @if($order->notes)
                                <div style="grid-column: 1 / -1;">
                                    <div class="suc__detail-label">Notes</div>
                                    <div class="suc__detail-value">{{ $order->notes }}</div>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- ══════════════════════
                         RIGHT — Next Steps + CTA
                    ══════════════════════ --}}
                    <div class="col-12 col-lg-5">

                        {{-- What's Next --}}
                        <div class="suc__card">
                            <div class="suc__card-title">📋 What Happens Next?</div>
                            <ul class="suc__timeline">
                                <li class="suc__tl-item">
                                    <div class="suc__tl-icon"><iconify-icon icon="mdi:check-circle-outline" class="icon"></iconify-icon></div>
                                    <div>
                                        <div class="suc__tl-title">Order Received</div>
                                        <div class="suc__tl-desc">Your order is confirmed and in our system.</div>
                                    </div>
                                </li>
                                <li class="suc__tl-item">
                                    <div class="suc__tl-icon">⚗️</div>
                                    <div>
                                        <div class="suc__tl-title">Quality Check</div>
                                        <div class="suc__tl-desc">Our team verifies and prepares your products.</div>
                                    </div>
                                </li>
                                <li class="suc__tl-item">
                                    <div class="suc__tl-icon"><iconify-icon icon="mdi:truck-delivery-outline" class="icon"></iconify-icon></div>
                                    <div>
                                        <div class="suc__tl-title">Dispatched</div>
                                        <div class="suc__tl-desc">Shipped within 1–2 business days with tracking.</div>
                                    </div>
                                </li>
                                <li class="suc__tl-item">
                                    <div class="suc__tl-icon">🌾</div>
                                    <div>
                                        <div class="suc__tl-title">Delivered</div>
                                        <div class="suc__tl-desc">Products reach your farm, ready to use!</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        {{-- CTA --}}
                        <div class="suc__card" style="text-align:center;">
                            <a href="{{ route('orders.index') }}" class="suc__btn-primary">
                                View My Orders
                            </a>
                            <a href="{{ route('products.index') }}" class="suc__btn-outline">
                                Continue Shopping
                            </a>
                            <p style="font-size:0.75rem; color:#9aab9a; margin-top:1rem; margin-bottom:0;">
                                Need help? Email us at
                                <a href="mailto:support@bharatbiomer.com"
                                   style="color:#2d7a45; font-weight:600;">
                                   support@bharatbiomer.com
                                </a>
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
