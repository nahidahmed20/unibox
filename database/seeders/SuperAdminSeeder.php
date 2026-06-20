<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Super Admin Role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $user = User::updateOrCreate(
            [
                'email' => 'superadmin@gmail.com',
            ],
            [
                'name'          => 'Super Admin',
                'username'      => 'superadmin',
                'phone'         => '01700000000',
                'type'     => 'super_admin',
                'status'        => 1,
                'password'      => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Role
        if (!$user->hasRole('Super Admin')) {
            $user->assignRole($superAdminRole);
        }
    }
}