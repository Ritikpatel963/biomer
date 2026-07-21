@extends('layout.layout')
@php
    $title = 'Assign Role';
    $subTitle = 'Role & Access / Assign Role';
    $script = '<script>
                             function assignUserRole(userId, roleName) {
                                 document.getElementById("assignUserId").value = userId;
                                 document.getElementById("assignRoleName").value = roleName;
                                 document.getElementById("assignRoleForm").submit();
                             }
                           </script>';
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
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h5 class="mb-0 text-primary-light">User Role Assignments</h5>
            </div>
        </div>

        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 80px;">S.L</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col" class="text-center">Role Permission</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/images/user-list/user-list' . (($user->id % 10) + 1) . '.png') }}"
                                            alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden">
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
                                        <span class="badge bg-neutral-200 text-neutral-600 px-12 py-6 text-xs radius-4">No Role
                                            Assigned</span>
                                    @else
                                        @foreach($user->roles as $role)
                                            <span
                                                class="badge bg-primary-100 text-primary-600 px-16 py-8 text-sm radius-4 fw-semibold m-1">
                                                {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('edit roles')
                                    <select
                                        class="form-select form-select-sm border-primary-600 text-primary-600 w-auto mx-auto radius-8 py-8 px-16 text-sm fw-semibold cursor-pointer"
                                        style="min-width: 150px; background-position: right 12px center;"
                                        onchange="if(this.value) assignUserRole({{ $user->id }}, this.value)">
                                        <option value="" disabled selected>Assign Role</option>
                                        @forelse($roles as $role)
                                            <option value="{{ $role->name }}">
                                                {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                            </option>
                                        @empty
                                            <option value="" disabled>No Active Roles</option>
                                        @endforelse
                                    </select>
                                    @else
                                        <span class="text-muted text-xs">View only</span>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-24 text-secondary-light">
                                    No users found in the database.
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

    {{-- Hidden Form for Role Assignment --}}
    <form id="assignRoleForm" action="{{ route('roleandaccess.assign') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="user_id" id="assignUserId">
        <input type="hidden" name="role_name" id="assignRoleName">
    </form>

@endsection
