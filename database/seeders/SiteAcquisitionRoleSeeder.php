<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            'acquisition.monitor',    // رصد برنامه و گزارش‌های هفته آزمایشی
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        $role = Role::firstOrCreate([
            'name'       => 'site acquisition',
            'guard_name' => 'admin',
        ]);

        $role->givePermissionTo($permissions);

        $sampleAdmin = Admin::query()
            ->where('email', 'trialacquisition@gmail.com')
            ->orWhere('email', 'siteacquisition@gmail.com')
            ->orWhere('mobile', '09120000002')
            ->first();

        if (!$sampleAdmin) {
            $sampleAdmin = new Admin();
        }

        $sampleAdmin->fill([
            'name'     => 'مشاور جذب یک هفته آزمایشی',
            'email'    => 'trialacquisition@gmail.com',
            'mobile'   => '09120000002',
            'password' => Hash::make('password'),
        ])->save();

        $sampleAdmin->syncRoles(['site acquisition']);
        $sampleAdmin->syncPermissions([]);
    }
}
