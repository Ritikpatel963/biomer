@extends('layout.layout')

@php
    $title = 'Footer Links';
    $subTitle = 'Manage footer navigation links';
@endphp

@section('content')
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title mb-1">Footer Links</h5>
            <p class="text-secondary-light mb-0">Organize the footer navigation links.</p>
        </div>
        <a href="{{ route('dashboard.footer-links.create') }}" class="btn btn-primary btn-sm">Add Link</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="6">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Position</th>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th style="width:160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td><span class="bg-info-focus text-info-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $link->section }}</span></td>
                            <td><span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">{{ $link->position }}</span></td>
                            <td><strong>{{ $link->label }}</strong></td>
                            <td><code class="text-sm">{{ $link->url }}</code></td>
                            <td><small>{{ $link->target }}</small></td>
                            <td>
                                @if($link->is_active)
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                @else
                                    <span class="bg-secondary-focus text-secondary px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard.footer-links.edit', $link) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('dashboard.footer-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Delete this link?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">No footer links added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($links->hasPages())
            <div class="px-3 py-3">{{ $links->links() }}</div>
        @endif
    </div>
</div>
@endsection
