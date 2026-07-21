@extends('layout.layout')
@php
    $title = 'Roles & Permissions';
    $subTitle = 'Role & Access';
    $script = '';
@endphp
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-24 radius-8" role="alert">
            <i class="ri-checkbox-circle-line me-2 text-lg"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-24 radius-8" role="alert">
            <i class="ri-error-warning-line me-2 text-lg"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-24 radius-8" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card h-100 p-0 radius-12">
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h5 class="mb-0 text-primary-light">All Roles</h5>
            </div>
            @can('create roles')
            <button type="button"
                class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="ri-add-line text-xl line-height-1"></i>
                Add New Role
            </button>
            @endcan
        </div>

        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 80px;">S.L</th>
                            <th scope="col">Create Date</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>
                                    {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $role->created_at ? $role->created_at->format('d M Y') : 'N/A' }}</td>
                                <td class="fw-semibold text-primary-light text-md">
                                    {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                </td>
                                <td>
                                    <p class="max-w-500-px mb-0 text-secondary">
                                        {{ $role->description ?? 'No description provided.' }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    @if($role->status === 'inactive')
                                        <span
                                            class="bg-danger-focus text-danger-600 border border-danger-main px-24 py-4 radius-4 fw-medium text-sm">Inactive</span>
                                    @else
                                        <span
                                            class="bg-success-focus text-success-600 border border-success-main px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        {{-- Edit permissions/details page --}}
                                        @can('edit roles')
                                        <a href="{{ route('roleandaccess.edit', $role->id) }}" title="Edit Role & Permissions"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <i class="ri-edit-2-line"></i>
                                        </a>
                                        @endcan

                                        {{-- Delete role --}}
                                        @can('delete roles')
                                        @if($role->name !== 'super-admin')
                                            <form action="{{ route('roleandaccess.destroy', $role->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete the role \'{{ $role->name }}\'? This will remove it from all users assigned to it.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete Role"
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-24 text-secondary-light">
                                    No roles found. Create one by clicking "Add New Role".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($roles->hasPages())
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span>Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} entries</span>
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>

    @can('create roles')
    <!-- Modal Start -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <form action="{{ route('roleandaccess.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Role Name <span
                                        class="text-danger-600">*</span></label>
                                <input type="text" name="name" class="form-control radius-8"
                                    placeholder="Enter Role Name (e.g. Content Creator)" required>
                            </div>
                            <div class="col-12 mb-20">
                                <label for="desc"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                                <textarea class="form-control" name="description" id="desc" rows="4"
                                    placeholder="Write some role description..."></textarea>
                            </div>

                            <div class="col-12 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status <span
                                        class="text-danger-600">*</span></label>
                                <div class="d-flex align-items-center flex-wrap gap-28">
                                    <div class="form-check checked-success d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="status" id="ActiveRadio"
                                            value="active" checked>
                                        <label
                                            class="form-check-label line-height-1 fw-medium text-secondary-light text-sm d-flex align-items-center gap-1"
                                            for="ActiveRadio">
                                            <span class="w-8-px h-8-px bg-success-600 rounded-circle"></span>
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check checked-danger d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="radio" name="status" id="InactiveRadio"
                                            value="inactive">
                                        <label
                                            class="form-check-label line-height-1 fw-medium text-secondary-light text-sm d-flex align-items-center gap-1"
                                            for="InactiveRadio">
                                            <span class="w-8-px h-8-px bg-danger-600 rounded-circle"></span>
                                            Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                                <button type="button"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8"
                                    data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
    @endcan

@endsection
