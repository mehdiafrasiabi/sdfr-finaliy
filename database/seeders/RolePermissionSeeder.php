<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeder نهایی نقش‌های پنل ادمین — فقط ۴ نقش مجاز است:
 *   1) super admin            (مدیر کل)
 *   2) educational-manager    (مدیر آموزشی)
 *   3) مشاور تحصیلی          (مشاور تحصیلی)
 *   4) site acquisition       (پشتیبان جذب)
 *
 * permission‌های گروه «مشاور تحصیلی» در AcademicAdvisorPermissionSeeder
 * و permission‌های «پشتیبان جذب» در SiteAcquisitionRoleSeeder تعریف می‌شوند.
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // permission های پایه‌ای که هنوز در کد به آن‌ها reference داریم.
        // (نقش‌های جزئی قدیمی حذف شده‌اند؛ این permission ها به super admin اختصاص می‌یابند.)
        $permissions = [
            // مدیریت محصولات
            'view products', 'create products', 'edit products', 'delete products',

            // مدیریت سفارشات
            'view orders', 'process orders',

            // مدیریت دسته بندی
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view category_features', 'create category_features', 'edit category_features', 'delete category_features',

            // مدیریت مپ
            'view countries', 'create countries', 'edit countries', 'delete countries',
            'view states', 'create states', 'edit states', 'delete states',
            'view cities', 'create cities', 'edit cities', 'delete cities',

            // مدیریت کد تخفیف
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons',

            // مدیریت تراکنشات
            'view payments', 'process payments',

            // مدیریت کاربران
            'view users',

            // مدیریت دانش‌آموزان
            'view students', 'create students', 'edit students', 'delete students',
            'view personal_information', 'create personal_information', 'edit personal_information', 'delete personal_information',
            'view barnamehs', 'create barnamehs', 'edit barnamehs', 'delete barnamehs',
            'view reports', 'create reports', 'edit reports', 'delete reports',
            'view report_monthlies', 'create report_monthlies', 'edit report_monthlies', 'delete report_monthlies',

            // مدیریت درگاه پرداخت
            'view payment_methods', 'create payment_methods', 'edit payment_methods', 'delete payment_methods',

            // مدیریت استوری‌ها
            'view stories', 'create stories', 'edit stories', 'delete stories',

            // تنظیمات
            'view contact_us', 'create contact_us', 'edit contact_us', 'delete contact_us',

            // آزمون‌ها
            'view exams', 'create exams', 'edit exams', 'delete exams',
            'publish exams', 'grade exams',

            // کارنامه‌ها
            'view report_cards', 'create report_cards', 'edit report_cards', 'delete report_cards', 'publish report_cards',

            // مشاور تحصیلی (legacy keys هنوز در کد reference دارند)
            'view students with support info',
            'view student reports with support info',
            'view_exams_for_academic_advisor',
            'create_exams_for_academic_advisor',
            'publish_exams_for_academic_advisor',
            'upload weekly program',

            // بانک سوالات
            'manage_questions', 'view_questions',

            // آزمون‌های تایپی
            'manage_typed_exams', 'view_typed_exams',
            'assign_typed_exams', 'view_typed_exam_stats', 'view_typed_exam_results',

            // تیکت و پشتیبانی
            'admin.tickets.view', 'admin.tickets.reply',

            // مدیر آموزشی
            'admin.educational-manager.appointments.view',
            'admin.educational-manager.appointments.approve',
            'admin.educational-manager.reschedule.view',
            'admin.educational-manager.reschedule.manage',
            'admin.admin-users.manage',
            'admin.consultants.view',
            'admin.supporters.view',
            'admin.students.view',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'admin',
            ]);
        }

        // ────────────────────────────────────────────────────────────
        // ۱) مدیر کل (super admin) — تمام دسترسی‌ها
        // ────────────────────────────────────────────────────────────
        $superAdmin = Role::query()->firstOrCreate([
            'name'       => 'super admin',
            'guard_name' => 'admin',
        ]);
        $superAdmin->syncPermissions(Permission::where('guard_name', 'admin')->get());

        // ────────────────────────────────────────────────────────────
        // ۲) مدیر آموزشی (educational-manager) — فقط دسترسی‌های دو
        //    صفحهٔ تخصیص (پشتیبان جذب و مشاور) که در فاز E تعریف می‌شوند.
        // ────────────────────────────────────────────────────────────
        $educationalManager = Role::query()->firstOrCreate([
            'name'       => 'educational-manager',
            'guard_name' => 'admin',
        ]);
        $educationalManager->syncPermissions([
            'admin.educational-manager.appointments.view',
            'admin.educational-manager.appointments.approve',
            'admin.educational-manager.reschedule.view',
            'admin.educational-manager.reschedule.manage',
            'admin.admin-users.manage',
            'admin.consultants.view',
            'admin.supporters.view',
            'admin.students.view',
            'view students with support info',
        ]);

        // ────────────────────────────────────────────────────────────
        // ۳) مشاور تحصیلی — جزئیات permission در AcademicAdvisorPermissionSeeder
        //    اینجا فقط رول را تضمین می‌کنیم تا کاربر seed بشود.
        // ────────────────────────────────────────────────────────────
        Role::query()->firstOrCreate([
            'name'       => 'مشاور تحصیلی',
            'guard_name' => 'admin',
        ]);

        // ────────────────────────────────────────────────────────────
        // ۴) پشتیبان جذب — permission‌های اختصاصی در SiteAcquisitionRoleSeeder
        // ────────────────────────────────────────────────────────────
        Role::query()->firstOrCreate([
            'name'       => 'site acquisition',
            'guard_name' => 'admin',
        ]);

        // ────────────────────────────────────────────────────────────
        // کاربران نمونه برای ۴ نقش
        // ────────────────────────────────────────────────────────────
        $superAdminUser = Admin::query()->firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('password'),
                'mobile'   => '09940682693',
            ]
        );
        $superAdminUser->syncRoles(['super admin']);

        $educationalManagerUser = Admin::query()->firstOrCreate(
            ['email' => 'educationalmanager@gmail.com'],
            [
                'name'     => 'مدیر آموزشی',
                'password' => bcrypt('password'),
                'mobile'   => '09120000001',
            ]
        );
        $educationalManagerUser->syncRoles(['educational-manager']);

        $academicAdvisorUser = Admin::query()->firstOrCreate(
            ['email' => 'academicadvisor@gmail.com'],
            [
                'name'     => 'مشاور تحصیلی',
                'password' => bcrypt('password'),
                'mobile'   => '09121234567',
            ]
        );
        $academicAdvisorUser->syncRoles(['مشاور تحصیلی']);

        $siteAcquisitionUser = Admin::query()->firstOrCreate(
            ['email' => 'siteacquisition@gmail.com'],
            [
                'name'     => 'پشتیبان جذب',
                'password' => bcrypt('password'),
                'mobile'   => '09120000002',
            ]
        );
        $siteAcquisitionUser->syncRoles(['site acquisition']);

        // پاک‌سازی کش Spatie
        if (app()->bound(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
