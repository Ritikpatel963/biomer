<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure default admin users exist
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'super-admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'super-admin',
            ]
        );

        // Run all required seeders
        $this->call([
            RoleAndPermissionSeeder::class,
            PageSeeder::class,
            PaymentGatewaySeeder::class,
            SiteDataSeeder::class,
        ]);
    }
}
