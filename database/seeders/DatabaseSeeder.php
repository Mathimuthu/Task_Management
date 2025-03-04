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
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], 
            [
                'name' => 'Jenitta',
                'mobile' => '1234567890',
                'password' => bcrypt('12345678'),
                'role' => 1,
            ]
        );        

        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

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

        $admin->syncRoles([$adminRole]);

        $admin->syncPermissions($adminRole->permissions);
    }
}
