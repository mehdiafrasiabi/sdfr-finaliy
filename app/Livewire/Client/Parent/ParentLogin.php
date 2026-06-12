<?php

namespace App\Livewire\Client\Parent;

use App\Models\PersonalInformation;
use App\Models\Student;
use App\Traits\NormalizesDigits;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ParentLogin extends Component
{
    use NormalizesDigits;
    use SEOTools;

    public string $nationalCode = '';

    public string $mobile = '';

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

        session()->put('parent_portal', [
            'student_id' => $match['student_id'],
            'user_id' => $match['user_id'],
            'student_name' => $match['student_name'],
            'parent_role' => $match['parent_role'],
            'logged_in_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('client.parent.portal.dashboard');
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

    public function render()
    {
        return view('livewire.client.parent.parent-login')
            ->layout('layouts.client.app-auth');
    }
}
