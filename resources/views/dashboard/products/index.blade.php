@extends('layout.layout')

@php
    $title = 'Products';
    $subTitle = 'Products';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Products</h5>
            <p class="text-secondary-light mb-0">Manage catalog products, stock, pricing, and variations.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-secondary btn-sm">Categories</a>
            <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline-secondary btn-sm">Brands</a>
            <a href="{{ route('dashboard.tags.index') }}" class="btn btn-outline-secondary btn-sm">Tags</a>
            <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary btn-sm">Add Product</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="0,1,8">
                <thead>
                    <tr>
                        <th style="width:70px;">S.L</th>
                        <th style="width:76px;">Image</th>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th style="width:230px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $i => $product)
                        <tr>
                            <td>{{ str_pad($products->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($product->featured_image)
                                    <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="radius-8 border" style="width:48px;height:48px;object-fit:cover;">
                                @else
                                    <div class="radius-8 bg-neutral-200 border" style="width:48px;height:48px;"></div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-primary-light">{{ $product->name }}</div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                    @if($product->sku)
                                        <code>{{ $product->sku }}</code>
                                    @endif
                                    <span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                        {{ $product->variations->count() }} variations
                                    </span>
                                </div>
                            </td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold">Rs. {{ number_format($product->base_price, 2) }}</div>
                                <div class="text-sm text-secondary-light">Ship: {{ $product->shipping_charge > 0 ? 'Rs. ' . number_format($product->shipping_charge, 2) : 'Free' }}</div>
                            </td>
                            <td>
                                @if($product->variations->count())
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">
                                        {{ number_format($product->variations->sum('stock_quantity')) }}
                                    </span>
                                @else
                                    <span class="bg-secondary-focus text-secondary px-12 py-4 rounded-pill fw-medium text-sm">
                                        {{ number_format($product->stock_quantity ?? 0) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($product->status === 'active')
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                @elseif($product->status === 'inactive')
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                @else
                                    <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-medium text-sm">Draft</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product \'{{ addslashes($product->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                No products found. <a href="{{ route('dashboard.products.create') }}">Add your first product.</a>
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
@endsection
