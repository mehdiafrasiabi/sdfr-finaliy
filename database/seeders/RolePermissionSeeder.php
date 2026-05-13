<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //دسترسی های ادمین
        $permissions = [
            //مدیریت محصولات
            'view products',
            'create products',
            'edit products',
            'delete products',

            //مدیریت سفارشات
            'view orders',
            'process orders',

            //مدیریت دسته بندی
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            //ویژگی دسته بندی
            'view category_features',
            'create category_features',
            'edit category_features',
            'delete category_features',

            //مدیریت مپ
            //کشورها
            'view countries',
            'create countries',
            'edit countries',
            'delete countries',
            //استان ها
            'view states',
            'create states',
            'edit states',
            'delete states',
            //شهرها
            'view cities',
            'create cities',
            'edit cities',
            'delete cities',

            //مدیریت کد تخفیف
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',

            //مدیریت تراکنشات
            'view payments',
            'process payments',

            //مدیریت کاربران
            'view users',

            //مدیریت دانش  آموزان
            'view students',
            'create students',
            'edit students',
            'delete students',
            'view personal_information',
            'create personal_information',
            'edit personal_information',
            'delete personal_information',
            'view barnamehs',
            'create barnamehs',
            'edit barnamehs',
            'delete barnamehs',
            'view reports',
            'create reports',
            'edit reports',
            'delete reports',
            'view report_monthlies',
            'create report_monthlies',
            'edit report_monthlies',
            'delete report_monthlies',

            //مدیریت درگاه پزداخت
            'view payment_methods',
            'create payment_methods',
            'edit payment_methods',
            'delete payment_methods',

            //مدیریت استوری ها
            'view stories',
            'create stories',
            'edit stories',
            'delete stories',

            //مدیریت تنظیمات
            'view contact_us',
            'create contact_us',
            'edit contact_us',
            'delete contact_us',

            'view exams',
            'create exams',
            'edit exams',
            'delete exams',
            'publish exams', // برای انتشار آزمون
            'grade exams', // برای نمره دهی

            //مدیریت کارنامه ها
            'view report_cards',
            'create report_cards',
            'edit report_cards',
            'delete report_cards',
            'publish report_cards',


            // دسترسی‌های جدید و ترکیبی برای پشتیبان تحصیلی
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

            // دسترسی‌های جدید برای مشاور تحصیلی
            'view students with support info',
            'view student reports with support info',
            'view_exams_for_academic_advisor',
            'create_exams_for_academic_advisor',
            'publish_exams_for_academic_advisor',

            'upload weekly program',

            // دسترسی‌های مدیریت بانک سوالات (Manager)

            'manage_questions', // ساخت، ویرایش و حذف سوالات

            'view_questions', // مشاهده سوالات



            // دسترسی‌های مدیریت آزمون‌های تایپی (Manager)

            'manage_typed_exams', // ساخت، ویرایش و حذف آزمون‌های تایپی

            'view_typed_exams', // مشاهده آزمون‌های تایپی



            // دسترسی‌های اختصاص و آمار آزمون (Admin)

            'assign_typed_exams', // اختصاص آزمون به دانش‌آموز

            'view_typed_exam_stats', // مشاهده آمار آزمون‌ها

            'view_typed_exam_results', // مشاهده نتایج آزمون دانش‌آموزان

            // دسترسی‌های تیکت و پشتیبانی
            'admin.tickets.view',
            'admin.tickets.reply',

            // دسترسی‌های مدیر آموزشی
            'admin.educational-manager.appointments.view',
            'admin.educational-manager.appointments.approve',
            'admin.educational-manager.reschedule.view',
            'admin.educational-manager.reschedule.manage',
            'admin.admin-users.manage',
            'admin.consultants.view',
            'admin.supporters.view',
            'admin.students.view',
        ];

        //ایجاد دسترسی در دیتابیس
        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'admin'
                ]
            );
        }

        //تغریف نقش ها و دادن دسترسی ها
        $superAdmin = Role::query()->firstOrCreate([
            'name' => 'super admin',
            'guard_name' => 'admin'
        ]);
        $superAdmin->givePermissionTo(Permission::all());

        $productAdmin = Role::query()->firstOrCreate([
            'name' => 'product admin',
            'guard_name' => 'admin'
        ]);
        $productAdmin->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view category_features', 'create category_features', 'edit category_features', 'delete category_features',
            'view coupons','create coupons','edit coupons','delete coupons',

        ]);

        $orderAdmin = Role::query()->firstOrCreate([
            'name' => 'order admin',
            'guard_name' => 'admin'
        ]);
        $orderAdmin->givePermissionTo([
            'view orders', 'process orders',
        ]);

        $paymentAdmin = Role::query()->firstOrCreate([
            'name' => 'payment admin',
            'guard_name' => 'admin'
        ]);
        $paymentAdmin->givePermissionTo([
            'view payments', 'process payments',
        ]);


        $userAdmin = Role::query()->firstOrCreate([
            'name' => 'user admin',
            'guard_name' => 'admin'
        ]);
        $userAdmin->givePermissionTo([
            'view users'
        ]);

        $storyAdmin = Role::query()->firstOrCreate([
            'name' => 'story admin',
            'guard_name' => 'admin'
        ]);
        $storyAdmin->givePermissionTo([
            'view stories', 'create stories', 'edit stories', 'delete stories',
        ]);

        $paymentMethodAdmin = Role::query()->firstOrCreate([
            'name' => 'payment_method admin',
            'guard_name' => 'admin'
        ]);
        $paymentMethodAdmin->givePermissionTo([
            'view payment_methods', 'create payment_methods', 'edit payment_methods', 'delete payment_methods',
        ]);

        $contactUsAdmin = Role::query()->firstOrCreate([
            'name' => 'contactUs admin',
            'guard_name' => 'admin'
        ]);
        $contactUsAdmin->givePermissionTo([
            'view contact_us', 'create contact_us', 'edit contact_us', 'delete contact_us',
        ]);

        $studentAdmin = Role::query()->firstOrCreate([
            'name' => 'student admin',
            'guard_name' => 'admin'
        ]);
        $studentAdmin->givePermissionTo([
            'view contact_us', 'create contact_us', 'edit contact_us', 'delete contact_us', 'view students', 'create students', 'edit students', 'delete students', 'view personal_information',
            'create personal_information', 'edit personal_information', 'delete personal_information', 'view barnamehs', 'create barnamehs', 'edit barnamehs', 'delete barnamehs', 'view reports',
            'create reports', 'edit reports', 'delete reports', 'view report_monthlies', 'create report_monthlies', 'edit report_monthlies', 'delete report_monthlies',
        ]);


        $mapAdmin = Role::query()->firstOrCreate([
            'name' => 'map admin',
            'guard_name' => 'admin'
        ]);
        $mapAdmin->givePermissionTo([
            'view countries', 'create countries', 'edit countries', 'delete countries',
            'view states', 'create states','edit states','delete states',
        ]);

        // نقش پشتیبان جذب سایت
        $acquisitionSupporter = Role::query()->firstOrCreate([
            'name' => 'acquisition_supporter',
            'guard_name' => 'admin'
        ]);
        // permissions تعریف می‌شوند در پایین (firstOrCreate) و assign
        foreach ([
            'admin.acquisition.students.view',
            'admin.acquisition.calls.manage',
            'admin.acquisition.prediction.submit',
            'admin.acquisition.plan.write',
        ] as $perm) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }
        $acquisitionSupporter->syncPermissions([
            'admin.acquisition.students.view',
            'admin.acquisition.calls.manage',
            'admin.acquisition.prediction.submit',
            'admin.acquisition.plan.write',
        ]);

        // نقش جدید مشاور تحصیلی (advisor)
        $advisor = Role::query()->firstOrCreate([
            'name' => 'advisor',
            'guard_name' => 'admin'
        ]);
        $advisor->givePermissionTo([
            'view students with support info',
            'view student reports with support info',
            'view exams',
            'upload weekly program',
            'view_typed_exams',
            'view_questions',
            'assign_typed_exams',
            'view_typed_exam_stats',
            'view_typed_exam_results',
        ]);

        // حذف نقش‌های قدیمی academic support و academic_advisor در صورت وجود
        foreach (['academic support', 'academic_advisor'] as $legacy) {
            $r = Role::where(['name' => $legacy, 'guard_name' => 'admin'])->first();
            if ($r) { $r->delete(); }
        }

        // نقش جدید: مدیر آموزشی
        $educationalManager = Role::query()->firstOrCreate([
            'name' => 'educational-manager',
            'guard_name' => 'admin'
        ]);
        $educationalManager->givePermissionTo([
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

        $superAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'superadmin@gmail.com',
            ],
            [
                'name'=>'Super Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09940682693'
            ]
        );
        $superAdminUser->assignRole('super admin');

        $productAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'productadmin@gmail.com',
            ],
            [
                'name'=>'Product Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09940682692'
            ]
        );
        $productAdminUser->assignRole('product admin');

        $orderAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'orderadmin@gmail.com',
            ],
            [
                'name'=>'Order Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09940682576'
            ]
        );
        $orderAdminUser->assignRole('order admin');

        $paymentAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'paymentsadmin@gmail.com',
            ],
            [
                'name'=>'Payment Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'099406826939'
            ]
        );
        $paymentAdminUser->assignRole('payment admin');

        $mapAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'mapadmin@gmail.com',
            ],
            [
                'name'=>'Map Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09920682546'
            ]
        );
        $mapAdminUser->assignRole('map admin');

        $studentAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'studentadmin@gmail.com',
            ],
            [
                'name'=>'student Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09940342546'
            ]
        );
        $studentAdminUser->assignRole('student admin');
        $studentAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'harirbafan@gmail.com',
            ],
            [
                'name'=>'حریربافان',
                'password'=> bcrypt('password'),
                'mobile'=>'09952486571'
            ]
        );
        $studentAdminUser->assignRole('student admin');

        $storyAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'storyadmin@gmail.com',
            ],
            [
                'name'=>'story Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09140046546'
            ]
        );
        $storyAdminUser->assignRole('story admin');

        $contactUsAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'contactusadmin@gmail.com',
            ],
            [
                'name'=>'ContactUs Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09240082546'
            ]
        );
        $contactUsAdminUser->assignRole('contactUs admin');

        $userAdminUser = Admin::query()->firstOrCreate(
            [
                'email'=>'useradmin@gmail.com',
            ],
            [
                'name'=>'user Admin',
                'password'=> bcrypt('password'),
                'mobile'=>'09236982676'
            ]
        );
        $userAdminUser->assignRole('user admin');

        // پشتیبان جذب سایت (acquisition supporter)
        $acquisitionSupporterUser = Admin::query()->firstOrCreate(
            [ 'email' => 'acquisition@gmail.com' ],
            [
                'name' => 'پشتیبان جذب سایت',
                'password' => bcrypt('password'),
                'mobile' => '09123458795'
            ]
        );
        $acquisitionSupporterUser->syncRoles(['acquisition_supporter']);

        // مشاور تحصیلی (advisor)
        $advisorUser = Admin::query()->firstOrCreate(
            [ 'email' => 'advisor@gmail.com' ],
            [
                'name' => 'مشاور تحصیلی',
                'password' => bcrypt('password'),
                'mobile' => '09121234567'
            ]
        );
        $advisorUser->syncRoles(['advisor']);
    }
}
