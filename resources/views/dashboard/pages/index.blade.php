@extends('layout.layout')

@php
    $title = 'Pages';
    $subTitle = 'Website Pages';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Website Pages</h5>
            <p class="text-secondary-light mb-0">Manage static pages, SEO fields, and publishing status.</p>
        </div>
        <a href="{{ route('dashboard.pages.create') }}" class="btn btn-primary btn-sm">Add New Page</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="5">
                <thead>
                    <tr>
                        <th style="width:70px;">S.L</th>
                        <th>Page Title</th>
                        <th>Slug</th>
                        <th>Meta Title</th>
                        <th>Status</th>
                        <th style="width:160px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $i => $page)
                        <tr>
                            <td>{{ str_pad($pages->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}</td>
                            <td><span class="fw-semibold text-primary-light">{{ $page->title }}</span></td>
                            <td><code class="text-sm">{{ $page->slug }}</code></td>
                            <td>{{ Str::limit($page->meta_title ?? 'Not set', 50) }}</td>
                            <td>
                                @if($page->status)
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                @else
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No pages found. <a href="{{ route('dashboard.pages.create') }}">Create your first page.</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
            <div class="px-3 py-3">{{ $pages->links() }}</div>
        @endif
    </div>
</div>
@endsection
