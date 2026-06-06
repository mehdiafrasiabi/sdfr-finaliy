<?php

namespace App\Livewire\Client\Onboarding;

use App\Models\City;
use App\Models\Otp;
use App\Models\State;
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

    // ─── مراحل ────────────────────────────────────────────────────────────────
    // موبایل: 1=welcome  2=اطلاعات شخصی  3=والدین  4=مکان+رمز  5=OTP  6=نهایی
    // دسکتاپ: همه‌ی فرم در یک صفحه؛ submitAll() → 5=OTP → 6=نهایی
    public int $currentStep = 1;
    public int $totalSteps  = 6;

    // ─── فیلدها ───────────────────────────────────────────────────────────────
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
    public string $otpInput     = '';
    public string $otpError     = '';
    public bool   $otpSent      = false;
    public int    $countdown    = 90;
    public bool   $isLoading    = false;
    public string $generalError = '';

    public bool $registered       = false;
    public bool $showTrialConfirm = false;

    public $states = [];
    public $cities = [];

    public array $passwordStrength = ['length' => false, 'letter' => false, 'number' => false];

    protected $listeners = ['countdownFinished'];

    public function mount(): void
    {
        $this->states = State::orderBy('name')->get();
    }

    // ═══════════════════════════════════════════════════════════════════════
    // MOBILE NAVIGATION
    // ═══════════════════════════════════════════════════════════════════════

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
        $this->currentStep = max(1, $this->currentStep - 1);
        $this->resetValidation();
        $this->generalError = '';
    }

    // ═══════════════════════════════════════════════════════════════════════
    // DESKTOP: validate all at once → OTP
    // ═══════════════════════════════════════════════════════════════════════

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

    // ═══════════════════════════════════════════════════════════════════════
    // VALIDATION
    // ═══════════════════════════════════════════════════════════════════════

    private function validatePersonalInfo(): void
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
            foreach ($v->errors()->messages() as $field => $messages) {
                foreach ($messages as $msg) {
                    $this->addError($field, $msg);
                }
            }
        }
    }

    private function validateParentsGrade(): void
    {
        $rules = [
            'fatherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'motherMobile' => ['required', 'regex:/^09[0-9]{9}$/', 'different:fatherMobile'],
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
        $v = Validator::make([
            'stateId'      => $this->stateId,
            'cityId'       => $this->cityId,
            'mobile'       => $this->mobile,
            'password'     => $this->password,
            'passwordConf' => $this->passwordConf,
            'fatherMobile' => $this->fatherMobile,
            'motherMobile' => $this->motherMobile,
        ], [
            'stateId'      => ['required', 'exists:states,id'],
            'cityId'       => ['required', 'exists:cities,id'],
            'mobile'       => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile', 'different:fatherMobile', 'different:motherMobile'],
            'password'     => ['required', 'min:8', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
            'passwordConf' => ['required', 'same:password'],
        ], [
            'stateId.required'      => 'انتخاب استان الزامی است.',
            'cityId.required'       => 'انتخاب شهر الزامی است.',
            'mobile.required'       => 'شماره موبایل الزامی است.',
            'mobile.regex'          => 'فرمت موبایل صحیح نیست.',
            'mobile.unique'         => 'این شماره قبلاً ثبت شده است.',
            'mobile.different'      => 'شماره شما نباید با شماره پدر یا مادر یکسان باشد.',
            'password.required'     => 'رمز عبور الزامی است.',
            'password.min'          => 'رمز باید حداقل ۸ کاراکتر باشد.',
            'password.regex'        => 'رمز باید شامل حرف انگلیسی و عدد باشد.',
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

    // ═══════════════════════════════════════════════════════════════════════
    // INPUT NORMALIZATION
    // ═══════════════════════════════════════════════════════════════════════

    public function updatedStateId(int $value): void
    {
        $this->cityId = 0;
        $this->cities = City::where('state_id', $value)->orderBy('name')->get();
    }

    public function updatedMobile(string $value): void       { $this->mobile = $this->convertToEnglishDigits($value); }
    public function updatedFatherMobile(string $value): void { $this->fatherMobile = $this->convertToEnglishDigits($value); }
    public function updatedMotherMobile(string $value): void { $this->motherMobile = $this->convertToEnglishDigits($value); }

    public function updatedPassword(string $value): void
    {
        $this->passwordStrength = [
            'length' => strlen($value) >= 8,
            'letter' => (bool) preg_match('/[A-Za-z]/', $value),
            'number' => (bool) preg_match('/\d/', $value),
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════
    // OTP
    // ═══════════════════════════════════════════════════════════════════════

    private function sendOtp(): void
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'mobile'     => $this->mobile,
            'code'       => $code,
            'expires_at' => now()->addSeconds(90),
        ]);

        try {
            (new User(['mobile' => $this->mobile]))->notify(new SendOtpToUser($this->mobile, $code));
            $this->otpSent   = true;
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
            'user_id'        => $user->id,
            'name'           => $this->firstName,
            'father_name'    => $this->lastName,
            'code_mell'      => $this->codeMell,
            'father_mobile'  => $this->fatherMobile,
            'mother_mobile'  => $this->motherMobile,
            'grade'          => in_array($this->grade, ['10','11','12']) ? $this->grade : '10',
            'field'          => $this->grade !== '9' ? $this->field : 'math',
            'birth_date'     => '',
            'place_of_birth' => '',
            'address'        => '',
            'state_id'       => $this->stateId,
            'city_id'        => $this->cityId,
            'name_full'      => trim($this->firstName . ' ' . $this->lastName),
        ]);

        Auth::login($user, true);
        $this->registered  = true;
        $this->currentStep = 6;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TRIAL / FINAL
    // ═══════════════════════════════════════════════════════════════════════

    public function openTrialConfirm(): void  { $this->showTrialConfirm = true; }
    public function closeTrialConfirm(): void { $this->showTrialConfirm = false; }

    public function confirmTrial(TrialWeekService $service): void
    {
        if ($this->isLoading) return;

        $this->isLoading        = true;
        $this->showTrialConfirm = false;

        $user = Auth::user();

        if (! $user) {
            $this->isLoading = false;
            $this->redirect(route('client.auth.login'), navigate: true);
            return;
        }

        if (TrialWeek::where('user_id', $user->id)->exists()) {
            // middleware assessments.required مسیر درست را تشخیص می‌دهد
            $this->redirect(route('client.profile.assessment.journey'), navigate: true);
            return;
        }

        $service->start(
            $user,
            (int) $this->grade,
            $this->grade !== '9' ? $this->field : null,
            $this->fatherMobile,
            $this->motherMobile,
        );

        $this->redirect(route('client.profile.assessment.journey'), navigate: true);
    }

    public function goToPurchase(): void { $this->redirect(route('client.purchase'), navigate: true); }
    public function declineTrial(): void { $this->redirect(route('client.home'), navigate: true); }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.onboarding.trial-week-onboarding')
            ->layout('layouts.client.app-auth');
    }
}
