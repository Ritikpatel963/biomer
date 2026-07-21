@extends('layout.layout')
@php
    $title = 'Edit Role & Permissions';
    $subTitle = 'Role & Access / Edit Role';
    $script = '';
@endphp

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-24 radius-8" role="alert">
        <i class="ri-checkbox-circle-line me-2 text-lg"></i>{{ session('success') }}
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

<form action="{{ route('roleandaccess.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row gy-4">
        {{-- Left Column: Role Details --}}
        <div class="col-lg-4">
            <div class="card radius-12 h-100">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0 text-primary-light">Role Details</h6>
                </div>
                <div class="card-body p-24">
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Role Name <span class="text-danger-600">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', ucwords(str_replace('-', ' ', $role->name))) }}" 
                               class="form-control radius-8 @error('name') is-invalid @enderror" 
                               placeholder="Enter Role Name" 
                               required 
                               {{ $role->name === 'super-admin' ? 'disabled' : '' }}>
                        @if($role->name === 'super-admin')
                            <small class="text-warning-main mt-8 d-block">Super-admin role name cannot be edited.</small>
                        @endif
                    </div>
                    
                    <div class="mb-20">
                        <label for="desc" class="form-label fw-semibold text-primary-light text-sm mb-8">Description</label>
                        <textarea class="form-control radius-8" 
                                  name="description" 
                                  id="desc" 
                                  rows="6" 
                                  placeholder="Write some role description...">{{ old('description', $role->description) }}</textarea>
                    </div>

                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status <span class="text-danger-600">*</span></label>
                        <div class="d-flex align-items-center flex-wrap gap-28">
                            <div class="form-check checked-success d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="status" id="ActiveRadio" value="active" {{ $role->status === 'active' ? 'checked' : '' }}>
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light text-sm d-flex align-items-center gap-1" for="ActiveRadio">
                                    <span class="w-8-px h-8-px bg-success-600 rounded-circle"></span>
                                    Active
                                </label>
                            </div>
                            <div class="form-check checked-danger d-flex align-items-center gap-2">
                                <input class="form-check-input" type="radio" name="status" id="InactiveRadio" value="inactive" {{ $role->status === 'inactive' ? 'checked' : '' }}>
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light text-sm d-flex align-items-center gap-1" for="InactiveRadio">
                                    <span class="w-8-px h-8-px bg-danger-600 rounded-circle"></span>
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-12 mt-32">
                        <a href="{{ route('roleAaccess') }}" class="btn btn-outline-secondary border-neutral-300 text-md px-24 py-12 radius-8 w-50 text-center">
                            Back
                        </a>
                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8 w-50">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Permissions Assignment Grid --}}
        <div class="col-lg-8">
            <div class="card radius-12 h-100">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="text-lg fw-semibold mb-0 text-primary-light">Role Permissions Matrix</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary-600 radius-8 px-12" id="selectAllBtn">Toggle All</button>
                </div>
                <div class="card-body p-24">
                    <p class="text-sm text-secondary-light mb-24">Configure which actions this role is authorized to perform across various system modules.</p>
                    
                    <div class="row gy-4">
                        @foreach($groupedPermissions as $module => $permissions)
                            <div class="col-md-6 col-xl-4">
                                <div class="border border-neutral-200 radius-12 p-16 bg-neutral-50 h-100 d-flex flex-column">
                                    <h6 class="text-md fw-bold text-primary-light mb-16 pb-8 border-bottom border-neutral-200 d-flex align-items-center gap-2">
                                        <i class="ri-shield-keyhole-line text-primary-600 text-lg"></i>
                                        {{ $module }}
                                    </h6>
                                    <div class="d-flex flex-column gap-16 flex-grow-1">
                                        @foreach($permissions as $permission)
                                            <div class="form-switch switch-primary d-flex align-items-center gap-3">
                                                <input class="form-check-input perm-checkbox" 
                                                       type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}" 
                                                       role="switch" 
                                                       id="perm_{{ $permission->id }}" 
                                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                       {{ $role->name === 'super-admin' ? 'disabled checked' : '' }}>
                                                <label class="form-check-label line-height-1 fw-semibold text-secondary-light text-sm" for="perm_{{ $permission->id }}">
                                                    {{ ucwords(explode(' ', $permission->name)[0]) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Inline script for "Select All" functionality --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAllBtn');
        const checkboxes = document.querySelectorAll('.perm-checkbox');
        
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                // If role is super-admin, checkboxes are disabled, do nothing
                if (checkboxes.length > 0 && checkboxes[0].disabled) return;
                
                let allChecked = true;
                checkboxes.forEach(cb => {
                    if (!cb.checked) allChecked = false;
                });
                
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                });
            });
        }
    });
</script>

@endsection
