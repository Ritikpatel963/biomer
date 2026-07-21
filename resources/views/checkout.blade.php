@extends('layout.frontlayout')
@section('title', 'Checkout – Bharat Biomer')

@section('content')
<section class="chk__section"
         data-cashfree-environment="{{ $cashfreeGateway?->environment ?? 'sandbox' }}"
         data-order-razorpay-url="{{ route('order.razorpay') }}"
         data-order-payment-success-url="{{ route('order.payment.success') }}"
         data-order-cashfree-url="{{ route('order.cashfree') }}"
         data-order-cod-url="{{ route('order.cod') }}">
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
                                   placeholder="Your full name" minlength="2" maxlength="255" required>
                            @error('name')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Phone Number *</label>
                            <input type="tel" name="phone"
                                   class="chk__input @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $customer->phone) }}"
                                   placeholder="+91 XXXXX XXXXX" pattern="[0-9+\-\s()]{7,20}" maxlength="20" required>
                            @error('phone')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12">
                            <label class="chk__label">Email Address *</label>
                            <input type="email" name="email"
                                   class="chk__input @error('email') is-invalid @enderror"
                                   value="{{ old('email', $customer->email) }}"
                                   placeholder="you@example.com" maxlength="255" required>
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
                                      minlength="10" maxlength="500" required>{{ old('address', $customer->address) }}</textarea>
                            @error('address')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">City *</label>
                            <input type="text" name="city"
                                   class="chk__input @error('city') is-invalid @enderror"
                                   value="{{ old('city', $customer->city) }}" placeholder="City / Town" minlength="2" maxlength="100" required>
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
                                        {{ old('state', $customer->state) == $state ? 'selected' : '' }}>
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
                                   value="{{ old('pincode', $customer->pincode) }}"
                                   placeholder="6-digit PIN" pattern="[1-9][0-9]{5}" maxlength="6" required>
                            @error('pincode')<span class="chk__error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="chk__label">Country</label>
                            <input type="text" name="country"
                                   class="chk__input" value="{{ old('country', $customer->country ?: 'India') }}" readonly>
                        </div>
                    </div>
                </div>

                {{-- 3. Notes --}}
                <div class="chk__form-card">
                    <div class="chk__form-card-title">
                        <span class="chk__form-card-num">3</span>
                        Additional Notes
                        <small class="chk__optional">(optional)</small>
                    </div>
                    <textarea name="notes" class="chk__textarea" maxlength="500"
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
                        <span class="chk__summary-count">
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
                            <div class="chk__item-img-placeholder"><iconify-icon icon="mdi:leaf" class="icon"></iconify-icon></div>
                        @endif
                        <div class="chk__item-info">
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
                    <div class="chk__totals">
                        <div class="chk__total-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="chk__total-row">
                            <span>Shipping</span>
                            <span class="chk__total-value--success">
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
<div class="chk__form-card-title chk__form-card-title--payment">
    <span class="chk__form-card-num"><iconify-icon icon="fa6-solid:credit-card" class="icon"></iconify-icon></span> Choose Payment Method
</div>
<div class="pay-methods">
    @forelse($paymentGateways as $index => $gateway)
        <label class="pay-method-label">
            <input type="radio" name="payment_method" value="{{ $gateway->gateway_name }}" {{ $index == 0 ? 'checked' : '' }}>
            @if($gateway->logo_url)
                <img src="{{ $gateway->logo_url }}"
                     onerror="this.style.display='none'"
                     class="pay-method-logo--light">
            @elseif($gateway->gateway_name == 'cod')
                <iconify-icon icon="mdi:cash" class="pay-method-icon"></iconify-icon>
            @endif
            {{ $gateway->display_name }}
        </label>
    @empty
        <div class="chk__error">No payment method is available right now. Please contact support.</div>
    @endforelse
</div>

<p class="chk__secure-note">
    <iconify-icon icon="ic:outline-lock" class="icon"></iconify-icon> Your information is secure and encrypted
</p>

<button type="button" id="placeOrderBtn" class="chk__place-btn" onclick="startPayment()" {{ $paymentGateways->isEmpty() ? 'disabled' : '' }}>
    <iconify-icon icon="fa6-solid:credit-card" class="btn-icon"></iconify-icon>
    <span>Pay Now</span>
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
@endpush

