<?php

namespace App\Livewire\Client\Onboarding;

use App\Models\City;
use App\Models\Otp;
use App\Models\PersonalInformation;
use App\Models\State;
use App\Models\TrialWeek;
use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\SendOtpToUser;
use App\Traits\NormalizesDigits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Unified registration + Trial Week onboarding wizard.
 *
 * Replaces the legacy /sign-up form. Single entry point at /start.
 * Desktop = scrollable sections; mobile = stepper (handled in view via Alpine).
 *
 * Steps:
 *  1. Welcome slides
 *  2. Mobile + OTP
 *  3. Registration info (name, family, national code, parents' mobiles, grade, field, state, city)
 *  4. Trial decision (accepted → create TrialWeek; declined → /checkout)
 */
class TrialWeekOnboarding extends Component
{
    use NormalizesDigits;

    public int $currentStep = 1;
    public int $totalSteps = 4;

    // step 2
    public string $mobile = '';
    public string $userInputCode = '';
    public string $codeErrorMessage = '';
    public int $countdown = 0;
    public bool $otpSent = false;
    public string $sendSmsError = '';

    // step 3 — registration info
    public string $firstName = '';
    public string $lastName = '';
    public string $nationalCode = '';
    public string $fatherMobile = '';
    public string $motherMobile = '';
    public string $grade = '';
    public string $field = '';
    public ?int $stateId = null;
    public ?int $cityId = null;

    // step 4 — trial decision
    public ?string $trialDecision = null; // accepted|declined

    public bool $isLoading = false;

    public $states = [];
    public $cities = [];

    protected $listeners = ['countdownFinished'];

    public function mount(): void
    {
        $this->states = State::query()->orderBy('name')->get();
    }

    public function updatedStateId($value): void
    {
        $this->cityId = null;
        $this->cities = City::query()->where('state_id', $value)->orderBy('name')->get();
    }

    public function updatedMobile($v): void { $this->mobile = $this->convertDigits($v); }
    public function updatedFatherMobile($v): void { $this->fatherMobile = $this->convertDigits($v); }
    public function updatedMotherMobile($v): void { $this->motherMobile = $this->convertDigits($v); }
    public function updatedNationalCode($v): void { $this->nationalCode = $this->convertDigits($v); }
    public function updatedUserInputCode($v): void { $this->userInputCode = $this->convertDigits($v); }

    protected function convertDigits($value): string
    {
        return preg_replace_callback('/[۰-۹٠-٩]/u', function ($m) {
            $map = ['۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
                    '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9'];
            return $map[$m[0]] ?? $m[0];
        }, (string) $value);
    }

    public function next(): void
    {
        match ($this->currentStep) {
            1 => $this->currentStep = 2,
            2 => $this->submitMobile(),
            3 => $this->submitRegistrationInfo(),
            4 => $this->finalize(),
            default => null,
        };
    }

    public function prev(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->resetValidation();
        }
    }

    public function jumpTo(int $step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps && $step <= $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function submitMobile(): void
    {
        $this->isLoading = true;
        $this->mobile = $this->convertDigits($this->mobile);

        if (!$this->otpSent || empty($this->userInputCode)) {
            $v = Validator::make(['mobile' => $this->mobile], [
                'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            ], [
                'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
                'mobile.regex' => 'فرمت شماره موبایل صحیح نیست.',
                'mobile.unique' => 'این شماره موبایل قبلاً ثبت‌نام کرده است. لطفاً وارد شوید.',
            ]);
            if ($v->fails()) {
                $this->isLoading = false;
                $this->setErrorBag($v->errors());
                return;
            }

            if (!$this->otpSent) {
                $this->sendOtp();
                $this->isLoading = false;
                return;
            }
        }

        // verify otp
        $otp = Otp::where('mobile', $this->mobile)
            ->where('code', $this->userInputCode)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            $this->isLoading = false;
            $this->codeErrorMessage = 'کد وارد شده نامعتبر یا منقضی شده است.';
            return;
        }

        $otp->update(['is_used' => true]);
        $this->codeErrorMessage = '';
        $this->currentStep = 3;
        $this->isLoading = false;
    }

    public function sendOtp(): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'mobile' => $this->mobile,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        try {
            $u = new User(['mobile' => $this->mobile]);
            $u->notify(new SendOtpToUser($this->mobile, $code));
            $this->otpSent = true;
            $this->countdown = 90;
            $this->dispatch('start-countdown');
            $this->dispatch('show-toast', ['type' => 'success', 'message' => 'کد تایید ارسال شد.']);
        } catch (\Throwable $e) {
            Log::error('Onboarding OTP error', ['e' => $e->getMessage()]);
            $this->sendSmsError = 'ارسال پیامک با خطا مواجه شد.';
        }
    }

    public function resendOtp(): void
    {
        $this->codeErrorMessage = '';
        $this->userInputCode = '';
        $this->sendOtp();
    }

    public function countdownFinished(): void
    {
        $this->countdown = 0;
    }

    public function submitRegistrationInfo(): void
    {
        $this->isLoading = true;
        $rules = [
            'firstName'    => ['required', 'string', 'min:2', 'max:55'],
            'lastName'     => ['required', 'string', 'min:2', 'max:55'],
            'nationalCode' => ['required', 'digits:10', 'unique:personal_information,code_mell'],
            'fatherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'motherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'grade'        => ['required', 'in:9,10,11,12'],
            'field'        => $this->grade == 9 ? ['nullable'] : ['required', 'in:math,experimental,human'],
            'stateId'      => ['required', 'exists:states,id'],
            'cityId'       => ['required', 'exists:cities,id'],
        ];
        $messages = [
            'firstName.required'    => 'وارد کردن نام الزامی است.',
            'lastName.required'     => 'وارد کردن نام خانوادگی الزامی است.',
            'nationalCode.required' => 'کد ملی الزامی است.',
            'nationalCode.digits'   => 'کد ملی باید ۱۰ رقم باشد.',
            'nationalCode.unique'   => 'این کد ملی قبلاً ثبت شده است.',
            'fatherMobile.required' => 'شماره پدر الزامی است.',
            'fatherMobile.regex'    => 'فرمت شماره پدر صحیح نیست.',
            'motherMobile.required' => 'شماره مادر الزامی است.',
            'motherMobile.regex'    => 'فرمت شماره مادر صحیح نیست.',
            'grade.required'        => 'انتخاب پایه الزامی است.',
            'field.required'        => 'انتخاب رشته الزامی است.',
            'stateId.required'      => 'انتخاب استان الزامی است.',
            'cityId.required'       => 'انتخاب شهر الزامی است.',
        ];

        $v = Validator::make([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'nationalCode' => $this->nationalCode,
            'fatherMobile' => $this->fatherMobile,
            'motherMobile' => $this->motherMobile,
            'grade' => $this->grade,
            'field' => $this->field,
            'stateId' => $this->stateId,
            'cityId' => $this->cityId,
        ], $rules, $messages);

        if ($v->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($v->errors());
            return;
        }

        $this->currentStep = 4;
        $this->isLoading = false;
    }

    public function finalize(): void
    {
        if (!in_array($this->trialDecision, ['accepted', 'declined'], true)) {
            $this->addError('trialDecision', 'برای ادامه یکی از گزینه‌ها را انتخاب کنید.');
            return;
        }
        $this->isLoading = true;

        $user = User::create([
            'name'   => trim($this->firstName . ' ' . $this->lastName),
            'mobile' => $this->mobile,
            // password not collected in trial onboarding; user logs in via OTP later
        ]);

        UserProfile::create([
            'user_id'  => $user->id,
            'full_name'=> trim($this->firstName . ' ' . $this->lastName),
            'state_id' => $this->stateId,
            'city_id'  => $this->cityId,
        ]);

        PersonalInformation::create([
            'user_id'       => $user->id,
            'name'          => $this->firstName,
            'last_name'     => $this->lastName,
            'code_mell'     => $this->nationalCode,
            'father_mobile' => $this->fatherMobile,
            'mother_mobile' => $this->motherMobile,
            'grade'         => $this->grade,
            'field'         => $this->grade == 9 ? null : $this->field,
            'state_id'      => $this->stateId,
            'city_id'       => $this->cityId,
        ]);

        Auth::guard('web')->login($user, true);

        if ($this->trialDecision === 'declined') {
            $this->isLoading = false;
            return redirect()->route('client.checkout');
        }

        TrialWeek::create([
            'user_id'       => $user->id,
            'grade'         => (int) $this->grade,
            'field'         => $this->grade == 9 ? null : $this->field,
            'father_mobile' => $this->fatherMobile,
            'mother_mobile' => $this->motherMobile,
            'status'        => TrialWeek::STATUS_PENDING,
            'trial_decision'=> 'accepted',
            'is_active'     => true,
            'expires_at'    => now()->addDays(7),
        ]);

        $this->isLoading = false;
        return redirect()->route('client.profile.waiting-for-supporter');
    }

    #[Layout('layouts.client.app-auth')]
    public function render()
    {
        return view('livewire.client.onboarding.trial-week-onboarding');
    }
}
