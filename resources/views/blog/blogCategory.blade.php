@extends('layout.layout')

@php
    $title    = 'Blog Categories';
    $subTitle = 'Blog Categories';
@endphp

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-24" role="alert">
        <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row gy-4">

    {{-- Add Category --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Add Category</h5>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('blog-categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-16">
                        <label class="form-label fw-semibold" for="name">
                            Category Name <span class="text-danger-600">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control border border-neutral-200 radius-8 @error('name') is-invalid @enderror"
                               placeholder="Enter category name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary-600 w-100 radius-8">
                        <i class="ri-add-line me-1"></i> Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">All Categories</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 admin-data-table" data-page-length="10" data-no-sort-targets="2">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-24" style="width:60px;">#</th>
                                <th>Category Name</th>
                                <th class="text-center pe-24" style="width:140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="ps-24 text-neutral-500">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $category->name }}</td>
                                    <td class="text-center pe-24">
                                        <div class="d-flex align-items-center justify-content-center gap-8">

                                            {{-- Edit --}}
                                            <button type="button"
                                                    class="btn btn-sm btn-primary-100 text-primary-600 px-12 py-6 radius-8"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal"
                                                    onclick="setEdit({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                                <i class="ri-edit-2-line"></i>
                                            </button>

                                            {{-- Delete -- each row has its own inline form --}}
                                            <form action="/blog/categories/{{ $category->id }}/delete" method="POST"
                                                  onsubmit="return confirm('Delete \'{{ addslashes($category->name) }}\'? This cannot be undone.')">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger-100 text-danger-600 px-12 py-6 radius-8">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-32 text-neutral-400">
                                        No categories yet. Add one!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($categories->hasPages())
                <div class="card-footer border-top p-16 d-flex justify-content-end">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content radius-12">
            <div class="modal-header border-bottom px-24 py-16">
                <h5 class="modal-title mb-0">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" action="" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <label class="form-label fw-semibold" for="edit_name">
                        Category Name <span class="text-danger-600">*</span>
                    </label>
                    <input type="text"
                           id="edit_name"
                           name="name"
                           class="form-control border border-neutral-200 radius-8"
                           placeholder="Enter category name"
                           required>
                </div>
                <div class="modal-footer border-top px-24 py-16 gap-12">
                    <button type="button" class="btn btn-outline-secondary radius-8" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-600 radius-8 px-24">
                        <i class="ri-save-line me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function setEdit(id, name) {
        document.getElementById('editForm').action = '/blog/categories/' + id + '/update';
        document.getElementById('edit_name').value = name;
    }
</script>
@endpush
