@extends('layout.layout')

@php
    $title = 'Brands';
    $subTitle = 'Brands';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Brands</h5>
            <p class="text-secondary-light mb-0">Manage product brand names and logos.</p>
        </div>
        <a href="{{ route('dashboard.brands.create') }}" class="btn btn-primary btn-sm">Add Brand</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="0,1,6">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Logo</th>
                        <th>Brand Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $i => $brand)
                        <tr>
                            <td>{{ str_pad($brands->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="radius-8 border" style="width:48px;height:48px;object-fit:contain;">
                                @else
                                    <div class="radius-8 bg-neutral-200 border" style="width:48px;height:48px;"></div>
                                @endif
                            </td>
                            <td><span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $brand->name }}</span></td>
                            <td><code>{{ $brand->slug }}</code></td>
                            <td><span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">{{ $brand->products_count }} Products</span></td>
                            <td>{{ $brand->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Delete brand \'{{ addslashes($brand->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No brands yet. <a href="{{ route('dashboard.brands.create') }}">Add the first one.</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($brands->hasPages())
            <div class="px-3 py-3">{{ $brands->links() }}</div>
        @endif
    </div>
</div>
@endsection
