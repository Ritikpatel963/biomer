@extends('layout.frontlayout')
@section('title', 'Your Cart – Bharat Biomer')

@section('content')
<style>
/* ═══════════════════════════════════════════
   CART PAGE
═══════════════════════════════════════════ */
.cart__section {
    padding: 3rem 0 5rem;
    background: #f8fbf6;
    min-height: 60vh;
}
.cart__heading {
    font-size: 1.9rem;
    font-weight: 800;
    color: #1a2e1a;
    margin-bottom: 0.2rem;
}
.cart__subheading {
    font-size: 0.9rem;
    color: #6b7c6b;
    margin-bottom: 2rem;
}

/* ── Table Card ── */
.cart__table-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
}
.cart__table { width: 100%; border-collapse: collapse; }
.cart__table thead tr {
    background: #f4faf0;
    border-bottom: 2px solid #e8f0e4;
}
.cart__table thead th {
    padding: 1rem 1.25rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #4a6b4a;
}
.cart__table tbody tr {
    border-bottom: 1px solid #f0f5ee;
    transition: background 0.15s;
}
.cart__table tbody tr:last-child { border-bottom: none; }
.cart__table tbody tr:hover { background: #fafff8; }
.cart__table td {
    padding: 1.1rem 1.25rem;
    vertical-align: middle;
    font-size: 0.92rem;
    color: #1a2e1a;
}

/* ── Product Cell ── */
.cart__product-wrap { display: flex; align-items: center; gap: 1rem; }
.cart__product-img {
    width: 64px; height: 64px;
    object-fit: contain;
    background: #f4faf0;
    border-radius: 10px;
    padding: 6px;
    border: 1px solid #e8f0e4;
    flex-shrink: 0;
}
.cart__product-img-placeholder {
    width: 64px; height: 64px;
    background: #f4faf0;
    border-radius: 10px;
    border: 1px solid #e8f0e4;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
}
.cart__product-name { font-weight: 700; color: #1a2e1a; font-size: 0.95rem; margin-bottom: 3px; }
.cart__product-variant {
    font-size: 0.75rem; color: #2d7a45;
    background: #e8f5ed; border: 1px solid #a8d5b5;
    border-radius: 20px; padding: 2px 10px; display: inline-block;
}
.cart__product-sku { font-size: 0.7rem; color: #9aab9a; margin-top: 3px; }

/* ── Price ── */
.cart__price { font-weight: 600; color: #2d7a45; font-size: 1rem; }

/* ── Quantity Controls ── */
.cart__qty-wrap {
    display: flex; align-items: center;
    border: 1.5px solid #c8e0c8;
    border-radius: 8px; overflow: hidden;
    width: fit-content;
}
.cart__qty-btn {
    background: #f4faf0; border: none;
    width: 34px; height: 36px;
    font-size: 1.1rem; font-weight: 700;
    color: #2d7a45; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s; user-select: none;
}
.cart__qty-btn:hover { background: #d4f0e0; }
.cart__qty-btn:active { background: #b8e8cc; }
.cart__qty-btn:disabled { color: #c0c0c0; cursor: not-allowed; background: #f4faf0; }
.cart__qty-input {
    width: 46px; height: 36px;
    border: none;
    border-left: 1.5px solid #c8e0c8;
    border-right: 1.5px solid #c8e0c8;
    text-align: center; font-size: 0.92rem;
    font-weight: 700; color: #1a2e1a;
    background: #fff; outline: none;
    -moz-appearance: textfield;
}
.cart__qty-input::-webkit-inner-spin-button,
.cart__qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }

/* ── Item Total ── */
.cart__item-total { font-weight: 700; color: #1a2e1a; font-size: 1rem; }

/* ── Remove ── */
.cart__remove-btn {
    background: none; border: none;
    color: #c0392b; cursor: pointer;
    padding: 6px 8px; border-radius: 6px;
    transition: background 0.15s;
    display: flex; align-items: center; justify-content: center;
}
.cart__remove-btn:hover { background: #fdecea; }

/* ── Updating overlay ── */
.cart__row-updating { opacity: 0.5; pointer-events: none; transition: opacity 0.2s; }

/* ── Summary Card ── */
.cart__summary-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    padding: 1.75rem;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    position: sticky; top: 20px;
}
.cart__summary-title {
    font-size: 1.1rem; font-weight: 800; color: #1a2e1a;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f5ee;
}
.cart__summary-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 0.65rem; font-size: 0.9rem; color: #4a6b4a;
}
.cart__summary-row.total {
    font-size: 1.1rem; font-weight: 800; color: #1a2e1a;
    border-top: 2px solid #f0f5ee;
    padding-top: 0.75rem; margin-top: 0.5rem; margin-bottom: 1.25rem;
}
.cart__summary-row.total span:last-child { color: #2d7a45; }

/* ── Buttons ── */
.cart__checkout-btn {
    display: block; width: 100%; padding: 0.9rem;
    background: #2d7a45; color: #fff;
    font-weight: 700; font-size: 1rem;
    border: none; border-radius: 10px;
    text-align: center; text-decoration: none;
    cursor: pointer; transition: background 0.2s;
}
.cart__checkout-btn:hover { background: #245e36; color: #fff; }
.cart__continue-btn {
    display: block; width: 100%; padding: 0.7rem;
    background: transparent; color: #2d7a45;
    font-weight: 600; font-size: 0.88rem;
    border: 1.5px solid #2d7a45; border-radius: 10px;
    text-align: center; text-decoration: none;
    margin-top: 0.75rem; transition: background 0.2s;
}
.cart__continue-btn:hover { background: #f0faf4; color: #2d7a45; }
.cart__clear-btn {
    display: block; width: 100%; padding: 0.6rem;
    background: transparent; color: #c0392b;
    font-weight: 600; font-size: 0.82rem;
    border: 1.5px solid #e8b4b0; border-radius: 10px;
    text-align: center; text-decoration: none;
    margin-top: 0.6rem; transition: background 0.2s;
}
.cart__clear-btn:hover { background: #fdecea; color: #c0392b; }

/* ── Item count badge ── */
.cart__count-badge {
    display: inline-block;
    background: #e8f5ed; color: #2d7a45;
    border: 1px solid #a8d5b5;
    border-radius: 20px;
    padding: 2px 12px;
    font-size: 0.82rem; font-weight: 700;
    margin-left: 8px;
}

/* ── Toast notification ── */
.cart__toast {
    position: fixed; bottom: 24px; right: 24px;
    background: #1a2e1a; color: #fff;
    padding: 12px 20px; border-radius: 10px;
    font-size: 0.88rem; font-weight: 600;
    z-index: 9999; opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    pointer-events: none;
    max-width: 300px;
}
.cart__toast.show { opacity: 1; transform: translateY(0); }
.cart__toast.success { border-left: 4px solid #2d7a45; }
.cart__toast.error   { border-left: 4px solid #e74c3c; }

/* ── Trust badges ── */
.cart__trust {
    display: flex; flex-direction: column; gap: 0.5rem;
    margin-top: 1.25rem; padding-top: 1rem;
    border-top: 1px solid #f0f5ee;
}
.cart__trust-item {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.78rem; color: #6b7c6b;
}

/* ── Empty Cart ── */
.cart__empty {
    text-align: center; padding: 5rem 1rem;
    background: #fff; border-radius: 16px;
    border: 1px solid #e8f0e4;
}
.cart__empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
.cart__empty h3 { font-size: 1.4rem; font-weight: 700; color: #1a2e1a; margin-bottom: 0.5rem; }
.cart__empty p { color: #6b7c6b; margin-bottom: 1.5rem; font-size: 0.92rem; }
.cart__empty-btn {
    display: inline-block; padding: 0.75rem 2rem;
    background: #2d7a45; color: #fff;
    font-weight: 700; border-radius: 10px;
    text-decoration: none; transition: background 0.2s;
}
.cart__empty-btn:hover { background: #245e36; color: #fff; }

/* ── Mobile: stacked cards ── */
@media (max-width: 768px) {
    .cart__table thead { display: none; }
    .cart__table, .cart__table tbody,
    .cart__table tr, .cart__table td { display: block; width: 100%; }
    .cart__table tr {
        background: #fff; border-radius: 12px;
        border: 1px solid #e8f0e4; margin-bottom: 1rem;
        padding: 1rem; box-shadow: 0 2px 8px rgba(60,120,60,0.05);
    }
    .cart__table tbody tr:hover { background: #fff; }
    .cart__table td { padding: 0.4rem 0; border: none; font-size: 0.88rem; }
    .cart__table td::before {
        content: attr(data-label);
        font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; color: #4a6b4a;
        display: block; margin-bottom: 4px;
    }
    .cart__table-card { background: transparent; border: none; box-shadow: none; }
}
</style>

{{-- Flash --}}
@if(session('success'))
    <div class="container pt-4">
        <div class="alert alert-success rounded-3" style="background:#e8f5ed; border:1px solid #a8d5b5; color:#2d7a45; font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="container pt-4">
        <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
    </div>
@endif

<section class="cart__section">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb" style="font-size:0.82rem; background:transparent; padding:0; margin:0;">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}" style="color:#2d7a45; text-decoration:none;">Shop</a>
                </li>
                <li class="breadcrumb-item active" style="color:#6b7c6b;">Cart</li>
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
                        <span id="summaryShipping" style="color:#2d7a45; font-weight:700;">
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

                    <div class="cart__summary-row" id="discountRow" style="{{ $discount > 0 ? '' : 'display: none;' }}">
                        <span>Discount <span id="couponCodeBadge" class="cart__product-variant" style="font-size:0.7rem;">{{ $coupon['code'] ?? '' }}</span></span>
                        <span id="summaryDiscount" style="color:#c0392b;">-₹{{ number_format($discount ?? 0, 2) }} <a href="javascript:void(0)" onclick="removeCoupon()" style="color:#c0392b; text-decoration:none; margin-left:5px;" title="Remove Coupon">✕</a></span>
                    </div>

                    <div class="cart__summary-row total">
                        <span>Total</span>
                        <span id="summaryTotal">₹{{ number_format($finalTotal ?? $total, 2) }}</span>
                    </div>

                    {{-- Coupon Input --}}
                    <div class="mb-4" id="couponFormWrapper" style="{{ $discount > 0 ? 'display: none;' : '' }}">
                        <label style="font-size: 0.85rem; font-weight: 700; color: #1a2e1a; margin-bottom: 0.3rem;">Have a coupon?</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="couponCode" class="form-control" placeholder="Enter code" style="border: 1.5px solid #e8f0e4; border-radius: 8px; font-size: 0.9rem; flex:1;">
                            <button type="button" class="btn btn-dark" onclick="applyCoupon()" style="background:#1a2e1a; border:none; border-radius:8px; font-weight:600; font-size:0.9rem;">Apply</button>
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
                        <p style="text-align:center; font-size:0.78rem; color:#6b7c6b; margin-top:0.6rem; margin-bottom:0;">
                            No account?
                            <a href="{{ route('customer.register') }}" style="color:#2d7a45; font-weight:600;">Register free</a>
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

@push('scripts')
<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

function updateGlobalCartBadge(count) {
    if (count > 0) {
        document.querySelectorAll('.bb-cart-badge').forEach(badge => {
            badge.textContent = count;
        });
    } else {
        document.querySelectorAll('.bb-cart-badge').forEach(badge => badge.remove());
    }
}

function formatShipping(amount) {
    return amount === '₹0.00' ? 'Free' : amount;
}

// ════════════════════════════════════════════════
//  TOAST
// ════════════════════════════════════════════════
function showToast(message, type = 'success') {
    const toast = document.getElementById('cartToast');
    toast.textContent = message;
    toast.className   = 'cart__toast ' + type;
    toast.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => toast.classList.remove('show'), 3000);
}

// ════════════════════════════════════════════════
//  CHANGE QUANTITY (+ / - buttons)
// ════════════════════════════════════════════════
function changeQty(key, index, delta) {
    const input = document.getElementById('qtyInput_' + index);
    let newVal  = parseInt(input.value) + delta;
    newVal      = Math.max(1, Math.min(100, newVal));
    input.value = newVal;
    sendQtyUpdate(key, index, newVal);
}

// ════════════════════════════════════════════════
//  MANUAL INPUT CHANGE
// ════════════════════════════════════════════════
function manualQtyChange(input) {
    let val = parseInt(input.value) || 1;
    val     = Math.max(1, Math.min(100, val));
    input.value = val;
    sendQtyUpdate(input.dataset.key, input.dataset.index, val);
}

// ════════════════════════════════════════════════
//  SEND QTY UPDATE TO SERVER
// ════════════════════════════════════════════════
function sendQtyUpdate(key, index, quantity) {
    const row = document.getElementById('cartRow_' + index);
    row.classList.add('cart__row-updating');

    fetch('/cart/update', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'X-CSRF-TOKEN' : CSRF_TOKEN,
            'Accept'       : 'application/json',
        },
        body: JSON.stringify({ key, quantity })
    })
    .then(r => r.json())
    .then(d => {
        row.classList.remove('cart__row-updating');
        if (d.success) {
            // Update item total
            document.getElementById('itemTotal_' + index).textContent = d.item_total;
            // Update summary
            document.getElementById('summarySubtotal').textContent = d.subtotal;
            document.getElementById('summaryShipping').textContent = formatShipping(d.shipping_total);
            document.getElementById('summaryTax').textContent = d.tax_amount;
            document.getElementById('summaryTotal').textContent    = d.final_total;
            document.getElementById('summaryItemCount').textContent = d.cart_count;
            document.getElementById('cartCountBadge').textContent   = d.cart_count + ' item(s)';
            updateGlobalCartBadge(d.cart_count);
            showToast('Quantity updated!', 'success');
        } else {
            showToast('Could not update. Try again.', 'error');
        }
    })
    .catch(() => {
        row.classList.remove('cart__row-updating');
        showToast('Network error. Try again.', 'error');
    });
}

// ════════════════════════════════════════════════
//  REMOVE ITEM
// ════════════════════════════════════════════════
function removeItem(key, index) {
    const row = document.getElementById('cartRow_' + index);
    row.classList.add('cart__row-updating');

    fetch('/cart/remove', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'X-CSRF-TOKEN' : CSRF_TOKEN,
            'Accept'       : 'application/json',
        },
        body: JSON.stringify({ key })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            // Animate row out
            row.style.transition = 'opacity 0.3s, transform 0.3s';
            row.style.opacity    = '0';
            row.style.transform  = 'translateX(20px)';
            setTimeout(() => {
                row.remove();
                // Update summary
                document.getElementById('summarySubtotal').textContent = d.subtotal;
                document.getElementById('summaryShipping').textContent = formatShipping(d.shipping_total);
                document.getElementById('summaryTax').textContent = d.tax_amount;
                document.getElementById('summaryTotal').textContent    = d.final_total;
                document.getElementById('summaryItemCount').textContent = d.cart_count;
                document.getElementById('cartCountBadge').textContent  = d.cart_count + ' item(s)';
                updateGlobalCartBadge(d.cart_count);
                showToast('Item removed from cart.', 'success');
                // Reload if empty
                if (d.empty) setTimeout(() => location.reload(), 800);
            }, 300);
        } else {
            row.classList.remove('cart__row-updating');
            showToast('Could not remove item. Try again.', 'error');
        }
    })
    .catch(() => {
        row.classList.remove('cart__row-updating');
        showToast('Network error. Try again.', 'error');
    });
}

// ════════════════════════════════════════════════
//  APPLY COUPON
// ════════════════════════════════════════════════
function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    if(!code) return showToast('Please enter a coupon code.', 'error');
    
    fetch('{{ route("cart.coupon.apply") }}', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'X-CSRF-TOKEN' : CSRF_TOKEN,
            'Accept'       : 'application/json',
        },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(d => {
        if(d.success) {
            showToast(d.message, 'success');
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('couponCodeBadge').textContent = code;
            document.getElementById('summaryDiscount').innerHTML = `-${d.discount} <a href="javascript:void(0)" onclick="removeCoupon()" style="color:#c0392b; text-decoration:none; margin-left:5px;" title="Remove Coupon">✕</a>`;
            document.getElementById('summarySubtotal').textContent = d.subtotal;
            document.getElementById('summaryShipping').textContent = formatShipping(d.shipping_total);
            document.getElementById('summaryTax').textContent = d.tax_amount;
            document.getElementById('summaryTotal').textContent = d.final_total;
            document.getElementById('couponFormWrapper').style.display = 'none';
        } else {
            showToast(d.message, 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'));
}

// ════════════════════════════════════════════════
//  REMOVE COUPON
// ════════════════════════════════════════════════
function removeCoupon() {
    fetch('{{ route("cart.coupon.remove") }}', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'X-CSRF-TOKEN' : CSRF_TOKEN,
            'Accept'       : 'application/json',
        }
    })
    .then(r => r.json())
    .then(d => {
        if(d.success) {
            showToast(d.message, 'success');
            document.getElementById('discountRow').style.display = 'none';
            document.getElementById('summarySubtotal').textContent = d.subtotal;
            document.getElementById('summaryShipping').textContent = formatShipping(d.shipping_total);
            document.getElementById('summaryTax').textContent = d.tax_amount;
            document.getElementById('summaryTotal').textContent = d.final_total;
            document.getElementById('couponFormWrapper').style.display = 'block';
            document.getElementById('couponCode').value = '';
        }
    })
    .catch(() => showToast('Network error.', 'error'));
}
</script>
@endpush
