@extends('layout.layout')
@php
    $title = 'Payment Gateways';
    $subTitle = 'Settings - Payment Gateways';
@endphp

@section('content')

<div class="card h-100 p-0 radius-12">
    <div class="card-body p-24">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-24" role="alert">
                <div class="d-flex align-items-center">
                    
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-24" role="alert">
                <div class="d-flex align-items-center">
                    
                    <span>{{ $errors->first() }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="paymentGatewayStatus" class="alert d-none mb-24" role="alert"></div>

        <form action="{{ route('paymentGateway.update') }}" method="POST">
            @csrf

            <div class="row gy-4">

                {{-- ══════════════════════════════════════════════════
                    RAZORPAY PAYMENT GATEWAY
                ══════════════════════════════════════════════════ --}}
                <div class="col-xxl-6">
                    <div class="card radius-12 shadow-none border overflow-hidden">
                        <div class="card-header bg-neutral-100 border-bottom py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                            <div class="d-flex align-items-center gap-10">
                                <span class="w-36-px h-36-px bg-base rounded-circle d-flex justify-content-center align-items-center">
                                    
                                </span>
                                <span class="text-lg fw-semibold text-primary-light">Razorpay</span>
                            </div>
                            <div class="form-switch switch-primary d-flex align-items-center justify-content-center">
                                <input class="form-check-input js-gateway-toggle" type="checkbox" name="razorpay_enabled" value="1" data-gateway="razorpay"
                                    {{ ($gateways->firstWhere('gateway_name', 'razorpay')?->is_enabled) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="card-body p-24">
                            <div class="row gy-3">

                                <div class="col-sm-6">
                                    <span class="form-label fw-semibold text-primary-light text-md mb-8">Environment <span class="text-danger-600">*</span></span>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                            <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input radius-4 border border-neutral-500" type="radio" name="razorpay_environment" id="razorpay_sandbox" value="sandbox"
                                                    {{ ($gateways->firstWhere('gateway_name', 'razorpay')?->environment ?? 'sandbox') === 'sandbox' ? 'checked' : '' }}>
                                            </div>
                                            <label for="razorpay_sandbox" class="form-label fw-medium text-lg text-primary-light mb-0">Sandbox</label>
                                        </div>
                                        <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                            <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input radius-4 border border-neutral-500" type="radio" name="razorpay_environment" id="razorpay_production" value="production"
                                                    {{ ($gateways->firstWhere('gateway_name', 'razorpay')?->environment ?? 'sandbox') === 'production' ? 'checked' : '' }}>
                                            </div>
                                            <label for="razorpay_production" class="form-label fw-medium text-lg text-primary-light mb-0">Production</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-24 py-8 radius-8 w-100 text-center">
                                        Save Changes
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════════════
                    CASHFREE PAYMENT GATEWAY
                ══════════════════════════════════════════════════ --}}
                <div class="col-xxl-6">
                    <div class="card radius-12 shadow-none border overflow-hidden">
                        <div class="card-header bg-neutral-100 border-bottom py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                            <div class="d-flex align-items-center gap-10">
                                <span class="w-36-px h-36-px bg-base rounded-circle d-flex justify-content-center align-items-center">
                                    
                                </span>
                                <span class="text-lg fw-semibold text-primary-light">Cashfree</span>
                            </div>
                            <div class="form-switch switch-primary d-flex align-items-center justify-content-center">
                                <input class="form-check-input js-gateway-toggle" type="checkbox" name="cashfree_enabled" value="1" data-gateway="cashfree"
                                    {{ ($gateways->firstWhere('gateway_name', 'cashfree')?->is_enabled) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="card-body p-24">
                            <div class="row gy-3">

                                <div class="col-sm-6">
                                    <span class="form-label fw-semibold text-primary-light text-md mb-8">Environment <span class="text-danger-600">*</span></span>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                            <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input radius-4 border border-neutral-500" type="radio" name="cashfree_environment" id="cashfree_sandbox" value="sandbox"
                                                    {{ ($gateways->firstWhere('gateway_name', 'cashfree')?->environment ?? 'sandbox') === 'sandbox' ? 'checked' : '' }}>
                                            </div>
                                            <label for="cashfree_sandbox" class="form-label fw-medium text-lg text-primary-light mb-0">Sandbox</label>
                                        </div>
                                        <div class="d-flex align-items-center gap-10 fw-medium text-lg">
                                            <div class="form-check style-check d-flex align-items-center">
                                                <input class="form-check-input radius-4 border border-neutral-500" type="radio" name="cashfree_environment" id="cashfree_production" value="production"
                                                    {{ ($gateways->firstWhere('gateway_name', 'cashfree')?->environment ?? 'sandbox') === 'production' ? 'checked' : '' }}>
                                            </div>
                                            <label for="cashfree_production" class="form-label fw-medium text-lg text-primary-light mb-0">Production</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-24 py-8 radius-8 w-100 text-center">
                                        Save Changes
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══════════════════════════════════════════════════
                COD - CASH ON DELIVERY
            ══════════════════════════════════════════════════ --}}
            <div class="row gy-4 mt-2">
                <div class="col-xxl-6">
                    <div class="card radius-12 shadow-none border overflow-hidden">
                        <div class="card-header bg-neutral-100 border-bottom py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                            <div class="d-flex align-items-center gap-10">
                                <span class="w-36-px h-36-px bg-base rounded-circle d-flex justify-content-center align-items-center">
                                    
                                </span>
                                <span class="text-lg fw-semibold text-primary-light">Cash on Delivery (COD)</span>
                            </div>
                            <div class="form-switch switch-primary d-flex align-items-center justify-content-center">
                                <input class="form-check-input js-gateway-toggle" type="checkbox" name="cod_enabled" value="1" data-gateway="cod"
                                    {{ ($gateways->firstWhere('gateway_name', 'cod')?->is_enabled) ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="card-body p-24">
                            <div class="row gy-3">
                                <div class="col-12">
                                    <p class="text-primary-light mb-0">
                                        <strong>Note:</strong> Enable this option to allow customers to pay cash on delivery. No additional configuration needed.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var statusBox = document.getElementById('paymentGatewayStatus');
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function showStatus(message, isSuccess) {
        if (!statusBox) return;

        statusBox.className = 'alert mb-24 ' + (isSuccess ? 'alert-success' : 'alert-danger');
        statusBox.textContent = message;

        window.clearTimeout(showStatus.timer);
        showStatus.timer = window.setTimeout(function () {
            statusBox.className = 'alert d-none mb-24';
            statusBox.textContent = '';
        }, 3000);
    }

    document.querySelectorAll('.js-gateway-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            var previousState = !toggle.checked;

            toggle.disabled = true;

            fetch('{{ route('paymentGateway.status') }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    gateway_name: toggle.dataset.gateway,
                    is_enabled: toggle.checked ? 1 : 0
                })
            })
                .then(function (response) {
                    if (response.status === 419) {
                        throw new Error('Session expired. Please refresh the page and try again.');
                    }

                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    }).catch(function () {
                        return { ok: response.ok, data: { message: 'Unable to update payment gateway status.' } };
                    });
                })
                .then(function (result) {
                    if (!result.ok) {
                        throw new Error(result.data.message || 'Unable to update payment gateway status.');
                    }

                    showStatus(result.data.message || 'Payment gateway status updated.', true);
                })
                .catch(function (error) {
                    toggle.checked = previousState;
                    showStatus(error.message, false);
                })
                .finally(function () {
                    toggle.disabled = false;
                });
        });
    });
});
</script>

@endsection
