@extends('layout.frontlayout')
@section('title', 'Order Confirmed – Bharat Biomer')

@section('content')
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
                            <div class="suc__info-value suc__info-value--success">
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
                                <div class="suc__item-info">
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
                            <div class="suc__totals">
                                <div class="suc__total-row">
                                    <span>Subtotal</span>
                                    <span>₹{{ number_format($order->total_amount - $order->shipping_amount, 2) }}</span>
                                </div>
                                <div class="suc__total-row">
                                    <span>Shipping</span>
                                    <span class="suc__shipping-value">
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
                                <div class="suc__address-full">
                                    <div class="suc__detail-label">Address</div>
                                    <div class="suc__detail-value">
                                        {{ $order->address }},
                                        {{ $order->city }}, {{ $order->state }}, India — {{ $order->pincode }}
                                    </div>
                                </div>
                                @if($order->notes)
                                <div class="suc__address-full">
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
                        <div class="suc__card suc__cta-card">
                            <a href="{{ route('orders.index') }}" class="suc__btn-primary">
                                View My Orders
                            </a>
                            <a href="{{ route('products.index') }}" class="suc__btn-outline">
                                Continue Shopping
                            </a>
                            <p class="suc__support-note">
                                Need help? Email us at
                                <a href="mailto:support@bharatbiomer.com"
                                   class="suc__support-link">
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
