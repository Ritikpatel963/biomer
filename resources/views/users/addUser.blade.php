@extends('layout.layout')
@php
    $title='Add Admin User';
    $subTitle = 'User Management / Add User';
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

<div class="card h-100 p-0 radius-12">
    <div class="card-body p-24">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-8 col-lg-10">
                <div class="card border">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-md fw-semibold mb-0 text-primary-light">Create New Admin User</h6>
                    </div>
                    <div class="card-body p-24">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="mb-20">
                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                <input type="text" name="name" class="form-control radius-8 @error('name') is-invalid @enderror" id="name" placeholder="Enter Full Name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-20">
                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email Address <span class="text-danger-600">*</span></label>
                                <input type="email" name="email" class="form-control radius-8 @error('email') is-invalid @enderror" id="email" placeholder="Enter email address" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-20">
                                <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span class="text-danger-600">*</span></label>
                                <input type="password" name="password" class="form-control radius-8 @error('password') is-invalid @enderror" id="password" placeholder="Enter a secure password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-20">
                                <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">Designate Role <span class="text-danger-600">*</span> </label>
                                <select name="role" class="form-control radius-8 form-select @error('role') is-invalid @enderror" id="role" required>
                                    <option value="" disabled selected>Select user role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex align-items-center justify-content-center gap-3 mt-32">
                                <a href="{{ route('usersList') }}" class="btn border border-neutral-300 bg-hover-neutral-100 text-secondary-light text-md px-40 py-11 radius-8">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
                                    Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
