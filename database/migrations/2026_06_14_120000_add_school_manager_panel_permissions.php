<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * دسترسی‌های قابلیت‌های جدید مدیر مدرسه در پنل ادمین (نقش school-manager):
 * داشبورد، وضعیت تحصیلی، آمار مشاوره، ثبت نمرات، صفحه‌ی پیشرفت دانش‌آموز.
 */
return new class extends Migration {
    private array $keys = [
        'admin.school-manager.dashboard.view',
        'admin.school-manager.academic-status.view',
        'admin.school-manager.advising.view',
        'admin.school-manager.grades.manage',
        'admin.school-manager.student.progress.view',
    ];

    public function up(): void
    {
        $permissions = [];
        foreach ($this->keys as $key) {
            $permissions[] = Permission::firstOrCreate([
                'name'       => $key,
                'guard_name' => 'admin',
            ]);
        }

        // اختصاص به نقش مدیر مدرسه
        $role = Role::where('name', 'school-manager')->where('guard_name', 'admin')->first();
        if ($role) {
            $role->givePermissionTo($permissions);
        }

        // اطمینان از داشتن دسترسی توسط super admin
        $superAdmin = Role::where('name', 'super admin')->where('guard_name', 'admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        if (app()->bound(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        Permission::whereIn('name', $this->keys)->where('guard_name', 'admin')->delete();

        if (app()->bound(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
};
