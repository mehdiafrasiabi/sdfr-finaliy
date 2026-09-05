<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;


class AcademicAdvisorPermissionSeeder extends Seeder

{

    /**
     * دسترسی‌های فارسی برای نقش مشاور تحصیلی
     * تمام بخش‌های پنل ادمین به صورت گروه‌بندی شده
     */

    public function run(): void

    {

        // پاک کردن کش Spatie

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // تعریف گروه‌های دسترسی با نام فارسی

        $permissionGroups = [

            'داشبورد' => [

                'مشاهده داشبورد' => 'admin.dashboard.view',

            ],


            'پروفایل' => [

                'مشاهده پروفایل' => 'admin.profile.view',

                'ویرایش پروفایل' => 'admin.profile.edit',

            ],


            'مدیریت دانش‌آموزان' => [

                'مشاهده لیست دانش‌آموزان' => 'admin.students.view',

                'جستجوی دانش‌آموز' => 'admin.students.search',

                'مشاهده جزئیات دانش‌آموز' => 'admin.students.detail',

                'ویرایش دانش‌آموز' => 'admin.students.edit',

            ],


            'گزارش روزانه' => [

                'مشاهده گزارش روزانه' => 'admin.daily-report.view',

                'ایجاد گزارش روزانه' => 'admin.daily-report.create',

                'ویرایش گزارش روزانه' => 'admin.daily-report.edit',

                'حذف گزارش روزانه' => 'admin.daily-report.delete',

            ],


            'برنامه‌ریزی دانش‌آموز' => [

                'مشاهده برنامه‌ها' => 'admin.student-plan.view',

                'ایجاد برنامه' => 'admin.student-plan.create',

                'ویرایش برنامه' => 'admin.student-plan.edit',

                'حذف برنامه' => 'admin.student-plan.delete',

            ],


            'گزارش وضعیت' => [

                'مشاهده گزارش وضعیت' => 'admin.report-status.view',

                'ایجاد گزارش وضعیت' => 'admin.report-status.create',

                'ویرایش گزارش وضعیت' => 'admin.report-status.edit',

            ],


            'گزارش تماس' => [

                'مشاهده گزارش تماس' => 'admin.report-calling.view',

                'ایجاد گزارش تماس' => 'admin.report-calling.create',

                'ویرایش گزارش تماس' => 'admin.report-calling.edit',

            ],


            'گزارش فعالیت روزانه' => [

                'مشاهده گزارش فعالیت' => 'admin.daily-activities.view',

                'ایجاد گزارش فعالیت' => 'admin.daily-activities.create',

                'ویرایش گزارش فعالیت' => 'admin.daily-activities.edit',

            ],


            'جلسات مطالعه' => [

                'مشاهده جلسات مطالعه' => 'admin.study-session.view',

                'مدیریت جلسات مطالعه' => 'admin.study-session.manage',

            ],


            'اتاق مشاوره' => [

                'مشاهده جلسات مشاوره' => 'admin.advising-session.view',

                'ایجاد جلسه مشاوره' => 'admin.advising-session.create',

                'ویرایش جلسه مشاوره' => 'admin.advising-session.edit',

                'حذف جلسه مشاوره' => 'admin.advising-session.delete',

                'آپلود برنامه هفتگی' => 'admin.weekly-program.upload',

            ],


            'آزمون‌های تایپی' => [

                'مشاهده آزمون‌ها' => 'admin.typed-exams.view',

                'اختصاص آزمون' => 'admin.typed-exams.assign',

                'مشاهده آمار آزمون' => 'admin.typed-exams.stats',

                'مشاهده نتایج دانش‌آموز' => 'admin.typed-exams.results',

                'ساخت و مدیریت آزمون اختصاصی' => 'admin.typed-exams.manage',

            ],


            'طبقه‌بندی آموزشی' => [

                'مشاهده طبقه‌بندی' => 'admin.classification.view',

                'مدیریت طبقه‌بندی' => 'admin.classification.manage',

            ],


            'بلاگ' => [

                'مشاهده بلاگ' => 'admin.blog.view',

                'ایجاد بلاگ' => 'admin.blog.create',

                'ویرایش بلاگ' => 'admin.blog.edit',

                'حذف بلاگ' => 'admin.blog.delete',

            ],


            'اعلان‌ها' => [

                'ارسال اعلان' => 'admin.notification.send',

            ],


            'تنظیمات' => [

                'مشاهده تماس با ما' => 'admin.contact-us.view',

                'ویرایش تماس با ما' => 'admin.contact-us.edit',

            ],


            'گوگل میت' => [

                'ایجاد جلسه گوگل میت' => 'admin.google-meet.create',

            ],


            'ارسال فایل به مدیر' => [

                'ارسال مستندات تماس' => 'admin.send-file.contact',

                'ارسال گزارش مطالعه' => 'admin.send-file.study-report',

            ],


            'لیست کارها' => [

                'مشاهده لیست کارها' => 'admin.todo.view',

                'مدیریت لیست کارها' => 'admin.todo.manage',

            ],


            'گزارش‌های ارسال نشده' => [

                'مشاهده گزارش‌های ارسال نشده' => 'admin.report-missing.view',

            ],


            'مشاوره جذب' => [

                'داشبورد مشاور جذب یک هفته آزمایشی' => 'acquisition.dashboard',

                'تماس‌ها و دانش‌آموزان جذب آزمایشی' => 'acquisition.contacts',

                'رصد برنامه و گزارش هفته آزمایشی' => 'acquisition.monitor',

                'پنل مشاور جذب تلفنی' => 'phone-acquisition.consult',

                'مدیریت جذب تلفنی' => 'phone-acquisition.manage',

            ],

        ];


        // ایجاد دسترسی‌ها در دیتابیس

        foreach ($permissionGroups as $groupName => $permissions) {

            foreach ($permissions as $persianName => $permissionKey) {

                Permission::firstOrCreate(

                    ['name' => $permissionKey, 'guard_name' => 'admin'],

                    ['name' => $permissionKey, 'guard_name' => 'admin']

                );

            }

        }


        // ایجاد نقش مشاور تحصیلی با دسترسی کامل

        $academicAdvisorRole = Role::firstOrCreate([

            'name' => 'مشاور تحصیلی',

            'guard_name' => 'admin'

        ]);


        // گرفتن تمام permission هایی که با admin. شروع می‌شوند

        $allAdminPermissions = Permission::where('guard_name', 'admin')
            ->where('name', 'like', 'admin.%')
            ->pluck('name')
            ->toArray();


        $academicAdvisorRole->syncPermissions($allAdminPermissions);


        $this->command->info('✅ نقش مشاور تحصیلی با تمام دسترسی‌ها ایجاد شد!');

        $this->command->info('📋 تعداد دسترسی‌ها: ' . count($allAdminPermissions));

    }


