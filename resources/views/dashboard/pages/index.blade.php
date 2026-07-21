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
            <p class="text-secondary-light mb-0">Manage SEO fields for the fixed pages already available on the website.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3 mb-0">
            {{ session('error') }}
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
            <table class="table bordered-table admin-data-table mb-0" data-page-length="10" data-no-sort-targets="5">
                <thead>
                    <tr>
                        <th style="width:70px;">S.L</th>
                        <th>Page Title</th>
                        <th>URL</th>
                        <th>Meta Title</th>
                        <th>Status</th>
                        <th style="width:180px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $i => $page)
                        <tr>
                            <td>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td><span class="fw-semibold text-primary-light">{{ $page->title }}</span></td>
                            <td>
                                <a href="{{ url($page->admin_url) }}" target="_blank" class="text-primary-600 text-decoration-none">
                                    {{ $page->admin_url }}
                                </a>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($page->meta_title ?: 'Not set', 50) }}</td>
                            <td>
                                @if($page->status)
                                    <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">Active</span>
                                @else
                                    <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ url($page->admin_url) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPageModal{{ $page->id }}">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($pages as $page)
    <div class="modal fade" id="editPageModal{{ $page->id }}" tabindex="-1" aria-labelledby="editPageModalLabel{{ $page->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('dashboard.pages.update', $page) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="page_id" value="{{ $page->id }}">

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="editPageModalLabel{{ $page->id }}">Edit {{ $page->title }} SEO</h5>
                            <p class="text-secondary-light mb-0 text-sm">{{ $page->admin_url }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="title{{ $page->id }}">Page Title</label>
                                <input type="text" id="title{{ $page->id }}" name="title"
                                    class="form-control @if(old('page_id') == $page->id) @error('title') is-invalid @enderror @endif"
                                    value="{{ old('page_id') == $page->id ? old('title') : $page->title }}" required>
                                @if(old('page_id') == $page->id)
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL</label>
                                <input type="text" class="form-control bg-neutral-100" value="{{ $page->admin_url }}" readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="metaTitle{{ $page->id }}">Meta Title</label>
                                <input type="text" id="metaTitle{{ $page->id }}" name="meta_title" maxlength="255"
                                    class="form-control @if(old('page_id') == $page->id) @error('meta_title') is-invalid @enderror @endif"
                                    value="{{ old('page_id') == $page->id ? old('meta_title') : $page->meta_title }}"
                                    placeholder="SEO title shown in browser/search results">
                                @if(old('page_id') == $page->id)
                                    @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="metaKeyword{{ $page->id }}">Meta Keywords</label>
                                <textarea id="metaKeyword{{ $page->id }}" name="meta_keyword" rows="3" maxlength="500"
                                    class="form-control @if(old('page_id') == $page->id) @error('meta_keyword') is-invalid @enderror @endif"
                                    placeholder="keyword1, keyword2, keyword3">{{ old('page_id') == $page->id ? old('meta_keyword') : $page->meta_keyword }}</textarea>
                                @if(old('page_id') == $page->id)
                                    @error('meta_keyword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="metaDescription{{ $page->id }}">Meta Description</label>
                                <textarea id="metaDescription{{ $page->id }}" name="meta_description" rows="4" maxlength="500"
                                    class="form-control @if(old('page_id') == $page->id) @error('meta_description') is-invalid @enderror @endif"
                                    placeholder="Short description for search engines">{{ old('page_id') == $page->id ? old('meta_description') : $page->meta_description }}</textarea>
                                @if(old('page_id') == $page->id)
                                    @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="status{{ $page->id }}">Status</label>
                                <select id="status{{ $page->id }}" name="status" class="form-select">
                                    @php
                                        $selectedStatus = old('page_id') == $page->id ? old('status') : (int) $page->status;
                                    @endphp
                                    <option value="1" {{ (string) $selectedStatus === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (string) $selectedStatus === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-600">Update Page</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
    @if($errors->any() && old('page_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalElement = document.getElementById('editPageModal{{ old('page_id') }}');

                if (modalElement && window.bootstrap) {
                    new bootstrap.Modal(modalElement).show();
                }
            });
        </script>
    @endif
@endpush
