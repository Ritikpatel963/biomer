<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Ensure accounts created before Spatie roles were introduced keep working.
     */
    public function ensureActiveRoleAssignment(): bool
    {
        if ($this->roles()->where('status', 'active')->exists()) {
            return true;
        }

        if (! $this->role || $this->role === 'user') {
            return false;
        }

        $legacyRole = Role::query()
            ->where('name', $this->role)
            ->where('status', 'active')
            ->first();

        if (! $legacyRole) {
            return false;
        }

        $this->syncRoles([$legacyRole]);

        return true;
    }
}
