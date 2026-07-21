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
            <p class="text-secondary-light mb-0">Organize footer columns, link order, status, and targets.</p>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFooterLinkModal">
            Add Link
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0">
            Please check the highlighted fields and try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table admin-data-table mb-0" data-page-length="25" data-no-sort-targets="6">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Position</th>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th style="width:170px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                        <tr>
                            <td><span class="bg-info-focus text-info-main px-12 py-4 rounded-pill fw-medium text-sm">{{ $link->section }}</span></td>
                            <td><span class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">{{ $link->position }}</span></td>
                            <td><strong>{{ $link->label }}</strong></td>
                            <td><code class="text-sm">{{ $link->url }}</code></td>
                            <td><small>{{ $link->target === '_blank' ? 'New Tab' : 'Same Tab' }}</small></td>
                            <td>
                                @if($link->is_active)
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                @else
                                    <span class="bg-secondary-focus text-secondary px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFooterLinkModal{{ $link->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('dashboard.footer-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Delete this footer link?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addFooterLinkModal" tabindex="-1" aria-labelledby="addFooterLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('dashboard.footer-links.store') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="create">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFooterLinkModalLabel">Add Footer Link</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('dashboard.settings.footer-links.partials.form', [
                        'link' => null,
                        'sections' => $sections,
                        'formKey' => 'create',
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-600">Save Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($links as $link)
    <div class="modal fade" id="editFooterLinkModal{{ $link->id }}" tabindex="-1" aria-labelledby="editFooterLinkModalLabel{{ $link->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('dashboard.footer-links.update', $link) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="edit">
                    <input type="hidden" name="link_id" value="{{ $link->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFooterLinkModalLabel{{ $link->id }}">Edit {{ $link->label }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('dashboard.settings.footer-links.partials.form', [
                            'link' => $link,
                            'sections' => $sections,
                            'formKey' => 'edit-' . $link->id,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-600">Update Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalId = '{{ old('form_type') === 'edit' ? 'editFooterLinkModal' . old('link_id') : 'addFooterLinkModal' }}';
                var modalElement = document.getElementById(modalId);

                if (modalElement && window.bootstrap) {
                    new bootstrap.Modal(modalElement).show();
                }
            });
        </script>
    @endif
@endpush
