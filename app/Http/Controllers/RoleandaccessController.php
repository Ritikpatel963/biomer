<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleandaccessController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function roleAaccess()
    {
        $roles = Role::paginate(10);
        return view('roleandaccess/roleAaccess', compact('roles'));
    }

    /**
     * Store a newly created role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        Role::create([
            'name' => strtolower($request->input('name')),
            'guard_name' => 'web',
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('roleAaccess')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing a role and assigning permissions.
     */
    public function editRole($id)
    {
        $role = Role::findOrFail($id);
        $this->authorizeSuperAdminRole($role);
        $permissions = Permission::all();

        // Group permissions by their module category (last word of permission name)
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission->name);
            $groupName = ucfirst(end($parts));
            $groupedPermissions[$groupName][] = $permission;
        }

        return view('roleandaccess/editRole', compact('role', 'groupedPermissions'));
    }

    /**
     * Update the specified role and its permissions.
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $this->authorizeSuperAdminRole($role);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'permissions' => 'nullable|array',
        ]);

        // Prevent modifying the name of the super-admin role to maintain system integrity
        if ($role->name === 'super-admin') {
            $roleName = 'super-admin';
        } else {
            $roleName = strtolower($request->input('name'));
        }

        $role->update([
            'name' => $roleName,
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        // Sync permissions
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roleAaccess')->with('success', 'Role and permissions updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'super-admin') {
            return redirect()->route('roleAaccess')->with('error', 'The super-admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roleAaccess')->with('success', 'Role deleted successfully.');
    }

    /**
     * Display users list for role assignment.
     */
    public function assignRole()
    {
        $users = User::paginate(10);
        $roles = Role::where('status', 'active')->get();
        return view('roleandaccess/assignRole', compact('users', 'roles'));
    }

    /**
     * Assign/update user's role.
     */
    public function updateUserRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => [
                'required',
                Rule::exists('roles', 'name')->where('status', 'active'),
            ],
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $roleName = $request->input('role_name');

        if (
            ($roleName === 'super-admin' || $user->hasRole('super-admin'))
            && ! $request->user()->hasRole('super-admin')
        ) {
            abort(403, 'Only a super admin can change super-admin assignments.');
        }

        // Sync Spatie role
        $user->syncRoles([$roleName]);

        // Sync legacy column for backward compatibility
        $user->update(['role' => $roleName]);

        return redirect()->route('assignRole')->with('success', 'Role assigned to user successfully.');
    }

    private function authorizeSuperAdminRole(Role $role): void
    {
        if ($role->name === 'super-admin' && ! request()->user()->hasRole('super-admin')) {
            abort(403, 'Only a super admin can modify the super-admin role.');
        }
    }
}
