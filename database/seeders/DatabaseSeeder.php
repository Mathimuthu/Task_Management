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
                'role' => $adminRole->id,
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@gmail.com'], 
            [
                'name' => 'Michael Scott',
                'mobile' => '9876543210',
                'password' => bcrypt('12345678'),
                'role' => $managerRole->id,
            ]
        );

        $employee = User::updateOrCreate(
            ['email' => 'employee@gmail.com'], 
            [
                'name' => 'Jim Halpert',
                'mobile' => '5678901234',
                'password' => bcrypt('12345678'),
                'role' => $employeeRole->id,
            ]
        );

        // Assign Roles to Users (Fixing model_type issue)
        DB::table('model_has_roles')->insert([
            ['role_id' => $adminRole->id, 'model_id' => $admin->id, 'model_type' => User::class],
            ['role_id' => $managerRole->id, 'model_id' => $manager->id, 'model_type' => User::class],
            ['role_id' => $employeeRole->id, 'model_id' => $employee->id, 'model_type' => User::class],
        ]);

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

        // Sync Permissions for Each Role
        $admin->syncPermissions($adminRole->permissions);
        $manager->syncPermissions($managerRole->permissions);
        $employee->syncPermissions($employeeRole->permissions);
    }
}
