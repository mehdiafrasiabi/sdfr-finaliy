<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\City;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\PhoneRegistrationLink;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * دیتای تستیِ پنل مشاور جذب تلفنی:
 *   - یک «مشاور تستی» با نقش «مشاور جذب تلفنی»
 *   - شماره‌های نمونه در همهٔ وضعیت‌ها (صف، سررسیده، پیگیری، خاکستری، ثبت‌نام)
 *
 * اجرا:  php artisan db:seed --class=PhoneAcquisitionDemoSeeder
 * هر بار اجرا، دیتای دمویِ قبلی (با پیشوند «[تست]») پاک و دوباره ساخته می‌شود.
 */
class PhoneAcquisitionDemoSeeder extends Seeder
{
    const DEMO_PREFIX = '[تست] ';

    private array $firstNames = ['علی', 'محمد', 'زهرا', 'فاطمه', 'رضا', 'مهدی', 'سارا', 'نگار', 'امیر', 'حسین', 'یاسمن', 'پارسا', 'کیان', 'مریم', 'الناز'];
    private array $lastNames  = ['محمدی', 'احمدی', 'رضایی', 'حسینی', 'کریمی', 'موسوی', 'جعفری', 'صادقی', 'نوری', 'قاسمی'];

    public function run(): void
    {
        $consultant = $this->ensureConsultant();
        $this->cleanupOldDemo();

        $stateIds = State::query()->inRandomOrder()->limit(15)->pluck('id')->all();
        $cityIds  = City::query()->inRandomOrder()->limit(40)->pluck('id')->all();

        $makeLead = function (array $attrs) use ($consultant, $stateIds, $cityIds): PhoneLead {
            $lead = PhoneLead::create(array_merge([
                'full_name'      => self::DEMO_PREFIX . Arr::random($this->firstNames) . ' ' . Arr::random($this->lastNames),
                'mobile'         => '09' . rand(100000000, 999999999),
                'grade'          => rand(9, 12),
                'field'          => Arr::random(['math', 'experimental', 'human']),
                'state_id'       => $stateIds ? Arr::random($stateIds) : null,
                'city_id'        => $cityIds ? Arr::random($cityIds) : null,
                'status'         => PhoneLead::STATUS_ACTIVE,
                'attempts_count' => 0,
                'created_by'     => $consultant->id,
            ], $attrs));

            PhoneLeadAssignment::create([
                'phone_lead_id' => $lead->id,
                'admin_id'      => $consultant->id,
                'assigned_by'   => $consultant->id,
                'status'        => $lead->status === PhoneLead::STATUS_ACTIVE
                    ? PhoneLeadAssignment::STATUS_ACTIVE
                    : PhoneLeadAssignment::STATUS_DONE,
                'assigned_at'   => now(),
            ]);

            return $lead;
        };

        $addCall = function (PhoneLead $lead, array $c) use ($consultant): PhoneCall {
            return PhoneCall::create(array_merge([
                'phone_lead_id'  => $lead->id,
                'admin_id'       => $consultant->id,
                'attempt_number' => 1,
                'connected'      => false,
                'called_at'      => now(),
            ], $c));
        };

        // ۱) شماره‌های تازه (بدون تماس) → صف تماس‌های من
        for ($i = 0; $i < 5; $i++) {
            $makeLead([]);
        }

        // ۲) عدم‌پاسخِ سررسیده در همان روز → صف + داشبورد
        for ($i = 0; $i < 3; $i++) {
            $lead = $makeLead(['attempts_count' => 1, 'last_outcome' => 'no_answer', 'next_call_at' => now()->subHour()]);
            $addCall($lead, ['fail_reason' => 'no_answer', 'attempt_number' => 1, 'called_at' => now()->subHour()]);
        }

        // ۳) پیگیری موفقِ آینده → پیگیری‌های من (هنوز سررسید نشده)
        for ($i = 0; $i < 2; $i++) {
            $lead = $makeLead(['attempts_count' => 1, 'last_outcome' => 'follow_up', 'next_call_at' => now()->addDay()->setTime(10, 0)]);
            $addCall($lead, [
                'connected'             => true,
                'attempt_number'        => 1,
                'answered_at'           => now()->subDays(1),
                'talk_duration_seconds' => rand(90, 360),
                'spoke_with'            => Arr::random(['student', 'father', 'mother']),
                'result'                => 'follow_up',
                'follow_up_at'          => now()->addDay()->setTime(10, 0),
                'summary'               => 'علاقه‌مند بود؛ قرار شد فردا تماس بگیریم.',
                'called_at'             => now()->subDays(1),
            ]);
        }

        // ۴) پیگیری موفقِ سررسیده → پیگیری‌های من (سررسیده) + داشبورد
        for ($i = 0; $i < 2; $i++) {
            $lead = $makeLead(['attempts_count' => 1, 'last_outcome' => 'follow_up', 'next_call_at' => now()->subHours(3)]);
            $addCall($lead, [
                'connected'             => true,
                'attempt_number'        => 1,
                'answered_at'           => now()->subDays(2),
                'talk_duration_seconds' => rand(120, 400),
                'spoke_with'            => 'student',
                'result'                => 'follow_up',
                'follow_up_at'          => now()->subHours(3),
                'summary'               => 'قرار پیگیری امروز بود.',
                'called_at'             => now()->subDays(2),
            ]);
        }

        // ۵) خاکستری با عنوان «عدم پاسخ» (۲ روز × ۲ بار) → دانش‌آموزان من / خاکستری
        for ($i = 0; $i < 2; $i++) {
            $lead = $makeLead(['attempts_count' => 4, 'last_outcome' => 'no_answer', 'status' => PhoneLead::STATUS_DEAD, 'grey_reason' => 'no_answer']);
            $addCall($lead, ['fail_reason' => 'no_answer', 'attempt_number' => 1, 'called_at' => now()->subDays(1)->setTime(9, 0)]);
            $addCall($lead, ['fail_reason' => 'no_answer', 'attempt_number' => 2, 'called_at' => now()->subDays(1)->setTime(12, 0)]);
            $addCall($lead, ['fail_reason' => 'no_answer', 'attempt_number' => 3, 'called_at' => now()->setTime(9, 0)]);
            $addCall($lead, ['fail_reason' => 'no_answer', 'attempt_number' => 4, 'called_at' => now()->setTime(11, 0)]);
        }

        // ۶) خاکستری با عنوان «خاموش» (۴ بار) → دانش‌آموزان من / خاکستری
        $lead = $makeLead(['attempts_count' => 4, 'last_outcome' => 'off', 'status' => PhoneLead::STATUS_DEAD, 'grey_reason' => 'off']);
        foreach ([3, 2, 1, 0] as $k => $d) {
            $addCall($lead, ['fail_reason' => 'off', 'attempt_number' => $k + 1, 'called_at' => now()->subDays($d)->setTime(10, 0)]);
        }

        // ۷) ثبت‌نام‌شده (بسته‌شده) + لینک یکتا → دانش‌آموزان من / بسته‌شده
        for ($i = 0; $i < 2; $i++) {
            $lead = $makeLead(['attempts_count' => 2, 'last_outcome' => 'registered', 'status' => PhoneLead::STATUS_CLOSED]);
            $addCall($lead, ['fail_reason' => 'no_answer', 'attempt_number' => 1, 'called_at' => now()->subDays(3)]);
            $addCall($lead, [
                'connected'             => true,
                'attempt_number'        => 2,
                'answered_at'           => now()->subDays(1),
                'talk_duration_seconds' => rand(180, 500),
                'spoke_with'            => 'father',
                'result'                => 'registered',
                'summary'               => 'ثبت‌نام انجام شد؛ لینک ارسال شد.',
                'called_at'             => now()->subDays(1),
            ]);

            PhoneRegistrationLink::create([
                'token'         => \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(12)),
                'phone_lead_id' => $lead->id,
                'admin_id'      => $consultant->id,
                'mobile'        => $lead->mobile,
                'sent_at'       => now()->subDays(1),
            ]);
        }

