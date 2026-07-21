{{-- resources/views/dashboard/customers/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Customers';
    $subTitle = 'Customers';
    $script   = '<script>
                    let table = new DataTable("#customerTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        columnDefs: [{ orderable: false, targets: [0, 1, 8] }]
                    });
                    document.getElementById("checkAll").addEventListener("change", function () {
                        document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    });
                 </script>';
@endphp

@section('content')

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">All Customers</h5>
        <span class="badge bg-primary-100 text-primary-600 px-16 py-6 fw-medium">
            {{ $customers->total() }} Total
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
             {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="customerTable" data-page-length='10' style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label">S.L</label>
                            </div>
                        </th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Audience</th>
                        <th>Total Orders</th>
                        <th>Total Spent</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $i => $customer)
                    <tr>
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($customers->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center fw-bold text-primary-600" style="font-size:0.85rem; min-width:36px;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="text-md mb-0 fw-medium">{{ $customer->name }}</h6>
                                    <span class="text-sm text-secondary-light">ID #{{ $customer->id }}</span>
                                </div>
                            </div>
                        </td>

                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td>{{ $customer->audience_type ? ucfirst($customer->audience_type) : '—' }}</td>

                        <td>
                            <span class="bg-primary-focus text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ $customer->orders_count }} Orders
                            </span>
                        </td>

                        <td>
                            <span class="fw-semibold text-success-main">
                                ₹{{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                            </span>
                        </td>

                        <td>{{ $customer->created_at ? $customer->created_at->format('d M Y') : '—' }}</td>

                        <td>
                            <a href="{{ route('dashboard.customers.show', $customer->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="View Customer">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
