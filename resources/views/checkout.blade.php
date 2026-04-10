@extends('layout.frontlayout')
@section('title', 'Checkout – Bharat Biomer')

@section('content')
<style>
/* ── Payment Method Selector ── */
.pay-methods { display: flex; gap: 1rem; margin-bottom: 1.25rem; }
.pay-method-label {
    flex: 1; display: flex; align-items: center; gap: 0.6rem;
    border: 2px solid #d4e8d0; border-radius: 10px;
    padding: 0.75rem 1rem; cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    font-size: 0.9rem; font-weight: 600; color: #1a2e1a;
}
.pay-method-label:has(input:checked) {
    border-color: #2d7a45; background: #e8f5ed;
}
.pay-method-label input { display: none; }
.pay-method-label img { height: 22px; object-fit: contain; }

/* ═══════════════════════════════════════════
   CHECKOUT PAGE
═══════════════════════════════════════════ */
.chk__section {
    padding: 3rem 0 5rem;
    background: #f8fbf6;
    min-height: 60vh;
}
.chk__heading {
    font-size: 1.9rem;
    font-weight: 800;
    color: #1a2e1a;
    margin-bottom: 0.2rem;
}
.chk__subheading {
    font-size: 0.9rem;
    color: #6b7c6b;
    margin-bottom: 2rem;
}

