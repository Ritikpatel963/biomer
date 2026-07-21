<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_active_role_only_sees_its_authorized_admin_sections(): void
    {
        [$user] = $this->userWithRole('role-viewer', ['view roles']);

        $this->actingAs($user)
            ->get(route('roleAaccess'))
            ->assertOk()
            ->assertSee('Role & Access')
            ->assertDontSee('Blog Management');
    }

    public function test_view_permission_does_not_grant_create_permission(): void
    {
        [$user] = $this->userWithRole('role-viewer', ['view roles']);

        $this->actingAs($user)
            ->post(route('roleandaccess.store'), [
                'name' => 'content-editor',
                'status' => 'active',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('roles', ['name' => 'content-editor']);
    }

    public function test_create_permission_allows_role_creation(): void
    {
        [$user] = $this->userWithRole('role-creator', ['view roles', 'create roles']);

        $this->actingAs($user)
            ->post(route('roleandaccess.store'), [
                'name' => 'content-editor',
                'status' => 'active',
            ])
            ->assertRedirect(route('roleAaccess'));

        $this->assertDatabaseHas('roles', ['name' => 'content-editor']);
    }

    public function test_inactive_roles_cannot_enter_the_admin_panel(): void
    {
        [$user, $role] = $this->userWithRole('disabled-staff', ['view roles']);
        $role->update(['status' => 'inactive']);

        $this->actingAs($user)
            ->get(route('roleAaccess'))
            ->assertForbidden();
    }

    public function test_legacy_admin_role_is_reconciled_on_access(): void
    {
        $permission = Permission::findOrCreate('view roles', 'web');
        $role = Role::create([
            'name' => 'legacy-admin',
            'guard_name' => 'web',
            'status' => 'active',
        ]);
        $role->givePermissionTo($permission);

        $user = User::factory()->create(['role' => 'legacy-admin']);

        $this->actingAs($user)
            ->get(route('roleAaccess'))
            ->assertOk();

        $this->assertTrue($user->fresh()->hasRole('legacy-admin'));
    }

    private function userWithRole(string $roleName, array $permissions): array
    {
        $permissionModels = collect($permissions)
            ->map(fn (string $permission) => Permission::findOrCreate($permission, 'web'));

        $role = Role::create([
            'name' => $roleName,
            'guard_name' => 'web',
            'status' => 'active',
        ]);
        $role->syncPermissions($permissionModels);

        $user = User::factory()->create(['role' => $roleName]);
        $user->assignRole($role);

        return [$user, $role];
    }
}
