@extends('layout.frontlayout')
@section('title', 'Your Cart – Bharat Biomer')

@section('content')
{{-- Flash --}}
@if(session('success'))
    <div class="container pt-4">
        <div class="alert alert-success rounded-3 cart__flash-success">
            ✓ {{ session('success') }}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="container pt-4">
        <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
    </div>
@endif

<section class="cart__section"
         data-cart-coupon-apply-url="{{ route('cart.coupon.apply') }}"
         data-cart-coupon-remove-url="{{ route('cart.coupon.remove') }}">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb cart__breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}" class="cart__breadcrumb-link">Shop</a>
                </li>
                <li class="breadcrumb-item active cart__breadcrumb-current">Cart</li>
            </ol>
        </nav>

        <h1 class="cart__heading">
            Your Cart
            <span class="cart__count-badge" id="cartCountBadge">
                {{ collect($cart)->sum('quantity') }} item(s)
            </span>
        </h1>
        <p class="cart__subheading">Review your items before checkout</p>

        @if(count($cart) > 0)
        <div class="row g-4">

            {{-- ══════════════════════════════
                 LEFT: Cart Items Table
            ══════════════════════════════ --}}
            <div class="col-12 col-lg-8">
                <div class="cart__table-card">
                    <table class="cart__table" id="cartTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $key => $item)
                            <tr id="cartRow_{{ $loop->index }}" data-key="{{ $key }}" data-index="{{ $loop->index }}">

                                {{-- Product --}}
                                <td data-label="Product">
                                    <div class="cart__product-wrap">
                                        @if(!empty($item['image']))
                                            <img src="{{ Storage::url($item['image']) }}"
                                                 alt="{{ $item['name'] }}"
                                                 class="cart__product-img">
                                        @else
                                            <div class="cart__product-img-placeholder"><iconify-icon icon="mdi:leaf" class="icon"></iconify-icon></div>
                                        @endif
                                        <div>
                                            <div class="cart__product-name">{{ $item['name'] }}</div>
                                            @if(!empty($item['variation']))
                                                <span class="cart__product-variant">{{ $item['variation'] }}</span>
                                            @endif
                                            @if(!empty($item['sku']))
                                                <div class="cart__product-sku">SKU: {{ $item['sku'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Unit Price --}}
                                <td data-label="Price">
                                    <span class="cart__price">₹{{ number_format($item['price'], 2) }}</span>
                                </td>

                                {{-- Quantity --}}
                                <td data-label="Quantity">
                                    <div class="cart__qty-wrap">
                                        <button class="cart__qty-btn"
                                                type="button"
                                                onclick="changeQty('{{ $key }}', {{ $loop->index }}, -1)"
                                                id="minusBtn_{{ $loop->index }}">
                                            −
                                        </button>
                                        <input type="number"
                                               class="cart__qty-input"
                                               id="qtyInput_{{ $loop->index }}"
                                               value="{{ $item['quantity'] }}"
                                               min="1" max="100"
                                               data-key="{{ $key }}"
                                               data-price="{{ $item['price'] }}"
                                               data-index="{{ $loop->index }}"
                                               onchange="manualQtyChange(this)">
                                        <button class="cart__qty-btn"
                                                type="button"
                                                onclick="changeQty('{{ $key }}', {{ $loop->index }}, 1)"
                                                id="plusBtn_{{ $loop->index }}">
                                            +
                                        </button>
                                    </div>
                                </td>

                                {{-- Item Total --}}
                                <td data-label="Total">
                                    <span class="cart__item-total"
                                          id="itemTotal_{{ $loop->index }}">
                                        ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </span>
                                </td>

                                {{-- Remove --}}
                                <td>
                                    <button class="cart__remove-btn"
                                            type="button"
                                            onclick="removeItem('{{ $key }}', {{ $loop->index }})"
                                            title="Remove item">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14H6L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4h6v2"/>
                                        </svg>
                                    </button>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ══════════════════════════════
                 RIGHT: Order Summary
            ══════════════════════════════ --}}
            <div class="col-12 col-lg-4">
                <div class="cart__summary-card">
                    <div class="cart__summary-title">Order Summary</div>

                    <div class="cart__summary-row">
                        <span>Items (<span id="summaryItemCount">{{ collect($cart)->sum('quantity') }}</span>)</span>
                        <span id="summarySubtotal">₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="cart__summary-row">
                        <span>Shipping</span>
                        <span id="summaryShipping" class="cart__summary-value--success">
                            @if($shippingTotal > 0)
                                ₹{{ number_format($shippingTotal, 2) }}
                            @else
                                Free
                            @endif
                        </span>
                    </div>
                    <div class="cart__summary-row">
                        <span>Tax (GST)</span>
                        <span id="summaryTax">{{ ($taxAmount ?? 0) > 0 ? '₹' . number_format($taxAmount, 2) : 'Included' }}</span>
                    </div>

                    <div class="cart__summary-row {{ $discount > 0 ? '' : 'cart__summary-row--hidden' }}" id="discountRow">
                        <span>Discount <span id="couponCodeBadge" class="cart__product-variant cart__coupon-badge">{{ $coupon['code'] ?? '' }}</span></span>
                        <span id="summaryDiscount" class="cart__discount-value">-₹{{ number_format($discount ?? 0, 2) }} <a href="javascript:void(0)" onclick="removeCoupon()" class="cart__remove-coupon" title="Remove Coupon">✕</a></span>
                    </div>

                    <div class="cart__summary-row total">
                        <span>Total</span>
                        <span id="summaryTotal">₹{{ number_format($finalTotal ?? $total, 2) }}</span>
                    </div>

                    {{-- Coupon Input --}}
                    <div class="mb-4 {{ $discount > 0 ? 'cart__coupon-form--hidden' : '' }}" id="couponFormWrapper">
                        <label class="cart__coupon-label">Have a coupon?</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="couponCode" class="form-control cart__coupon-input" placeholder="Enter code">
                            <button type="button" class="btn btn-dark cart__coupon-apply-btn" onclick="applyCoupon()">Apply</button>
                        </div>
                    </div>

                    {{-- Checkout Button --}}
                    @auth('customer')
                        <a href="{{ route('checkout') }}" class="cart__checkout-btn">
                            Proceed to Checkout →
                        </a>
                    @else
                        <a href="{{ route('customer.login') }}" class="cart__checkout-btn">
                            Login to Checkout →
                        </a>
                        <p class="cart__guest-note">
                            No account?
                            <a href="{{ route('customer.register') }}" class="cart__guest-link">Register free</a>
                        </p>
                    @endauth

                    <a href="{{ route('products.index') }}" class="cart__continue-btn">← Continue Shopping</a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Clear your entire cart?')">
                        @csrf
                        <button type="submit" class="cart__clear-btn border-0">
                           <iconify-icon icon="fa6-solid:trash" class="me-2"></iconify-icon> Clear Cart
                        </button>
                    </form>

                    {{-- Trust Badges --}}
                    <div class="cart__trust">
                        <div class="cart__trust-item">
                            <svg width="15" height="15" fill="none" stroke="#2d7a45" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            Secure Checkout
                        </div>
                        <div class="cart__trust-item">
                            <svg width="15" height="15" fill="none" stroke="#2d7a45" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            100% Genuine Products
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @else
        {{-- ── Empty State ── --}}
        <div class="cart__empty">
            <div class="cart__empty-icon"><iconify-icon icon="fa6-solid:cart-shopping" class="icon"></iconify-icon></div>
            <h3>Your cart is empty</h3>
            <p>Browse our bio-stimulant range and add products to your cart.</p>
            <a href="{{ route('products.index') }}" class="cart__empty-btn">Browse Products</a>
        </div>
        @endif

    </div>
</section>

{{-- Toast --}}
<div class="cart__toast" id="cartToast"></div>

@endsection
