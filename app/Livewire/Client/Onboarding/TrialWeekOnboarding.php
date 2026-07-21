<?php

namespace App\Livewire\Client\Onboarding;

use App\Models\Avatar;
use App\Models\ExamPlanningSetting;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PersonalInformation;
use App\Notifications\SendOtpToUser;
use App\Traits\NormalizesDigits;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class TrialWeekOnboarding extends Component
{
    use NormalizesDigits, SEOTools;

    public int $currentStep = 2;
    public int $totalSteps  = 6;

    public string $firstName    = '';
    public string $lastName     = '';
    public string $codeMell     = '';
    public string $birthDate    = '';   // تاریخ تولد (جلالی)

    public string $gender       = '';   // (B1) جنسیت: male | female
    public string $avatar       = '';   // (B1) مسیرِ آواتارِ انتخابی
    public string $fatherMobile = '';
    public string $motherMobile = '';
    public string $grade        = '10';
    public string $field        = 'math';
    public bool   $attendsSchool = true;
    public string $mobile       = '';
    public string $password     = '';
    public string $passwordConf = '';

    public bool   $examPlanMode = false;

    public string $otpInput     = '';
    public string $otpError     = '';
    public bool   $otpSent      = false;
    public int    $countdown    = 90;
    public bool   $isLoading    = false;
    public string $generalError = '';

    public bool $registered       = false;
    public array $maleAvatarOptions = [];
    public array $femaleAvatarOptions = [];

    public array $passwordStrength = ['length' => false, 'letter' => false, 'number' => false];

    protected $listeners = ['countdownFinished'];

    public function mount(): void
    {
        $this->maleAvatarOptions = Avatar::imagePathsForGender('male');
        $this->femaleAvatarOptions = Avatar::imagePathsForGender('female');

        // (A4) ذخیره‌ی پلنِ انتخابی از صفحه‌ی اصلی در session تا در مرحله‌ی نتیجه‌ی آزمون
        // (C8) دیگر صفحه‌ی انتخابِ «نقدی یا آزمایشی» به کاربر نمایش داده نشود.
        $plan = request('plan');
        if (in_array($plan, ['trial', 'exam', 'cash'], true)) {
            session(['intended_plan' => $plan]);
        }

        // Added for admin referrer tracking
        $referrerId = request('ref');
        if ($referrerId && filter_var($referrerId, FILTER_VALIDATE_INT)) {
            session(['referrer_admin_id' => (int)$referrerId]);
        }

        $this->examPlanMode = $plan === 'exam';
        if ($this->examPlanMode) {
            $this->attendsSchool = true;
            $this->normalizeExamPlanSelection();
        }
        $this->seo()
            ->setTitle('ثبت نام | SDFR')
            ->setDescription('برای ورود به حساب کاربری خود در سامانه SDFR به صفحه پرتال مراجعه کنید. با دسترسی به حساب، از خدمات و امکانات هوشمند مشاوره بهره‌مند شوید. همین حالا ثبت‌نام کن!')
        ;

        if (request()->filled('plan')) {
            $this->seo()->metatags()->addMeta('robots', 'noindex,follow', 'name');
        }

    }

    public function next(): void
    {
        $this->generalError = '';
        $this->resetValidation();

        if ($this->currentStep === 2) {
            $this->validatePersonalInfo();
        } elseif ($this->currentStep === 3) {
            $this->validateParentsGrade();
        } elseif ($this->currentStep === 4) {
            $this->validateLocationPassword();
        } elseif ($this->currentStep === 5) {
            return;
        }

        if ($this->getErrorBag()->isEmpty()) {
            $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
            if ($this->currentStep === 5 && !$this->otpSent) {
                $this->sendOtp();
            }
            $this->dispatch('step-changed', step: $this->currentStep);
        } else {
            $this->dispatch('step-validation-failed');
        }
    }

    public function previous(): void
    {
        $this->currentStep = max(2, $this->currentStep - 1);
        $this->resetValidation();
        $this->generalError = '';
    }

    public function submitAll(): void
    {
        $this->generalError = '';
        $this->resetValidation();

        $this->validatePersonalInfo();
        $this->validateParentsGrade();
        $this->validateLocationPassword();

        if ($this->getErrorBag()->isEmpty()) {
            $this->currentStep = 5;
            if (!$this->otpSent) {
                $this->sendOtp();
            }
            $this->dispatch('step-changed', step: $this->currentStep);
        } else {
            $this->dispatch('step-validation-failed');
        }
    }

    private function validatePersonalInfo(): void
    {
        $this->codeMell  = $this->convertToEnglishDigits($this->codeMell);
        $this->birthDate = $this->convertToEnglishDigits($this->birthDate);

        $v = Validator::make([
            'firstName'    => $this->firstName,
            'lastName'     => $this->lastName,
            'codeMell'     => $this->codeMell,
            'birthDate'    => $this->birthDate,
            'gender'       => $this->gender,
            'avatar'       => $this->avatar,
        ], [
            'firstName'    => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{Arabic}\s]+$/u'],
            'lastName'     => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{Arabic}\s]+$/u'],
            'codeMell'     => ['required', 'digits:10', Rule::unique('personal_information', 'code_mell')],
            'birthDate'    => ['required', 'regex:/^\d{4}\/\d{2}\/\d{2}$/'],
            'gender'       => ['required', 'in:male,female'],
            'avatar'       => [
                'required',
                'string',
                Rule::exists('avatars', 'image_path')->where(fn ($query) => $query
                    ->where('gender', $this->gender)
                    ->where('is_active', true)),
            ],
        ], [
            'firstName.required'    => 'نام الزامی است.',
            'firstName.regex'       => 'نام باید فارسی باشد.',
            'lastName.required'     => 'نام خانوادگی الزامی است.',
            'lastName.regex'        => 'نام خانوادگی باید فارسی باشد.',
            'codeMell.required'     => 'کد ملی الزامی است.',
            'codeMell.digits'       => 'کد ملی باید ۱۰ رقم باشد.',
            'codeMell.unique'       => 'این کد ملی قبلاً ثبت شده است.',
            'birthDate.required'    => 'تاریخ تولد الزامی است.',
            'birthDate.regex'       => 'فرمت تاریخ تولد صحیح نیست (مثال: ۱۳۸۰/۰۱/۰۱).',

            'gender.required'       => 'انتخاب جنسیت الزامی است.',
            'gender.in'             => 'جنسیت انتخاب‌شده معتبر نیست.',
            'avatar.required'       => 'انتخاب آواتار الزامی است.',
            'avatar.exists'         => 'آواتار انتخاب‌شده معتبر نیست.',
        ]);

        if ($v->fails()) {
            foreach ($v->errors()->messages() as $field => $messages) {
                foreach ($messages as $msg) {
                    $this->addError($field, $msg);
                }
            }
        }
    }

    private function validateParentsGrade(): void
    {
        $this->fatherMobile = $this->convertToEnglishDigits($this->fatherMobile);
        $this->motherMobile = $this->convertToEnglishDigits($this->motherMobile);

        $rules = [
            'fatherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'motherMobile' => ['required', 'regex:/^09[0-9]{9}$/', 'different:fatherMobile'],
            'grade'        => ['required', Rule::in($this->availableGradeValues())],
        ];
        if ($this->grade !== '9') {
            $rules['field'] = ['required', Rule::in($this->availableFieldValues())];
        }

        $v = Validator::make([
            'fatherMobile' => $this->fatherMobile,
            'motherMobile' => $this->motherMobile,
            'grade'        => $this->grade,
            'field'        => $this->field,
        ], $rules, [
            'fatherMobile.required'  => 'شماره پدر الزامی است.',
            'fatherMobile.regex'     => 'فرمت شماره پدر صحیح نیست.',
            'motherMobile.required'  => 'شماره مادر الزامی است.',
            'motherMobile.regex'     => 'فرمت شماره مادر صحیح نیست.',
            'motherMobile.different' => 'شماره موبایل پدر و مادر نباید یکسان باشد.',
            'grade.required'         => 'پایه الزامی است.',
            'grade.in'               => 'پایه انتخاب‌شده معتبر نیست.',
            'field.required'         => 'رشته الزامی است.',
            'field.in'               => 'رشته انتخاب‌شده معتبر نیست.',
        ]);

        if ($v->fails()) {
            foreach ($v->errors()->messages() as $field => $messages) {
                foreach ($messages as $msg) {
                    $this->addError($field, $msg);
                }
            }
        }
    }

    private function validateLocationPassword(): void
    {
        $this->mobile       = $this->convertToEnglishDigits($this->mobile);
        $this->fatherMobile = $this->convertToEnglishDigits($this->fatherMobile);
        $this->motherMobile = $this->convertToEnglishDigits($this->motherMobile);

        $v = Validator::make([
            'mobile'       => $this->mobile,
            'password'     => $this->password,
            'passwordConf' => $this->passwordConf,
            'fatherMobile' => $this->fatherMobile,
            'motherMobile' => $this->motherMobile,
        ], [
            'mobile'       => ['required', 'regex:/^09[0-9]{9}$/', Rule::unique('users', 'mobile'), 'different:fatherMobile', 'different:motherMobile'],
            'password'     => ['required', 'min:8'],
            'passwordConf' => ['required', 'same:password'],
        ], [
            'mobile.required'       => 'شماره موبایل الزامی است.',
            'mobile.regex'          => 'فرمت موبایل صحیح نیست.',
            'mobile.unique'         => 'این شماره قبلاً ثبت شده است.',
            'mobile.different'      => 'شماره شما نباید با شماره پدر یا مادر یکسان باشد.',
            'password.required'     => 'رمز عبور الزامی است.',
            'password.min'          => 'رمز باید حداقل ۸ کاراکتر باشد.',
            'passwordConf.required' => 'تکرار رمز عبور الزامی است.',
            'passwordConf.same'     => 'تکرار رمز با رمز عبور مطابقت ندارد.',
        ]);

        if ($v->fails()) {
            foreach ($v->errors()->messages() as $field => $messages) {
                foreach ($messages as $msg) {
                    $this->addError($field, $msg);
                }
            }
        }
    }

    public function updatedGrade(string $value): void
    {
        if ($value === 'graduate') {
            $this->attendsSchool = false;
        }

        if ($this->examPlanMode) {
            $this->normalizeExamPlanSelection();
        }
    }

    // (B1) با تغییرِ جنسیت، آواتارِ انتخابی پاک می‌شود تا آواتارِ هم‌جنسِ درست انتخاب شود.
    public function updatedGender(): void
    {
        // پاک‌سازی آواتار در فرانت انجام می‌شود تا انتخاب کاربر در اثر تاخیر
        // پاسخ Livewire دوباره بازنویسی نشود.
    }

    public function updatedMobile($value): void       { $this->mobile = $this->convertToEnglishDigits($value); }
    public function updatedFatherMobile($value): void { $this->fatherMobile = $this->convertToEnglishDigits($value); }
    public function updatedMotherMobile($value): void { $this->motherMobile = $this->convertToEnglishDigits($value); }
    public function updatedCodeMell($value): void     { $this->codeMell = $this->convertToEnglishDigits($value); }
    public function updatedBirthDate($value): void    { $this->birthDate = $this->convertToEnglishDigits($value); }
    public function updatedOtpInput($value): void
    {
        $this->otpInput = $this->normalizeOtpInput($value);
        $this->otpError = '';
    }

    public function updatedPassword(string $value): void
    {
        $this->passwordStrength = [
            'length' => strlen($value) >= 8,
            'letter' => true,
            'number' => true,
        ];
    }

    private function sendOtp(): void
    {
        if (! $this->registrationIsAvailable()) {
            $this->dispatch('step-validation-failed');
            return;
        }

        $activeOtp = Otp::forMobile($this->mobile)
            ->unused()
            ->latest()
            ->first();

        if ($activeOtp && ! $activeOtp->isExpired()) {
            $this->otpSent = true;
            $this->countdown = $activeOtp->remainingSeconds();
            $this->dispatch('start-countdown');
            $this->dispatch('show-toast', ['type' => 'info', 'message' => 'کد قبلی هنوز معتبر است. همان کد را وارد کنید.']);
            return;
        }

        $code = Otp::generateCode();
        $otp = Otp::create([
            'mobile'     => $this->mobile,
            'code'       => $code,
            'expires_at' => now()->addSeconds(Otp::TTL_SECONDS),
        ]);

        try {
            (new User(['mobile' => $this->mobile]))->notify(new SendOtpToUser($this->mobile, $code));
            $this->otpSent   = true;
            $this->countdown = $otp->remainingSeconds();
            $this->dispatch('start-countdown');
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'کد تأیید ارسال شد.']);
        } catch (\Exception $e) {
            $otp->delete();
            Log::error('OTP error', ['err' => $e->getMessage()]);
            $this->generalError = 'خطا در ارسال پیامک. دوباره تلاش کنید.';
        }
    }

    public function resendOtp(): void
    {
        $this->otpInput = '';
        $this->otpError = '';
        $this->sendOtp();
    }

    public function countdownFinished(): void
    {
        $this->countdown = 0;
    }

    private function normalizeOtpInput($value): string
    {
        return preg_replace('/\D+/', '', $this->convertToEnglishDigits($value)) ?? '';
    }

    public function verifyOtp(?string $otpInput = null): void
    {
        $this->resetValidation();
        $this->isLoading = true;
        $this->otpError  = '';
        $this->mobile    = $this->convertToEnglishDigits($this->mobile);
        $this->otpInput  = $this->normalizeOtpInput($otpInput ?? $this->otpInput);

        if (strlen($this->otpInput) !== 6) {
            $this->otpError  = 'کد تایید باید ۶ رقم باشد.';
            $this->isLoading = false;
            return;
        }

        $this->validatePersonalInfo();
        $this->validateParentsGrade();
        $this->validateLocationPassword();

        if (! $this->registrationIsAvailable() || ! $this->getErrorBag()->isEmpty()) {
            $this->currentStep = $this->getErrorBag()->has('codeMell') ? 2 : 4;
            $this->otpError = 'اطلاعات وارد شده قبلاً ثبت شده یا نیاز به اصلاح دارد.';
            $this->isLoading = false;
            $this->dispatch('step-validation-failed');
            $this->dispatch('step-changed', step: $this->currentStep);
            return;
        }

        $otp = Otp::forMobile($this->mobile)
            ->where('code', $this->otpInput)
            ->unused()
            ->latest()
            ->first();

        if (!$otp) {
            $this->otpError  = 'کد وارد شده صحیح نیست.';
            $this->isLoading = false;
            return;
        }

        if ($otp->isExpired()) {
            $this->countdown = 0;
            $this->otpError  = 'زمان این کد تمام شده است. دوباره کد جدید بگیرید.';
            $this->isLoading = false;
            return;
        }

        $markedAsUsed = Otp::query()
            ->whereKey($otp->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        if (! $markedAsUsed) {
            $this->otpError  = 'این کد قبلاً استفاده شده است. دوباره کد جدید بگیرید.';
            $this->isLoading = false;
            return;
        }

        try {
            $this->createAccount();
        } catch (QueryException $e) {
            Log::error('Trial onboarding registration error', ['err' => $e->getMessage()]);
            $this->otpError = 'این شماره موبایل یا کد ملی قبلاً ثبت شده است.';
            $this->isLoading = false;
            $this->currentStep = 4;
            $this->dispatch('step-validation-failed');
            $this->dispatch('step-changed', step: $this->currentStep);
            return;
        }

        $this->isLoading = false;
    }

    private function registrationIsAvailable(): bool
    {
        $this->mobile   = $this->convertToEnglishDigits($this->mobile);
        $this->codeMell = $this->convertToEnglishDigits($this->codeMell);

        $available = true;

        if (preg_match('/^\d{10}$/', $this->codeMell) && PersonalInformation::where('code_mell', $this->codeMell)->exists()) {
            $this->addError('codeMell', 'این کد ملی قبلاً ثبت شده است.');
            $available = false;
        }

        if (preg_match('/^09\d{9}$/', $this->mobile) && User::where('mobile', $this->mobile)->exists()) {
            $this->addError('mobile', 'این شماره قبلاً ثبت شده است.');
            $available = false;
        }

        return $available;
    }

    private function createAccount(): void
    {
        $user = DB::transaction(function () {
            $fullName = trim($this->firstName . ' ' . $this->lastName);

            $user = User::create([
                'name'     => $fullName,
                'mobile'   => $this->mobile,
                'picture'  => $this->avatar ?: null,
                'password' => Hash::make($this->password),
            ]);

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => trim($this->lastName),
                    'state_id'  => null,
                    'city_id'   => null,
                    'gender'    => in_array($this->gender, ['male', 'female'], true) ? $this->gender : 'male',
                    'picture'   => $this->avatar ?: null,
                ]
            );

            PersonalInformation::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name'           => $this->firstName,
                    'father_name'    => '',
                    'code_mell'      => $this->codeMell,
                    'father_mobile'  => $this->fatherMobile,
                    'mother_mobile'  => $this->motherMobile,
                    'grade'          => in_array($this->grade, ['10', '11', '12']) ? $this->grade : ($this->grade === 'graduate' ? '12' : '10'),
                    'is_graduate'    => $this->grade === 'graduate',
                    'attends_school' => $this->grade === 'graduate' ? false : $this->attendsSchool,
                    'field'          => $this->grade !== '9' ? $this->field : 'math',
                    'birth_date'     => $this->convertToEnglishDigits($this->birthDate),
                    'address'        => '',
                    'state_id'       => null,
                    'city_id'        => null,
                    'name_full'      => trim($this->lastName),
                    'place_of_birth' => '',
                ]
            );

            return $user;
        });

        // ردیابی تبدیل: اگر کاربر از طریق لینک یکتای مشاور جذب تلفنی آمده باشد،
        // ثبت‌نام را به آن لینک (و در نتیجه به مشاور) نسبت می‌دهیم.
        if ($refToken = session('phone_ref_token')) {
            $link = \App\Models\PhoneRegistrationLink::where('token', $refToken)
                ->whereNull('registered_user_id')
                ->first();

            if ($link) {
                $link->update(['registered_user_id' => $user->id, 'used_at' => now()]);

                \App\Models\PhoneLead::whereKey($link->phone_lead_id)->update([
                    'status'       => \App\Models\PhoneLead::STATUS_CLOSED,
                    'last_outcome' => \App\Models\PhoneCall::RESULT_REGISTERED,
                    'next_call_at' => null,
                ]);
            }

            session()->forget('phone_ref_token');
        }

        Auth::login($user, true);
        $this->registered  = true;
        $this->currentStep = 6;
    }

    public function startAssessments(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('client.auth.login'), navigate: true);
            return;
        }

        $this->redirect(route('client.profile.assessment.list'), navigate: true);
    }

    public function goToPurchase(): void { $this->redirect(route('client.purchase'), navigate: true); }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.onboarding.trial-week-onboarding')
            ->with([
                'gradeOptions' => $this->availableGradeOptions(),
                'fieldOptions' => $this->availableFieldOptions(),
            ])
            ->layout('layouts.client.app-auth');
    }

    private function availableGradeOptions(): array
    {
        if (! $this->examPlanMode) {
            $gradeLabels = ['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم','graduate'=>'فارغ‌التحصیل'];
            return array_map(
                fn ($value, $label) => ['id' => (string) $value, 'name' => $label],
                array_keys($gradeLabels),
                array_values($gradeLabels)
            );
        }

        return ExamPlanningSetting::query()
            ->active()
            ->windowOpen()
            ->whereIn('grade', [9, 10, 11, 12])
            ->orderBy('grade')
            ->orderBy('field')
            ->get()
            ->pluck('grade')
            ->map(fn ($grade) => (int) $grade)
            ->unique()
            ->values()
            ->map(fn (int $grade) => [
                'id' => (string) $grade,
                'name' => ExamPlanningSetting::GRADE_LABELS[$grade] ?? "پایه {$grade}",
            ])
            ->all();
    }

    private function availableFieldOptions(): array
    {
        if (! $this->examPlanMode) {
            return [
                ['id' => 'math', 'name' => 'ریاضی'],
                ['id' => 'experimental', 'name' => 'تجربی'],
                ['id' => 'human', 'name' => 'انسانی'],
            ];
        }

        $grade = (int) $this->grade;
        if ($grade === 9) {
            return [];
        }

        return ExamPlanningSetting::query()
            ->active()
            ->windowOpen()
            ->where('grade', $grade)
            ->whereNotNull('field')
            ->orderBy('field')
            ->get()
            ->pluck('field')
            ->filter()
            ->unique()
            ->values()
            ->map(fn (string $field) => [
                'id' => $field,
                'name' => ExamPlanningSetting::FIELD_LABELS[$field] ?? $field,
            ])
            ->all();
    }

    private function availableGradeValues(): array
    {
        return array_map(fn ($option) => (string) $option['id'], $this->availableGradeOptions());
    }

    private function availableFieldValues(): array
    {
        return array_map(fn ($option) => (string) $option['id'], $this->availableFieldOptions());
    }

    private function normalizeExamPlanSelection(): void
    {
        $gradeValues = $this->availableGradeValues();
        if (! in_array((string) $this->grade, $gradeValues, true) && ! empty($gradeValues)) {
            $this->grade = (int) $gradeValues[0];
        }

        if ((int) $this->grade === 9) {
            $this->field = 'math';
            $this->attendsSchool = true;
            return;
        }

        $fieldValues = $this->availableFieldValues();
        if (! in_array($this->field, $fieldValues, true) && ! empty($fieldValues)) {
            $this->field = $fieldValues[0];
        }

        $this->attendsSchool = true;
    }
}
