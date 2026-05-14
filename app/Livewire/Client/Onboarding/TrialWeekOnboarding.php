<?php

namespace App\Livewire\Client\Onboarding;

use App\Models\City;
use App\Models\Otp;
use App\Models\State;
use App\Models\Student;
use App\Models\TrialWeek;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PersonalInformation;
use App\Notifications\SendOtpToUser;
use App\Services\TrialWeekService;
use App\Traits\NormalizesDigits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class TrialWeekOnboarding extends Component
{
    use NormalizesDigits;

    // ─── مرحله جاری (1-8) ─────────────────────────────────────────────────────
    // 1=خوش‌آمدگویی 1  2=خوش‌آمدگویی 2  3=خوش‌آمدگویی 3
    // 4=اطلاعات شخصی  5=والدین+پایه  6=مکان+حساب  7=تأیید OTP
    // 8=تأیید هفته آزمایشی
    public int $currentStep = 1;
    public int $totalSteps  = 8;

    // ─── فیلدهای ثبت‌نام ──────────────────────────────────────────────────────
    public string $firstName    = '';
    public string $lastName     = '';
    public string $codeMell     = '';
    public string $fatherMobile = '';
    public string $motherMobile = '';
    public string $grade        = '10';
    public string $field        = 'math';
    public int    $stateId      = 0;
    public int    $cityId       = 0;
    public string $mobile       = '';
    public string $password     = '';
    public string $passwordConf = '';

    // ─── OTP ──────────────────────────────────────────────────────────────────
    public string $otpInput    = '';
    public string $otpError    = '';
    public bool   $otpSent     = false;
    public int    $countdown   = 90;
    public bool   $isLoading   = false;
    public string $generalError = '';

    // ─── وضعیت تکمیل ─────────────────────────────────────────────────────────
    public bool $registered        = false; // حساب ساخته شد
    public bool $showTrialConfirm  = false; // مودال تأیید هفته آزمایشی

    // ─── لیست‌ها ──────────────────────────────────────────────────────────────
    public $states = [];
    public $cities = [];

    // ─── strength رمز ─────────────────────────────────────────────────────────
    public array $passwordStrength = ['length' => false, 'letter' => false, 'number' => false];

    protected $listeners = ['countdownFinished'];

    public function mount(): void
    {
        $this->states = State::orderBy('name')->get();
    }

    // ─── navigation ───────────────────────────────────────────────────────────

    public function next(): void
    {
        $this->generalError = '';

        if ($this->currentStep === 4) {
            $this->validateStep4();
        } elseif ($this->currentStep === 5) {
            $this->validateStep5();
        } elseif ($this->currentStep === 6) {
            $this->validateStep6();
        } elseif ($this->currentStep === 7) {
            // OTP verify — handled separately
            return;
        }

        if ($this->getErrorBag()->isEmpty()) {
            $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
            if ($this->currentStep === 7 && !$this->otpSent) {
                $this->sendOtp();
            }
        }
    }

    public function previous(): void
    {
        $this->currentStep = max(1, $this->currentStep - 1);
        $this->resetValidation();
        $this->generalError = '';
    }

    // ─── validation helpers ────────────────────────────────────────────────────

    private function validateStep4(): void
    {
        $v = Validator::make([
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
            'codeMell'  => $this->codeMell,
        ], [
            'firstName' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{Arabic}\s]+$/u'],
            'lastName'  => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\p{Arabic}\s]+$/u'],
            'codeMell'  => ['required', 'digits:10'],
        ], [
            'firstName.required' => 'نام الزامی است.',
            'firstName.regex'    => 'نام باید فارسی باشد.',
            'lastName.required'  => 'نام خانوادگی الزامی است.',
            'lastName.regex'     => 'نام خانوادگی باید فارسی باشد.',
            'codeMell.required'  => 'کد ملی الزامی است.',
            'codeMell.digits'    => 'کد ملی باید ۱۰ رقم باشد.',
        ]);

        if ($v->fails()) {
            $this->setErrorBag($v->errors());
        }
    }

    private function validateStep5(): void
    {
        $rules = [
            'fatherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'motherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'grade'        => ['required', 'in:9,10,11,12'],
        ];
        if ($this->grade !== '9') {
            $rules['field'] = ['required', 'in:math,experimental,human'];
        }

        $v = Validator::make([
            'fatherMobile' => $this->fatherMobile,
            'motherMobile' => $this->motherMobile,
            'grade'        => $this->grade,
            'field'        => $this->field,
        ], $rules, [
            'fatherMobile.required' => 'شماره پدر الزامی است.',
            'fatherMobile.regex'    => 'فرمت شماره پدر صحیح نیست.',
            'motherMobile.required' => 'شماره مادر الزامی است.',
            'motherMobile.regex'    => 'فرمت شماره مادر صحیح نیست.',
            'grade.required'        => 'پایه الزامی است.',
            'field.required'        => 'رشته الزامی است.',
        ]);

        if ($v->fails()) {
            $this->setErrorBag($v->errors());
        }
    }

    private function validateStep6(): void
    {
        $v = Validator::make([
            'stateId'     => $this->stateId,
            'cityId'      => $this->cityId,
            'mobile'      => $this->mobile,
            'password'    => $this->password,
            'passwordConf' => $this->passwordConf,
        ], [
            'stateId'     => ['required', 'exists:states,id'],
            'cityId'      => ['required', 'exists:cities,id'],
            'mobile'      => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'password'    => ['required', 'min:8', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
            'passwordConf' => ['required', 'same:password'],
        ], [
            'stateId.required'      => 'انتخاب استان الزامی است.',
            'cityId.required'       => 'انتخاب شهر الزامی است.',
            'mobile.required'       => 'شماره موبایل الزامی است.',
            'mobile.regex'          => 'فرمت موبایل صحیح نیست.',
            'mobile.unique'         => 'این شماره قبلاً ثبت شده.',
            'password.required'     => 'رمز عبور الزامی است.',
            'password.min'          => 'رمز باید حداقل ۸ کاراکتر باشد.',
            'password.regex'        => 'رمز باید حرف و عدد داشته باشد.',
            'passwordConf.same'     => 'تکرار رمز مطابقت ندارد.',
        ]);

        if ($v->fails()) {
            $this->setErrorBag($v->errors());
        }
    }

    // ─── OTP ──────────────────────────────────────────────────────────────────

    public function updatedStateId(int $value): void
    {
        $this->cityId = 0;
        $this->cities = City::where('state_id', $value)->orderBy('name')->get();
    }

    public function updatedMobile(string $value): void
    {
        $this->mobile = $this->convertToEnglishDigits($value);
    }

    public function updatedFatherMobile(string $value): void
    {
        $this->fatherMobile = $this->convertToEnglishDigits($value);
    }

    public function updatedMotherMobile(string $value): void
    {
        $this->motherMobile = $this->convertToEnglishDigits($value);
    }

    public function updatedPassword(string $value): void
    {
        $this->passwordStrength = [
            'length' => strlen($value) >= 8,
            'letter' => (bool) preg_match('/[A-Za-z]/', $value),
            'number' => (bool) preg_match('/\d/', $value),
        ];
    }

    private function sendOtp(): void
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'mobile'     => $this->mobile,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            (new User(['mobile' => $this->mobile]))->notify(new SendOtpToUser($this->mobile, $code));
            $this->otpSent  = true;
            $this->countdown = 90;
            $this->dispatch('start-countdown');
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'کد تأیید ارسال شد.']);
        } catch (\Exception $e) {
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

    // ─── تأیید OTP + ساخت حساب ────────────────────────────────────────────────

    public function verifyOtp(): void
    {
        $this->isLoading = true;
        $this->otpError  = '';

        $otp = Otp::where('mobile', $this->mobile)
            ->where('code', $this->otpInput)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            $this->otpError  = 'کد وارد شده نامعتبر یا منقضی شده است.';
            $this->isLoading = false;
            return;
        }

        $otp->update(['is_used' => true]);
        $this->createAccount();
        $this->isLoading = false;
    }

    private function createAccount(): void
    {
        $user = User::create([
            'name'     => trim($this->firstName . ' ' . $this->lastName),
            'mobile'   => $this->mobile,
            'password' => Hash::make($this->password),
        ]);

        UserProfile::create([
            'user_id'   => $user->id,
            'full_name' => trim($this->firstName . ' ' . $this->lastName),
            'state_id'  => $this->stateId,
            'city_id'   => $this->cityId,
            'gender'    => 'male',
        ]);

        PersonalInformation::create([
            'user_id'       => $user->id,
            'name'          => $this->firstName,
            'father_name'   => $this->lastName,
            'code_mell'     => $this->codeMell,
            'father_mobile' => $this->fatherMobile,
            'mother_mobile' => $this->motherMobile,
            'grade'         => in_array($this->grade, ['10','11','12']) ? $this->grade : '10',
            'field'         => $this->grade !== '9' ? $this->field : 'math',
            'birth_date'    => '',
            'place_of_birth' => '',
            'address'       => '',
            'state_id'      => $this->stateId,
            'city_id'       => $this->cityId,
            'name_full'     => trim($this->firstName . ' ' . $this->lastName),
        ]);

        Auth::login($user, true);
        $this->registered   = true;
        $this->currentStep  = 8;
    }

    // ─── شروع هفته آزمایشی ────────────────────────────────────────────────────

    public function openTrialConfirm(): void
    {
        $this->showTrialConfirm = true;
    }

    public function closeTrialConfirm(): void
    {
        $this->showTrialConfirm = false;
    }

    public function confirmTrial(TrialWeekService $service): void
    {
        $user = Auth::user();

        if (TrialWeek::where('user_id', $user->id)->exists()) {
            $this->redirect(route('client.profile.trial.guide'), navigate: true);
            return;
        }

        $service->start(
            $user,
            (int) $this->grade,
            $this->grade !== '9' ? $this->field : null,
            $this->fatherMobile,
            $this->motherMobile,
        );

        $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
    }

    public function declineTrial(): void
    {
        $this->redirect(route('client.home'), navigate: true);
    }

    // ─── render ───────────────────────────────────────────────────────────────

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.onboarding.trial-week-onboarding')
            ->layout('layouts.client.app-auth');
    }
}
