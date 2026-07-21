<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            ! Schema::hasTable('users')
            || ! Schema::hasTable('roles')
            || ! Schema::hasTable('model_has_roles')
        ) {
            return;
        }

        $modelKey = config('permission.column_names.model_morph_key', 'model_id');
        $roleKey = config('permission.column_names.role_pivot_key', 'role_id');

        $activeRoles = DB::table('roles')
            ->where('status', 'active')
            ->pluck('id', 'name');

        DB::table('users')
            ->whereNotNull('role')
            ->orderBy('id')
            ->each(function (object $user) use ($activeRoles, $modelKey, $roleKey): void {
                $roleId = $activeRoles->get($user->role);

                if (! $roleId) {
                    return;
                }

                $alreadyAssigned = DB::table('model_has_roles')
                    ->where('model_type', User::class)
                    ->where($modelKey, $user->id)
                    ->exists();

                if (! $alreadyAssigned) {
                    DB::table('model_has_roles')->insertOrIgnore([
                        $roleKey => $roleId,
                        'model_type' => User::class,
                        $modelKey => $user->id,
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Existing legacy assignments cannot be distinguished safely from
        // assignments created through the admin panel, so rollback is a no-op.
    }
};
