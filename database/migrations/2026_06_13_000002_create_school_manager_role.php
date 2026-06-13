<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * نقش «مدیر مدرسه» روی guard «admin».
 *
 * مدیر مدرسه با همین نقش وارد پنل admin می‌شود و فقط بخش‌های مربوط به
 * دانش‌آموزان مدرسه‌ی خودش را می‌بیند.
 */
return new class extends Migration {
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::firstOrCreate([
            'name'       => 'مدیر مدرسه',
            'guard_name' => 'admin',
        ]);

        $permissionKeys = [
            'admin.students.view',
            'admin.students.detail',
            'admin.study-session.view',
            'admin.daily-activities.view',
            'admin.advising-session.view',
            'admin.weekly-program.upload',
        ];

        $permissions = [];
        foreach ($permissionKeys as $key) {
            $permissions[] = Permission::firstOrCreate([
                'name'       => $key,
                'guard_name' => 'admin',
            ]);
        }

        $role->syncPermissions($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::where('name', 'مدیر مدرسه')->where('guard_name', 'admin')->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
