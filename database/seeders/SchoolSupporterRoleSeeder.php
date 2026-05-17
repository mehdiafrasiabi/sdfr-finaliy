<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SchoolSupporterRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'school-supporter.dashboard',
            'school-supporter.students',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        $role = Role::firstOrCreate([
            'name'       => 'school-supporter',
            'guard_name' => 'admin',
        ]);

        $role->givePermissionTo($permissions);

        if (app()->bound(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