    /**
     * گرفتن ساختار گروه‌های دسترسی برای استفاده در UI
     */

    public static function getPermissionGroups(): array

    {

        return [

            'داشبورد' => [

                'مشاهده داشبورد' => 'admin.dashboard.view',

            ],


            'پروفایل' => [

                'مشاهده پروفایل' => 'admin.profile.view',

                'ویرایش پروفایل' => 'admin.profile.edit',

            ],


            'مدیریت دانش‌آموزان' => [

                'مشاهده لیست دانش‌آموزان' => 'admin.students.view',

                'جستجوی دانش‌آموز' => 'admin.students.search',

                'مشاهده جزئیات دانش‌آموز' => 'admin.students.detail',

                'ویرایش دانش‌آموز' => 'admin.students.edit',

            ],


            'گزارش روزانه' => [

                'مشاهده گزارش روزانه' => 'admin.daily-report.view',

                'ایجاد گزارش روزانه' => 'admin.daily-report.create',

                'ویرایش گزارش روزانه' => 'admin.daily-report.edit',

                'حذف گزارش روزانه' => 'admin.daily-report.delete',

            ],


            'برنامه‌ریزی دانش‌آموز' => [

                'مشاهده برنامه‌ها' => 'admin.student-plan.view',

                'ایجاد برنامه' => 'admin.student-plan.create',

                'ویرایش برنامه' => 'admin.student-plan.edit',

                'حذف برنامه' => 'admin.student-plan.delete',

            ],


            'گزارش وضعیت' => [

                'مشاهده گزارش وضعیت' => 'admin.report-status.view',

                'ایجاد گزارش وضعیت' => 'admin.report-status.create',

                'ویرایش گزارش وضعیت' => 'admin.report-status.edit',

            ],


            'گزارش تماس' => [

                'مشاهده گزارش تماس' => 'admin.report-calling.view',

                'ایجاد گزارش تماس' => 'admin.report-calling.create',

                'ویرایش گزارش تماس' => 'admin.report-calling.edit',

            ],


            'گزارش فعالیت روزانه' => [

                'مشاهده گزارش فعالیت' => 'admin.daily-activities.view',

                'ایجاد گزارش فعالیت' => 'admin.daily-activities.create',

                'ویرایش گزارش فعالیت' => 'admin.daily-activities.edit',

            ],


            'جلسات مطالعه' => [

                'مشاهده جلسات مطالعه' => 'admin.study-session.view',

                'مدیریت جلسات مطالعه' => 'admin.study-session.manage',

            ],


            'اتاق مشاوره' => [

                'مشاهده جلسات مشاوره' => 'admin.advising-session.view',

                'ایجاد جلسه مشاوره' => 'admin.advising-session.create',

                'ویرایش جلسه مشاوره' => 'admin.advising-session.edit',

                'حذف جلسه مشاوره' => 'admin.advising-session.delete',

                'آپلود برنامه هفتگی' => 'admin.weekly-program.upload',

            ],


            'آزمون‌های تایپی' => [

                'مشاهده آزمون‌ها' => 'admin.typed-exams.view',

                'اختصاص آزمون' => 'admin.typed-exams.assign',

                'مشاهده آمار آزمون' => 'admin.typed-exams.stats',

                'مشاهده نتایج دانش‌آموز' => 'admin.typed-exams.results',

                'ساخت و مدیریت آزمون اختصاصی' => 'admin.typed-exams.manage',

            ],


            'طبقه‌بندی آموزشی' => [

                'مشاهده طبقه‌بندی' => 'admin.classification.view',

                'مدیریت طبقه‌بندی' => 'admin.classification.manage',

            ],


            'بلاگ' => [

                'مشاهده بلاگ' => 'admin.blog.view',

                'ایجاد بلاگ' => 'admin.blog.create',

                'ویرایش بلاگ' => 'admin.blog.edit',

                'حذف بلاگ' => 'admin.blog.delete',

            ],


            'اعلان‌ها' => [

                'ارسال اعلان' => 'admin.notification.send',

            ],


            'تنظیمات' => [

                'مشاهده تماس با ما' => 'admin.contact-us.view',

                'ویرایش تماس با ما' => 'admin.contact-us.edit',

            ],


            'گوگل میت' => [

                'ایجاد جلسه گوگل میت' => 'admin.google-meet.create',

            ],


            'ارسال فایل به مدیر' => [

                'ارسال مستندات تماس' => 'admin.send-file.contact',

                'ارسال گزارش مطالعه' => 'admin.send-file.study-report',

            ],


            'لیست کارها' => [

                'مشاهده لیست کارها' => 'admin.todo.view',

                'مدیریت لیست کارها' => 'admin.todo.manage',

            ],


            'گزارش‌های ارسال نشده' => [

                'مشاهده گزارش‌های ارسال نشده' => 'admin.report-missing.view',

            ],


            'مشاوره جذب' => [

                'داشبورد مشاور جذب یک هفته آزمایشی' => 'acquisition.dashboard',

                'تماس‌ها و دانش‌آموزان جذب آزمایشی' => 'acquisition.contacts',

                'رصد برنامه و گزارش هفته آزمایشی' => 'acquisition.monitor',

                'پنل مشاور جذب تلفنی' => 'phone-acquisition.consult',

                'مدیریت جذب تلفنی' => 'phone-acquisition.manage',

            ],

        ];

    }


    /**
     * گرفتن نام فارسی دسترسی با کلید انگلیسی
     */

    public static function getPermissionPersianName(string $permissionKey): string

    {

        $groups = self::getPermissionGroups();

        foreach ($groups as $groupName => $permissions) {

            foreach ($permissions as $persianName => $key) {

                if ($key === $permissionKey) {

                    return $persianName;

                }

            }

        }

        return $permissionKey;

    }


    /**
     * گرفتن نام فارسی گروه با کلید دسترسی
     */

    public static function getGroupNameByPermission(string $permissionKey): string

    {

        $groups = self::getPermissionGroups();

        foreach ($groups as $groupName => $permissions) {

            foreach ($permissions as $persianName => $key) {

                if ($key === $permissionKey) {

                    return $groupName;

                }

            }

        }

        return 'سایر';

    }

}
