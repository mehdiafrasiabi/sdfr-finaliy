<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * نقش «مشاور جذب تلفنی» و دسترسی‌های مربوطه.
 * این نقش بعد از ارسال لینک ثبت‌نام، همان مشاور جذب دانش‌آموز هم محسوب می‌شود.
 */
class PhoneAcquisitionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'phone-acquisition.consult', // پنل مشاور جذب تلفنی (ثبت تماس، صف، رسید)
            'phone-acquisition.manage',  // پنل مدیر آموزشی برای جذب تلفنی
            'acquisition.dashboard',
            'acquisition.contacts',
            'acquisition.student',
            'acquisition.monitor',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        $role = Role::firstOrCreate([
            'name'       => 'مشاور جذب تلفنی',
            'guard_name' => 'admin',
        ]);

        $role->givePermissionTo([
            'phone-acquisition.consult',
            'acquisition.dashboard',
            'acquisition.contacts',
            'acquisition.student',
            'acquisition.monitor',
        ]);

        $sampleAdmin = \App\Models\Admin::query()
            ->where('email', 'phoneacquisition@gmail.com')
            ->orWhere('email', 'phone.consultant@test.local')
            ->orWhere('mobile', '09120000003')
            ->first();

        if (!$sampleAdmin) {
            $sampleAdmin = new \App\Models\Admin();
        }

        $sampleAdmin->fill([
            'name'     => 'مشاور جذب تلفنی',
            'email'    => 'phoneacquisition@gmail.com',
            'mobile'   => '09120000003',
            'password' => bcrypt('password'),
        ])->save();

        $sampleAdmin->syncRoles(['مشاور جذب تلفنی']);
        $sampleAdmin->syncPermissions([]);

        // مدیر آموزشی به مدیریت جذب تلفنی دسترسی دارد
        $manager = Role::where('name', 'educational-manager')->where('guard_name', 'admin')->first();
        if ($manager) {
            $manager->givePermissionTo('phone-acquisition.manage');
        }
    }
}
