<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by module
        $permissionGroups = [
            'Products' => ['view products', 'create products', 'edit products', 'delete products'],
            'Orders' => ['view orders', 'edit orders', 'delete orders'],
            'Customers' => ['view customers', 'edit customers'],
            'Blogs' => ['view blogs', 'create blogs', 'edit blogs', 'delete blogs'],
            'Settings' => ['view settings', 'edit settings'],
            'Roles' => ['view roles', 'create roles', 'edit roles', 'delete roles'],
        ];

        // Create Permissions
        $allPermissionNames = [];
        foreach ($permissionGroups as $module => $permissions) {
            foreach ($permissions as $permissionName) {
                Permission::findOrCreate($permissionName, 'web');
                $allPermissionNames[] = $permissionName;
            }
        }

        // Create Roles and assign permissions
        
        // 1. Super Admin
        $superAdminRole = Role::findOrCreate('super-admin', 'web');
        $superAdminRole->update([
            'description' => 'Full access to all settings, modules, users and role assignments.',
            'status' => 'active',
        ]);
        $superAdminRole->syncPermissions($allPermissionNames);

        // 2. Admin
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->update([
            'description' => 'Read and edit access for products, orders, blogs, and customers. Cannot delete key system records.',
            'status' => 'active',
        ]);
        $adminRole->syncPermissions([
            'view products', 'create products', 'edit products',
            'view orders', 'edit orders',
            'view customers', 'edit customers',
            'view blogs', 'create blogs', 'edit blogs', 'delete blogs',
            'view settings',
            'view roles',
        ]);

        // 3. Manager
        $managerRole = Role::findOrCreate('manager', 'web');
        $managerRole->update([
            'description' => 'Staff access to manage shop inventory, view orders, and write blogs.',
            'status' => 'active',
        ]);
        $managerRole->syncPermissions([
            'view products',
            'view orders',
            'view customers',
            'view blogs', 'create blogs', 'edit blogs',
        ]);

        // Assign super-admin to standard admin users
        $adminEmails = ['admin@gmail.com', 'test@example.com'];
        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->assignRole($superAdminRole);
                // Sync legacy role column for compatibility
                $user->update(['role' => 'super-admin']);
            }
        }

        // Bring legacy users into the permission system. The old role column is
        // retained only for compatibility; Spatie roles are the source of truth.
        User::query()
            ->whereNotNull('role')
            ->whereDoesntHave('roles')
            ->each(function (User $user): void {
                $role = Role::query()
                    ->where('name', $user->role)
                    ->where('status', 'active')
                    ->first();

                if ($role) {
                    $user->assignRole($role);
                }
            });
    }
}
