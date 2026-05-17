<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * فقط 4 نقش نهایی روی guard «admin» نگه داشته می‌شوند:
     *   - super admin            (مدیر کل)
     *   - educational-manager    (مدیر آموزشی)
     *   - مشاور تحصیلی          (مشاور تحصیلی)
     *   - site acquisition       (پشتیبان جذب)
     *
     * تمام نقش‌های دیگر روی همین guard حذف می‌شوند و کاربرانشان به
     * نقش «super admin» منتقل می‌شوند. permission‌های اختصاصی نقش
     * «پشتیبان تحصیلی» و «trial-supporter» نیز پاکسازی می‌شوند.
     */
    public function up(): void
    {
        $finalRoles = [
            'super admin',
            'educational-manager',
            'مشاور تحصیلی',
            'site acquisition',
        ];

        $rolesToRemove = DB::table('roles')
            ->where('guard_name', 'admin')
            ->whereNotIn('name', $finalRoles)
            ->pluck('id', 'name');

        if ($rolesToRemove->isEmpty()) {
            return;
        }

        $superAdminRoleId = DB::table('roles')
            ->where('guard_name', 'admin')
            ->where('name', 'super admin')
            ->value('id');

        if (! $superAdminRoleId) {
            // اگر «super admin» موجود نیست همان‌جا ساخته می‌شود تا کاربران آویزان نمانند.
            $superAdminRoleId = DB::table('roles')->insertGetId([
                'name'       => 'super admin',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $removeIds = $rolesToRemove->values()->all();

        DB::transaction(function () use ($removeIds, $superAdminRoleId) {
            // 1) انتقال کاربرانی که نقش حذف‌شده دارند به super admin
            $modelHasRoles = DB::table('model_has_roles')
                ->whereIn('role_id', $removeIds)
                ->get();

            foreach ($modelHasRoles as $row) {
                $alreadySuper = DB::table('model_has_roles')
                    ->where('role_id', $superAdminRoleId)
                    ->where('model_type', $row->model_type)
                    ->where('model_id', $row->model_id)
                    ->exists();

                if (! $alreadySuper) {
                    DB::table('model_has_roles')->insert([
                        'role_id'    => $superAdminRoleId,
                        'model_type' => $row->model_type,
                        'model_id'   => $row->model_id,
                    ]);
                }
            }

            // 2) پاکسازی پیوندهای روی نقش‌های حذف‌شدنی
            DB::table('model_has_roles')->whereIn('role_id', $removeIds)->delete();
            DB::table('role_has_permissions')->whereIn('role_id', $removeIds)->delete();

            // 3) حذف خود نقش‌ها
            DB::table('roles')->whereIn('id', $removeIds)->delete();
        });

        // 4) حذف permission‌های منسوخ مربوط به پشتیبان تحصیلی و trial
        $obsoletePermissions = [
            'view_students_for_academic_support',
            'view_reports_for_academic_support',
            'create_reports_for_academic_support',
            'edit_reports_for_academic_support',
            'delete_reports_for_academic_support',
            'view_report_monthlies_for_academic_support',
            'create_report_monthlies_for_academic_support',
            'edit_report_monthlies_for_academic_support',
            'delete_report_monthlies_for_academic_support',
            'view_exams_for_academic_support',
            'create_exams_for_academic_support',
            'edit_exams_for_academic_support',
            'delete_exams_for_academic_support',
            'publish_exams_for_academic_support',
            'grade_exams_for_academic_support',
            'view_report_cards_for_academic_support',
            'create_report_cards_for_academic_support',
            'edit_report_cards_for_academic_support',
            'delete_report_cards_for_academic_support',
            'publish_report_cards_for_academic_support',
            'academic support',
            'trial.view',
            'trial.manage',
            'trial.report',
        ];

        $permIds = DB::table('permissions')
            ->where('guard_name', 'admin')
            ->whereIn('name', $obsoletePermissions)
            ->pluck('id');

        if ($permIds->isNotEmpty()) {
            DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
            DB::table('model_has_permissions')->whereIn('permission_id', $permIds)->delete();
            DB::table('permissions')->whereIn('id', $permIds)->delete();
        }

        // 5) پاک‌سازی کش Spatie
        if (app()->bound(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    /**
     * بازگشت تنها نقش‌های حذف‌شده را بازسازی نمی‌کند (داده تاریخی است).
     * در صورت نیاز، seeder های قبلی را می‌توان دوباره اجرا کرد.
     */
    public function down(): void
    {
        // intentionally a no-op: previous data cannot be safely restored.
    }
};
