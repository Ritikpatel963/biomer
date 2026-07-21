@extends('layout.layout')
@php
    $title='Admin Users List';
    $subTitle = 'User Management / Users List';
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

<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <h5 class="mb-0 text-primary-light">All Admin Users</h5>
        </div>
        @can('create roles')
        <a href="{{ route('addUser') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
            <i class="ri-add-line text-xl line-height-1"></i>
            Add New User
        </a>
        @endcan
    </div>
    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 80px;">S.L</th>
                        <th scope="col">Join Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="text-center">Assigned Roles</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/user-list/user-list' . (($user->id % 10) + 1) . '.png') }}" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
                                    <div class="flex-grow-1">
                                        <span class="text-md mb-0 fw-semibold text-primary-light">{{ $user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary-light">{{ $user->email }}</span>
                            </td>
                            <td class="text-center">
                                @if($user->roles->isEmpty())
                                    <span class="badge bg-neutral-100 text-neutral-600 px-12 py-6 text-xs radius-4">No Role Assigned</span>
                                @else
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary-100 text-primary-600 px-12 py-6 text-xs radius-4 fw-semibold m-1">
                                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    {{-- Delete User --}}
                                    @can('delete roles')
                                    @if($user->id !== auth()->id() && $user->email !== 'admin@gmail.com' && $user->email !== 'test@example.com')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the user account \'{{ $user->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Delete User"
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted text-xs">System Protected</span>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-24 text-secondary-light">
                                No admin users found in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                <span>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries</span>
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
