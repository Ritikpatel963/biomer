@extends('layout.layout')

@php
    $title = 'Product Attributes';
    $subTitle = 'Ecommerce / Attributes';
@endphp

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-24">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Attribute</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.attributes.store') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Attribute Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Size, Color, Flavour" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Values</label>
                    <textarea name="values" class="form-control" rows="2" placeholder="Small, Medium, Large or Red, Blue, Green" required>{{ old('values') }}</textarea>
                    <small class="text-secondary-light">Separate values with commas or new lines.</small>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <input type="hidden" name="is_active" value="0">
                    <div class="form-check form-switch mb-8">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary-600">Create Attribute</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0">Attribute List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 admin-data-table" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Values</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attributes as $attribute)
                            <tr>
                                <td class="fw-semibold">{{ $attribute->name }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($attribute->values ?? [] as $value)
                                            <span class="badge bg-primary-50 text-primary-600">{{ $value }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($attribute->is_active)
                                        <span class="badge bg-success-100 text-success-600">Active</span>
                                    @else
                                        <span class="badge bg-neutral-200 text-secondary-light">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-success-600 edit-attribute-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAttributeModal"
                                        data-action="{{ route('dashboard.attributes.update', $attribute) }}"
                                        data-name="{{ $attribute->name }}"
                                        data-values="{{ implode(', ', $attribute->values ?? []) }}"
                                        data-sort-order="{{ $attribute->sort_order }}"
                                        data-is-active="{{ $attribute->is_active ? '1' : '0' }}"
                                    >
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.attributes.destroy', $attribute) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger-600" onclick="return confirm('Delete this attribute?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No global attributes yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attributes->hasPages())
                <div class="pt-3">{{ $attributes->links() }}</div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="editAttributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Attribute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editAttributeForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Attribute Name</label>
                            <input type="text" name="name" id="edit_attribute_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Values</label>
                            <textarea name="values" id="edit_attribute_values" class="form-control" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="sort_order" id="edit_attribute_sort_order" value="0">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check form-switch mb-8">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_attribute_active">
                            <label class="form-check-label" for="edit_attribute_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary-600">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
