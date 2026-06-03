@extends('layout.layout')
@section('title', 'Taxes (GST)')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <div>
            <h4 class="fw-semibold mb-1">Taxes (GST)</h4>
            <p class="text-secondary-light mb-0">View and manage the product GST/tax rates in your catalog.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Taxes (GST)</li>
            </ol>
        </nav>
    </div>

    <div class="row g-3 mb-24">
        @foreach([
            ['label' => 'Total products', 'value' => $counts['total'], 'color' => 'primary', 'icon' => 'ri-store-2-line'],
            ['label' => 'Products with tax', 'value' => $counts['with_tax'], 'color' => 'success', 'icon' => 'ri-percent-line'],
            ['label' => 'Products without tax', 'value' => $counts['without_tax'], 'color' => 'warning', 'icon' => 'ri-close-circle-line'],
        ] as $stat)
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-{{ $stat['color'] }}-focus text-{{ $stat['color'] }}-main fs-24" style="width:52px;height:52px;">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-secondary-light mb-1">{{ $stat['label'] }}</p>
                            <h5 class="fw-semibold mb-0">{{ $stat['value'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom bg-base">
            <form method="GET" action="{{ route('dashboard.taxes.index') }}" class="row g-3 align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0">GST / Tax rate list</h5>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 border-end-0"><i class="ri-search-line"></i></span>
                        <input type="search" name="q" class="form-control border-start-0" placeholder="Search product name, SKU or rate" value="{{ old('q', $search ?? '') }}">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-data-table" data-page-length="10" data-no-sort-targets="6">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Tax Rate</th>
                            <th>GST Rate</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $i => $product)
                            <tr>
                                <td>{{ str_pad($products->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="fw-semibold text-primary-light">{{ $product->name }}</div>
                                    <small class="text-secondary-light">{{ $product->brand->name ?? 'No brand' }} • {{ $product->category->name ?? 'No category' }}</small>
                                </td>
                                <td>{{ $product->sku ?? '—' }}</td>
                                <td>{{ number_format($product->tax_rate ?? 0, 2) }}%</td>
                                <td>{{ number_format($product->gst_rate ?? 0, 2) }}%</td>
                                <td>
                                    @if($product->status === 'active')
                                        <span class="badge bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                    @elseif($product->status === 'inactive')
                                        <span class="badge bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                    @else
                                        <span class="badge bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-medium text-sm">Draft</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary-light">
                                    No products found. Add products with GST rates to display here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="px-3 py-3">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
