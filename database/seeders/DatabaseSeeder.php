<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // Create Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], 
            [
                'name' => 'Jenitta',
                'mobile' => '1234567890',
                'password' => bcrypt('12345678'),
                'role' => 1,
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@gmail.com'], 
            [
                'name' => 'Michael Scott',
                'mobile' => '9876543210',
                'password' => bcrypt('12345678'),
                'role' => 2, // Assuming 2 represents Manager role
            ]
        );

        $employee = User::updateOrCreate(
            ['email' => 'employee@gmail.com'], 
            [
                'name' => 'Jim Halpert',
                'mobile' => '5678901234',
                'password' => bcrypt('12345678'),
                'role' => 3, // Assuming 3 represents Employee role
            ]
        );

        // Create Permissions
        $permissions = [
            'read users', 'write users', 'delete users',
            'read department', 'write department', 'delete department',
            'read role', 'write role', 'delete role',
            'read tasks', 'write tasks', 'delete tasks',
            'read mytasks', 'write mytasks', 'delete mytasks',
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $adminRole->givePermissionTo($perm);
        }

        // Assign Permissions to Manager
        $managerPermissions = [
            'read users', 'read department', 'read role', 
            'read tasks', 'write tasks', 'delete tasks',
            'read mytasks', 'write mytasks'
        ];

        foreach ($managerPermissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $managerRole->givePermissionTo($perm);
        }

        // Assign Permissions to Employee
        $employeePermissions = [
            'read mytasks', 'write mytasks'
        ];

        foreach ($employeePermissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $employeeRole->givePermissionTo($perm);
        }

        // Assign Roles to Users
        $admin->syncRoles([$adminRole]);
        $manager->syncRoles([$managerRole]);
        $employee->syncRoles([$employeeRole]);

        // Sync Permissions for Each Role
        $admin->syncPermissions($adminRole->permissions);
        $manager->syncPermissions($managerRole->permissions);
        $employee->syncPermissions($employeeRole->permissions);
    }
}
