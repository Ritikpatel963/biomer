@extends('layout.layout')

@php
    $title = 'Add Coupon';
    $subTitle = 'Coupons';
@endphp

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Add Coupon</h5>
            <p class="text-secondary-light mb-0">Create a promotional code for customer checkout.</p>
        </div>
        <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-outline-secondary btn-sm">Back to Coupons</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body">
        <form action="{{ route('dashboard.coupons.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. WELCOME10" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs.)</option>
                        <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control" value="{{ old('value') }}" step="0.01" min="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Minimum Order Amount <span class="text-danger">*</span></label>
                    <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', 0) }}" step="0.01" min="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" min="1">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Expiry Date</label>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                </div>

                <div class="col-12">
                    <x-toggle-switch name="is_active" id="isActiveSwitch" :checked="old('is_active', true)" label="Make Active" />
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create Coupon</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
