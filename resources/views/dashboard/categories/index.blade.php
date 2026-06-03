@extends('layout.layout')

@php
    $title = 'Categories';
    $subTitle = 'Categories';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Categories</h5>
            <p class="text-secondary-light mb-0">Manage product category structure.</p>
        </div>
        <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="0,6">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Products</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $i => $cat)
                        <tr>
                            <td>{{ str_pad($categories->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $cat->name }}</span>
                                @if($cat->parent_id)
                                    <span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm ms-1">Sub</span>
                                @endif
                            </td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>{{ $cat->parent->name ?? '-' }}</td>
                            <td><span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">{{ $cat->products_count }} Products</span></td>
                            <td>{{ $cat->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete category \'{{ addslashes($cat->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No categories yet. <a href="{{ route('dashboard.categories.create') }}">Add one.</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="px-3 py-3">{{ $categories->links() }}</div>
        @endif
    </div>
</div>
@endsection