/* ── Steps ── */
.chk__steps {
    display: flex;
    align-items: center;
    margin-bottom: 2.5rem;
}
.chk__step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: #9aab9a;
}
.chk__step.done   { color: #2d7a45; }
.chk__step.active { color: #2d7a45; }
.chk__step-num {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 2px solid #c8e0c8;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 800; background: #fff;
}
.chk__step.done .chk__step-num {
    background: #e8f5ed; border-color: #2d7a45; color: #2d7a45;
}
.chk__step.active .chk__step-num {
    background: #2d7a45; border-color: #2d7a45; color: #fff;
}
.chk__step-divider {
    flex: 1; height: 2px;
    background: #e8f0e4; margin: 0 0.6rem;
}

/* ── Form Cards ── */
.chk__form-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    padding: 1.75rem;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    margin-bottom: 1.5rem;
}
.chk__form-card-title {
    font-size: 1rem;
    font-weight: 800;
    color: #1a2e1a;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f5ee;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.chk__form-card-num {
    width: 26px; height: 26px;
    background: #e8f5ed; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 800; color: #2d7a45;
    flex-shrink: 0;
}

/* ── Fields ── */
.chk__label {
    display: block;
    font-size: 0.78rem;
    font-weight: 700;
    color: #4a6b4a;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.4rem;
}
.chk__input,
.chk__textarea,
.chk__select {
    width: 100%;
    padding: 0.72rem 1rem;
    border: 1.5px solid #d4e8d0;
    border-radius: 10px;
    font-size: 0.92rem;
    color: #1a2e1a;
    background: #fafff8;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
}
.chk__input:focus,
.chk__textarea:focus,
.chk__select:focus {
    border-color: #2d7a45;
    box-shadow: 0 0 0 3px rgba(45,122,69,0.1);
    background: #fff;
}
.chk__input.is-invalid,
.chk__textarea.is-invalid,
.chk__select.is-invalid {
    border-color: #e74c3c;
    box-shadow: 0 0 0 3px rgba(231,76,60,0.1);
}
.chk__error {
    font-size: 0.78rem;
    color: #e74c3c;
    margin-top: 4px;
    display: block;
}
.chk__textarea { resize: vertical; min-height: 90px; }
.chk__input[readonly] { background: #f0f5ee; color: #6b7c6b; cursor: not-allowed; }

/* ── User Banner ── */
.chk__user-banner {
    background: #e8f5ed;
    border: 1px solid #a8d5b5;
    border-radius: 10px;
    padding: 0.85rem 1.1rem;
    display: flex; align-items: center; gap: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 0.88rem; color: #2d7a45; font-weight: 500;
}

/* ── Summary Card ── */
.chk__summary-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8f0e4;
    padding: 1.75rem;
    box-shadow: 0 2px 16px rgba(60,120,60,0.06);
    position: sticky; top: 20px;
}
.chk__summary-title {
    font-size: 1rem;
    font-weight: 800;
    color: #1a2e1a;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f0f5ee;
}

/* ── Cart Item in Summary ── */
.chk__item {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f5ee;
}
.chk__item:last-of-type { border-bottom: none; }
.chk__item-img {
    width: 50px; height: 50px;
    object-fit: contain; background: #f4faf0;
    border-radius: 8px; padding: 5px;
    border: 1px solid #e8f0e4; flex-shrink: 0;
}
.chk__item-img-placeholder {
    width: 50px; height: 50px;
    background: #f4faf0; border-radius: 8px;
    border: 1px solid #e8f0e4; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
}
.chk__item-name { font-size: 0.88rem; font-weight: 700; color: #1a2e1a; line-height: 1.3; }
.chk__item-meta { font-size: 0.75rem; color: #6b7c6b; }
.chk__item-price { font-size: 0.92rem; font-weight: 700; color: #2d7a45; margin-left: auto; white-space: nowrap; }

/* ── Totals ── */
.chk__total-row {
    display: flex; justify-content: space-between;
    font-size: 0.88rem; color: #4a6b4a;
    padding: 0.4rem 0;
    border-bottom: 1px solid #f5f5f5;
}
.chk__total-row:last-of-type { border-bottom: none; }
.chk__total-row.grand {
    font-size: 1.05rem; font-weight: 800; color: #1a2e1a;
    border-top: 2px solid #e8f0e4; border-bottom: none;
    padding-top: 0.75rem; margin-top: 0.5rem;
}
.chk__total-row.grand span:last-child { color: #2d7a45; }

/* ── Place Order Button ── */
.chk__place-btn {
    display: block; width: 100%;
    padding: 0.9rem; margin-top: 1.25rem;
    background: #2d7a45; color: #fff;
    font-weight: 800; font-size: 1rem;
    border: none; border-radius: 10px;
    text-align: center; cursor: pointer;
    transition: background 0.2s; font-family: inherit;
}
.chk__place-btn:hover:not(:disabled) { background: #245e36; }
.chk__place-btn:disabled { background: #9aab9a; cursor: not-allowed; }

/* ── Note box ── */
.chk__note {
    background: #f4faf0;
    border: 1px solid #c8e0c8;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.8rem; color: #4a6b4a;
    margin-top: 0.75rem;
}

/* ── Back link ── */
.chk__back-link {
    display: inline-flex; align-items: center; gap: 0.4rem;
    color: #2d7a45; font-size: 0.88rem; font-weight: 600;
    text-decoration: none; margin-bottom: 1.5rem;
}
.chk__back-link:hover { color: #245e36; }

@media (max-width: 576px) {
    .chk__steps span { display: none; }
}
</style>

<section class="chk__section">
    <div class="container">

        {{-- Back --}}
        <a href="{{ route('cart.index') }}" class="chk__back-link">← Back to Cart</a>

        <h1 class="chk__heading">Checkout</h1>
        <p class="chk__subheading">Complete your details to place your order</p>

        {{-- Steps --}}
        <div class="chk__steps">
            <div class="chk__step done">
                <div class="chk__step-num">✓</div>
                <span>Cart</span>
            </div>
            <div class="chk__step-divider"></div>
            <div class="chk__step active">
                <div class="chk__step-num">2</div>
                <span>Details</span>
            </div>
            <div class="chk__step-divider"></div>
            <div class="chk__step">
                <div class="chk__step-num">3</div>
                <span>Confirm</span>
            </div>
        </div>

        <form id="checkoutForm">
        @csrf
        <div class="row g-4">

            {{-- ══════════════════════════════
                 LEFT — Forms
            ══════════════════════════════ --}}
            <div class="col-12 col-lg-7">

                {{-- Logged in as --}}
                <div class="chk__user-banner">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Ordering as&nbsp;<strong>{{ $customer->name }}</strong>
                    &nbsp;({{ $customer->email }})
                </div>

                {{-- 1. Contact Details --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">1</span>
                        Contact Details
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Full Name *</label>
                            <input type="text" name="name"
                                   class="chk__input @error('name') is-invalid @enderror"
                                   value="{{ old('name', $customer->name) }}"
                                   placeholder="Your full name" required>
                            @error('name')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Phone Number *</label>
                            <input type="tel" name="phone"
                                   class="chk__input @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $customer->phone) }}"
                                   placeholder="+91 XXXXX XXXXX" required>
                            @error('phone')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12">
                            <label class="chk__label">Email Address *</label>
                            <input type="email" name="email"
                                   class="chk__input @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}"
                                   placeholder="you@example.com" required>
                            @error('email')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- 2. Shipping Address --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">2</span>
                        Shipping Address
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="chk__label">Full Address *</label>
                            <textarea name="address"
                                      class="chk__textarea @error('address') is-invalid @enderror"
                                      placeholder="House / Flat no., Street, Village, Landmark, Area..."
                                      required>{{ old('address') }}</textarea>
                            @error('address')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">City *</label>
                            <input type="text" name="city"
                                   class="chk__input @error('city') is-invalid @enderror"
                                   value="{{ old('city') }}" placeholder="City / Town" required>
                            @error('city')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">State *</label>
                            <select name="state"
                                    class="chk__select @error('state') is-invalid @enderror" required>
                                <option value="">— Select State —</option>
                                @foreach([
                                    'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar',
                                    'Chhattisgarh','Goa','Gujarat','Haryana',
                                    'Himachal Pradesh','Jharkhand','Karnataka','Kerala',
                                    'Madhya Pradesh','Maharashtra','Manipur','Meghalaya',
                                    'Mizoram','Nagaland','Odisha','Punjab','Rajasthan',
                                    'Sikkim','Tamil Nadu','Telangana','Tripura',
                                    'Uttar Pradesh','Uttarakhand','West Bengal',
                                    'Delhi','Jammu & Kashmir','Ladakh'
                                ] as $state)
                                    <option value="{{ $state }}"
                                        {{ old('state') == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">PIN Code *</label>
                            <input type="text" name="pincode"
                                   class="chk__input @error('pincode') is-invalid @enderror"
                                   value="{{ old('pincode') }}"
                                   placeholder="6-digit PIN" maxlength="6" required>
                            @error('pincode')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Country</label>
                            <input type="text" name="country"
                                   class="chk__input" value="India" readonly>
                        </div>
                    </div>
                </div>

                {{-- 3. Notes --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">3</span>
                        Additional Notes
                        <small style="font-weight:400; font-size:0.78rem; color:#6b7c6b; text-transform:none;">(optional)</small>
                    </div>
                    <textarea name="notes" class="chk__textarea"
                              placeholder="Crop type, field conditions, preferred delivery time...">{{ old('notes') }}</textarea>
                    <div class="chk__note">
                        💡 Mention your crop and soil type — our agronomist will include personalised tips with your order.
                    </div>
                </div>

            </div>

            {{-- ══════════════════════════════
                 RIGHT — Order Summary
            ══════════════════════════════ --}}
            <div class="col-12 col-lg-5">
                <div class="chk__summary-card">
                    <div class="chk__summary-title">
                        Order Summary
                        <span style="font-size:0.78rem; font-weight:500; color:#6b7c6b; margin-left:6px;">
                            ({{ collect($cart)->sum('quantity') }} item(s))
                        </span>
                    </div>

                    {{-- Items --}}
                    @foreach($cart as $key => $item)
                    <div class="chk__item">
                        @if(!empty($item['image']))
                            <img src="{{ Storage::url($item['image']) }}"
                                 alt="{{ $item['name'] }}" class="chk__item-img">
                        @else
                            <div class="chk__item-img-placeholder">🌿</div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <div class="chk__item-name">{{ $item['name'] }}</div>
                            @if(!empty($item['variation']))
                                <div class="chk__item-meta">{{ $item['variation'] }}</div>
                            @endif
                            <div class="chk__item-meta">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        <div class="chk__item-price">
                            ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </div>
                    </div>
                    @endforeach

                    {{-- Totals --}}
                    <div style="margin-top:1rem;">
                        <div class="chk__total-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="chk__total-row">
                            <span>Shipping</span>
                            <span style="color:#2d7a45; font-weight:700;">
                                @if($shippingTotal > 0)
                                    ₹{{ number_format($shippingTotal, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div class="chk__total-row">
                            <span>Tax (GST)</span>
                            <span>Included</span>
                        </div>
                        <div class="chk__total-row grand">
                            <span>Total</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                   {{-- Payment Method Selector --}}
<div class="chk__form-card-title" style="margin-top:1.25rem;">
    <span class="chk__form-card-num">💳</span> Choose Payment Method
</div>
<div class="pay-methods">
    @foreach($paymentGateways as $index => $gateway)
        <label class="pay-method-label">
            <input type="radio" name="payment_method" value="{{ $gateway->gateway_name }}" {{ $index == 0 ? 'checked' : '' }}>
            @if($gateway->logo_url)
                <img src="{{ $gateway->logo_url }}"
                     onerror="this.style.display='none'"
                     style="filter:invert(1) sepia(1) saturate(5) hue-rotate(100deg)">
            @elseif($gateway->gateway_name == 'cod')
                💵
            @endif
            {{ $gateway->display_name }}
        </label>
    @endforeach
</div>

<p style="text-align:center; font-size:0.72rem; color:#9aab9a; margin-top:0.75rem; margin-bottom:0;">
    🔒 Your information is secure and encrypted
</p>

<button type="button" id="placeOrderBtn" class="chk__place-btn" onclick="startPayment()">
    Complete Purchase
</button>

                </div>
            </div>

        </div>
        </form>

    </div>
</section>

@endsection
@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
const cashfree = Cashfree({ mode: "sandbox" }); // 🔁 change to "production" for live

function getSelectedGateway() {
    return document.querySelector('input[name="payment_method"]:checked')?.value || 'razorpay';
}

function startPayment() {
    const btn  = document.getElementById('placeOrderBtn');
    const form = document.getElementById('checkoutForm');

    if (!form.checkValidity()) { form.reportValidity(); return; }

    btn.disabled    = true;
    btn.textContent = 'Processing...';

    const gateway = getSelectedGateway();

    if (gateway === 'cod') {
        startCodPayment(btn, form);
    } else if (gateway === 'cashfree') {
        startCashfreePayment(btn, form);
    } else {
        startRazorpayPayment(btn, form);
    }
}

// ── RAZORPAY ──────────────────────────────────────────────────────────
function startRazorpayPayment(btn, form) {
    const formData = new FormData(form);

    fetch('{{ route("order.razorpay") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) throw new Error(data.error || data.message || 'Something went wrong.');

        const options = {
            key:         data.key_id,
            amount:      data.amount,
            currency:    data.currency,
            name:        'Bharat Biomer',
            description: 'Order Payment',
            order_id:    data.razorpay_order_id,
            prefill:     { name: data.name, email: data.email, contact: data.phone },
            theme:       { color: '#2d7a45' },
            handler: function (response) {
                btn.textContent = 'Verifying Payment...';
                fetch('{{ route("order.payment.success") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        razorpay_order_id:   response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature:  response.razorpay_signature,
                    }),
                })
                .then(res => res.json().then(data => ({ ok: res.ok, data })))
                .then(({ ok, data }) => {
                    if (ok && data.redirect_url) window.location.href = data.redirect_url;
                    else throw new Error(data.error || 'Payment verification failed.');
                })
                .catch(err => { alert('❌ ' + err.message); resetBtn(btn); });
            },
            modal: { ondismiss: () => resetBtn(btn) }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', r => { alert('❌ Payment failed: ' + r.error.description); resetBtn(btn); });
        rzp.open();
    })
    .catch(err => { alert('❌ ' + err.message); resetBtn(btn); });
}

// ── CASHFREE ──────────────────────────────────────────────────────────
function startCashfreePayment(btn, form) {
    const formData = new FormData(form);

    fetch('{{ route("order.cashfree") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) throw new Error(data.error || 'Something went wrong.');

        btn.textContent = 'Opening Payment...';

        cashfree.checkout({
            paymentSessionId: data.payment_session_id,
            redirectTarget:   "_self",   // opens in same tab; Cashfree redirects back to return_url
        });
    })
    .catch(err => { alert('❌ ' + err.message); resetBtn(btn); });
}

// ── COD ───────────────────────────────────────────────────────────────
function startCodPayment(btn, form) {
    if (!confirm('Place order with Cash on Delivery? You will pay when the order is delivered.')) {
        resetBtn(btn);
        return;
    }

    const formData = new FormData(form);

    fetch('{{ route("order.cod") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (ok && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            let msg = data.error || data.message || 'Something went wrong.';
            if (data.errors) {
                // Combine all Laravel validation errors into one string
                msg = Object.values(data.errors).flat().join('\n');
            }
            throw new Error(msg);
        }
    })
    .catch(err => { alert('❌ ' + err.message); resetBtn(btn); });
}

function resetBtn(btn) {
    btn.disabled    = false;
    btn.textContent = '💳 Pay Now →';
}
</script>
@endpush
