<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $manageUsers = Permission::firstOrCreate([
            'name' => 'manage_users'
        ]);

        // Create roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'manager'
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password')
            ]
        );

        $admin->assignRole($adminRole);

        // Assign permission to manager role
        $managerRole->givePermissionTo($manageUsers);
        // Create manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password')
            ]
        );

        $manager->assignRole($managerRole);

        // Create random users
        User::factory(10)->create();
    }
}
