<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SiteAcquisitionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'acquisition.dashboard',  // مشاهده داشبورد پشتیبان جذب
            'acquisition.contacts',   // ثبت و مشاهده تماس‌ها
            'acquisition.student',    // مشاهده اطلاعات دانش‌آموز
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        $role = Role::firstOrCreate([
            'name'       => 'site acquisition',
            'guard_name' => 'admin',
        ]);

        $role->givePermissionTo($permissions);
    }
}
