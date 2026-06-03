@extends('layout.layout')

@php
    $title = 'Coupons';
    $subTitle = 'Coupons';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Coupons</h5>
            <p class="text-secondary-light mb-0">Manage promotional codes and usage limits.</p>
        </div>
        <a href="{{ route('dashboard.coupons.create') }}" class="btn btn-primary btn-sm">Add Coupon</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="8">
                <thead>
                    <tr>
                        <th style="width:70px;">S.L</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order Amount</th>
                        <th>Stats</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th style="width:160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $i => $coupon)
                        <tr>
                            <td>{{ str_pad($coupons->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td><strong>{{ $coupon->code }}</strong></td>
                            <td><span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm text-capitalize">{{ $coupon->type }}</span></td>
                            <td>{{ $coupon->type == 'percent' ? $coupon->value . '%' : 'Rs. ' . number_format($coupon->value, 2) }}</td>
                            <td>Rs. {{ number_format($coupon->min_order_amount, 2) }}</td>
                            <td class="text-muted small">Used: {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/'.$coupon->usage_limit : '' }}</td>
                            <td>
                                @if($coupon->is_active)
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                @else
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('d M Y') : 'Never' }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete coupon \'{{ addslashes($coupon->code) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">No coupons yet. <a href="{{ route('dashboard.coupons.create') }}">Add one.</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
            <div class="px-3 py-3">{{ $coupons->links() }}</div>
        @endif
    </div>
</div>
@endsection
