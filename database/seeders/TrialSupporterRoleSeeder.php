<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TrialSupporterRoleSeeder extends Seeder
{
    public function run(): void
    {
        $newPermissions = [
            'trial.view',      // مشاهده دانش‌آموزان آزمایشی
            'trial.manage',    // مدیریت فرایند آزمایشی
            'trial.report',    // مشاهده گزارش‌ها
        ];

        foreach ($newPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        $role = Role::firstOrCreate([
            'name'       => 'trial-supporter',
            'guard_name' => 'admin',
        ]);

        $role->givePermissionTo($newPermissions);

        // پشتیبان آزمایشی به گزارش‌ها هم دسترسی داشته باشد
        $role->givePermissionTo([
            'view_students_for_academic_support',
            'view personal_information',
            'view_reports_for_academic_support',
            'create_reports_for_academic_support',
        ]);
    }
}