        $this->command?->info('✅ دیتای دمو ساخته شد.');
        $this->command?->info("ورود مشاور:  ایمیل=phone.consultant@test.local  | موبایل={$consultant->mobile}  | رمز=password");
        $this->command?->info('بعد از ورود به: /admin/phone-acquisition/dashboard');
    }

    private function ensureConsultant(): Admin
    {
        $role = Role::firstOrCreate(['name' => 'مشاور جذب تلفنی', 'guard_name' => 'admin']);
        Permission::firstOrCreate(['name' => 'phone-acquisition.consult', 'guard_name' => 'admin']);
        $role->givePermissionTo('phone-acquisition.consult');

        $consultant = Admin::where('email', 'phone.consultant@test.local')->first();

        if (! $consultant) {
            $consultant = Admin::create([
                'name'     => self::DEMO_PREFIX . 'مشاور جذب تلفنی',
                'email'    => 'phone.consultant@test.local',
                'mobile'   => $this->freeMobile(),
                'password' => Hash::make('password'),
            ]);
        }

        if (! $consultant->hasRole($role)) {
            $consultant->assignRole($role);
        }

        // تا پس از ورود، صفحهٔ داشبورد پیش‌فرض ادمین ۴۰۳ ندهد.
        $dashboardPerm = Permission::firstOrCreate(['name' => 'admin.dashboard.view', 'guard_name' => 'admin']);
        $consultant->givePermissionTo($dashboardPerm);

        return $consultant;
    }

    /** یک موبایل آزاد (که در جدول admins نباشد) با الگوی 0912000000X پیدا می‌کند. */
    private function freeMobile(): string
    {
        for ($i = 1; $i < 100000; $i++) {
            $mobile = '0912' . str_pad((string) $i, 7, '0', STR_PAD_LEFT);
            if (! Admin::where('mobile', $mobile)->exists()) {
                return $mobile;
            }
        }

        return '09' . rand(100000000, 999999999);
    }

    private function cleanupOldDemo(): void
    {
        $oldLeads = PhoneLead::withTrashed()->where('full_name', 'like', self::DEMO_PREFIX . '%')->get();

        foreach ($oldLeads as $lead) {
            PhoneRegistrationLink::where('phone_lead_id', $lead->id)->delete();
            PhoneCall::where('phone_lead_id', $lead->id)->delete();
            PhoneLeadAssignment::where('phone_lead_id', $lead->id)->delete();
            $lead->forceDelete();
        }
    }
}
