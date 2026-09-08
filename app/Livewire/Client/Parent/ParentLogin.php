<?php

namespace App\Livewire\Client\Parent;

use App\Models\Otp;
use App\Models\PersonalInformation;
use App\Models\Student;
use App\Notifications\SendOtpToUser;
use App\Traits\NormalizesDigits;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ParentLogin extends Component
{
    use NormalizesDigits;
    use SEOTools;

    public string $nationalCode = '';

    public string $mobile = '';

    public string $otpCode = '';

    public int $otpStep = 1;

    public int $countdown = 0;

    protected $listeners = ['countdownFinished'];

    public function mount()
    {
        $this->seo()->setTitle('ورود والدین');

        if (session()->has('parent_portal')) {
            return redirect()->route('client.parent.portal.dashboard');
        }
    }

    public function updatedNationalCode($value): void
    {
        $this->nationalCode = $this->convertToEnglishDigits($value);
    }

    public function updatedMobile($value): void
    {
        $this->mobile = $this->convertToEnglishDigits($value);
    }

    public function updatedOtpCode($value): void
    {
        $this->otpCode = $this->convertToEnglishDigits($value);
    }

    public function countdownFinished(): void
    {
        $this->countdown = 0;
    }

    /**
     * مرحله ۱: تطبیق کد ملی دانش‌آموز + موبایل والد و ارسال کد تایید به همان موبایل.
     * ورود مستقیم دیگر انجام نمی‌شود — فقط بعد از تایید OTP در verifyOtp() نشست ساخته می‌شود.
     */
    public function login()
    {
        $this->nationalCode = trim($this->convertToEnglishDigits($this->nationalCode));
        $this->mobile = trim($this->convertToEnglishDigits($this->mobile));

        $this->validate([
            'nationalCode' => ['required', 'digits:10'],
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
        ], [
            'nationalCode.required' => 'کد ملی دانش‌آموز الزامی است.',
            'nationalCode.digits' => 'کد ملی باید ۱۰ رقم باشد.',
            'mobile.required' => 'شماره موبایل پدر یا مادر الزامی است.',
            'mobile.regex' => 'شماره موبایل معتبر نیست. (مثال: 09123456789)',
        ]);

        $throttleKey = 'parent-portal:' . request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('nationalCode', "تعداد تلاش‌های ناموفق زیاد است. لطفاً {$seconds} ثانیه دیگر دوباره امتحان کنید.");
            return;
        }

        $match = $this->findStudent();

        if (!$match) {
            RateLimiter::hit($throttleKey, 60);
            $this->addError('nationalCode', 'اطلاعات وارد شده با هیچ دانش‌آموزی مطابقت ندارد. لطفاً کد ملی دانش‌آموز و شماره موبایل ثبت‌شده را بررسی کنید.');
            return;
        }

        RateLimiter::clear($throttleKey);
        $this->resetValidation();

        session()->put('parent_otp_context', $match);

        $this->sendOtpCode($match['mobile']);
    }

    /**
     * ارسال کد تایید به موبایلِ تطبیق‌داده‌شده (یا استفاده مجدد از کدِ هنوز معتبر قبلی).
     */
    protected function sendOtpCode(string $mobile): void
    {
        $activeOtp = Otp::forMobile($mobile)->unused()->latest()->first();

        if ($activeOtp && !$activeOtp->isExpired()) {
            $this->otpStep = 2;
            $this->countdown = $activeOtp->remainingSeconds();
            $this->dispatch('start-countdown');
            return;
        }

        $code = Otp::generateCode();
        $otp = Otp::create([
            'mobile' => $mobile,
            'code' => $code,
            'expires_at' => now()->addSeconds(Otp::TTL_SECONDS),
        ]);

        try {
            (new AnonymousNotifiable())->notify(new SendOtpToUser($mobile, $code));

            $this->otpStep = 2;
            $this->countdown = $otp->remainingSeconds();
            $this->dispatch('start-countdown');
        } catch (\Throwable $e) {
            $otp->delete();
            Log::error('Send Parent Portal OTP Error', ['error' => $e->getMessage()]);
            $this->addError('mobile', 'متاسفانه ارسال پیامک کد تایید با خطا مواجه شد. لطفاً دوباره تلاش کنید.');
        }
    }

    public function resendOtp(): void
    {
        $context = session('parent_otp_context');
        if (!$context) {
            $this->backToMobileStep();
            return;
        }

        $this->otpCode = '';
        $this->sendOtpCode($context['mobile']);
        $this->dispatch('otp-cleared');
    }

    /**
     * مرحله ۲: بررسی کد تایید و ساخت نشست پنل والدین.
     */
    public function verifyOtp()
    {
        $this->otpCode = trim($this->convertToEnglishDigits($this->otpCode));

        $this->validate([
            'otpCode' => ['required', 'digits:6'],
        ], [
            'otpCode.required' => 'وارد کردن کد تایید الزامی است.',
            'otpCode.digits' => 'کد تایید باید ۶ رقم باشد.',
        ]);

        $context = session('parent_otp_context');
        if (!$context || empty($context['mobile'])) {
            $this->otpStep = 1;
            $this->addError('otpCode', 'نشست شما منقضی شده. لطفاً دوباره تلاش کنید.');
            $this->dispatch('otp-error');
            return;
        }

        $throttleKey = 'parent-portal-otp:' . request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('otpCode', "تعداد تلاش‌های ناموفق زیاد است. لطفاً {$seconds} ثانیه دیگر دوباره امتحان کنید.");
            $this->dispatch('otp-error');
            return;
        }

        $otp = Otp::forMobile($context['mobile'])
            ->where('code', $this->otpCode)
            ->unused()
            ->latest()
            ->first();

        if (!$otp) {
            RateLimiter::hit($throttleKey, 60);
            $this->addError('otpCode', 'کد وارد شده صحیح نیست.');
            $this->dispatch('otp-error');
            return;
        }

        if ($otp->isExpired()) {
            $this->countdown = 0;
            $this->addError('otpCode', 'زمان این کد تمام شده است. دوباره کد بگیرید.');
            $this->dispatch('otp-error');
            return;
        }

        $markedAsUsed = Otp::query()
            ->whereKey($otp->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        if (!$markedAsUsed) {
            $this->addError('otpCode', 'این کد قبلاً استفاده شده است. دوباره کد جدید بگیرید.');
            $this->dispatch('otp-error');
            return;
        }

        RateLimiter::clear($throttleKey);

        $children = $this->findChildrenForMobile($context['mobile']);
        // اگر به هر دلیلی فرزندِ اصلیِ تطبیق‌داده‌شده در فهرست نبود (نبایدِ پیش بیاید)، آن را هم اضافه کن.
        if (!collect($children)->firstWhere('id', $context['student_id'])) {
            $children[] = [
                'id' => $context['student_id'],
                'name' => $context['student_name'],
                'role' => $context['parent_role'],
            ];
        }

        session()->forget('parent_otp_context');

        session()->put('parent_portal', [
            'student_id' => $context['student_id'],
            'user_id' => $context['user_id'],
            'student_name' => $context['student_name'],
            'parent_role' => $context['parent_role'],
            'verified_mobile' => $context['mobile'],
            'children' => $children,
            'logged_in_at' => now()->toDateTimeString(),
        ]);

        $this->dispatch('otp-success');

        return redirect()->route('client.parent.portal.dashboard');
    }

    public function backToMobileStep(): void
    {
        $this->otpStep = 1;
        $this->otpCode = '';
        $this->countdown = 0;
        $this->resetValidation();
        session()->forget('parent_otp_context');
    }

    /**
     * یافتن دانش‌آموز با کد ملی + موبایل پدر یا مادر.
     * ابتدا در اطلاعات فردی (مشاوره‌ای) و سپس در دانش‌آموزان مدرسه‌ای جستجو می‌شود.
     */
    protected function findStudent(): ?array
    {
        // ۱) دانش‌آموزان مشاوره‌ای: اطلاعات فردی
        $info = PersonalInformation::where('code_mell', $this->nationalCode)->first();
        if ($info) {
            $role = $this->matchParentRole($info->father_mobile, $info->mother_mobile);
            $student = $info->user?->student;
            if ($role && $student) {
                return [
                    'student_id' => $student->id,
                    'user_id' => $info->user_id,
                    'student_name' => $info->name ?: ($info->user?->name ?? 'دانش‌آموز'),
                    'parent_role' => $role,
                    'mobile' => $this->mobile,
                ];
            }
        }

        // ۲) دانش‌آموزان دارای کد ملی روی رکورد خودشان
        $students = Student::where('national_code', $this->nationalCode)->get();
        foreach ($students as $student) {
            $role = $this->matchParentRole($student->father_mobile, $student->mother_mobile);
            if ($role) {
                return [
                    'student_id' => $student->id,
                    'user_id' => $student->user_id,
                    'student_name' => $student->user?->name ?? 'دانش‌آموز',
                    'parent_role' => $role,
                    'mobile' => $this->mobile,
                ];
            }
        }

        return null;
    }

    protected function matchParentRole(?string $fatherMobile, ?string $motherMobile): ?string
    {
        $normalize = fn(?string $v) => $v ? trim($this->convertToEnglishDigits($v)) : null;

        if ($normalize($fatherMobile) === $this->mobile) {
            return 'father';
        }
        if ($normalize($motherMobile) === $this->mobile) {
            return 'mother';
        }

        return null;
    }

    /**
     * تمام فرزندانی که موبایلِ تاییدشده به‌عنوان موبایل پدر یا مادرشان ثبت شده —
     * برای پشتیبانی از چند فرزند (سوییچ بین فرزندان در داشبورد) بعد از تایید OTP.
     * چون مالکیت همین شماره موبایل با کد تایید احراز شده، دیگر نیازی به کد ملیِ هر فرزند نیست.
     *
     * @return array<int, array{id:int, name:string, role:string}>
     */
    protected function findChildrenForMobile(string $mobile): array
    {
        $normalize = fn(?string $v) => $v ? trim($this->convertToEnglishDigits($v)) : null;
        $children = [];

        PersonalInformation::query()
            ->where(function ($q) use ($mobile) {
                $q->where('father_mobile', $mobile)->orWhere('mother_mobile', $mobile);
            })
            ->with('user.student')
            ->get()
            ->each(function (PersonalInformation $info) use (&$children, $mobile, $normalize) {
                $student = $info->user?->student;
                if (!$student) {
                    return;
                }
                $role = $normalize($info->father_mobile) === $mobile ? 'father' : 'mother';
                $children[$student->id] = [
                    'id' => $student->id,
                    'name' => $info->name ?: ($info->user?->name ?? 'دانش‌آموز'),
                    'role' => $role,
                ];
            });

        Student::query()
            ->where(function ($q) use ($mobile) {
                $q->where('father_mobile', $mobile)->orWhere('mother_mobile', $mobile);
            })
            ->with('user')
            ->get()
            ->each(function (Student $student) use (&$children, $mobile, $normalize) {
                if (isset($children[$student->id])) {
                    return;
                }
                $role = $normalize($student->father_mobile) === $mobile ? 'father' : 'mother';
                $children[$student->id] = [
                    'id' => $student->id,
                    'name' => $student->user?->name ?? 'دانش‌آموز',
                    'role' => $role,
                ];
            });

        return array_values($children);
    }

    public function render()
    {
        return view('livewire.client.parent.parent-login')
            ->layout('layouts.client.app-auth');
    }
}
