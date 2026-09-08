<?php

namespace App\Livewire\Client\Auth;

use App\Models\User;
use App\Models\Otp;
use App\Notifications\SendOtpToUser;
use App\Traits\NormalizesDigits;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ForgotPassword extends Component
{
    use SEOTools, NormalizesDigits;

    public $step = 1;

    public $mobile = '';
    public $code = '';
    public $password = '';
    public $passwordConfirmation = '';

    public $isLoading = false;
    public $errorMessage = '';
    public $countdown = 0;
    public $showPassword = false;
    public $showPasswordConfirmation = false;

    public $passwordStrength = [
        'length' => false,
        'letter' => false,
        'number' => false,
    ];

    protected $listeners = ['countdownFinished'];

    public function mount()
    {
        $this->seoConfig();

    }

    public function seoConfig()
    {
        $this->seo()->setTitle('فراموشی رمز عبور | SDFR');
        $this->seo()->metatags()->addMeta('robots', 'noindex,follow', 'name');
    }

    public function updatedMobile($value)
    {
        $this->mobile = $this->convertToEnglishDigits($value);
    }

    public function updatedCode($value)
    {
        $this->code = $this->convertToEnglishDigits($value);
    }

    public function updatedPassword($value)
    {
        $this->validatePasswordStrength($value);
    }

    public function validatePasswordStrength($password)
    {
        $this->passwordStrength['length'] = strlen($password) >= 8;
        $this->passwordStrength['number'] = (bool)preg_match('/\d/', $password);
        $this->passwordStrength['letter'] = (bool)preg_match('/[A-Za-z]/', $password);
    }

    public function isPasswordValid()
    {
        return $this->passwordStrength['length'] &&
            $this->passwordStrength['number'] &&
            $this->passwordStrength['letter'];
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordConfirmationVisibility()
    {
        $this->showPasswordConfirmation = !$this->showPasswordConfirmation;
    }

    public function sendCode()
    {
        $this->isLoading = true;
        $this->mobile = $this->convertToEnglishDigits($this->mobile);
        $this->code = $this->convertToEnglishDigits($this->code);
        $this->errorMessage = '';

        $validator = Validator::make([
            'mobile' => $this->mobile,
        ], [
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
        ], [
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست. (مثال: ۰۹۱۲۳۴۵۶۷۸۹)',
        ]);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            return;
        }

        $this->resetValidation();

        $user = User::where('mobile', $this->mobile)->first();
        if (!$user) {
            $this->isLoading = false;
            $this->errorMessage = 'کاربری با این شماره موبایل یافت نشد.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        $activeOtp = Otp::forMobile($this->mobile)
            ->unused()
            ->latest()
            ->first();

        if ($activeOtp && ! $activeOtp->isExpired()) {
            session()->put('reset_mobile', $this->mobile);
            $this->countdown = $activeOtp->remainingSeconds();
            $this->step = 2;
            $this->isLoading = false;

            $this->dispatch('start-countdown');
            $this->dispatch('success', 'کد قبلی هنوز معتبر است. همان کد را وارد کنید.');
            return;
        }

        $key = 'forgot-password:' . $this->mobile;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->isLoading = false;
            $this->errorMessage = 'تعداد درخواست‌ها بیش از حد مجاز است. لطفاً چند دقیقه صبر کنید.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        RateLimiter::hit($key, 180);

        $code = Otp::generateCode();
        $otp = Otp::create([
            'mobile' => $this->mobile,
            'code' => $code,
            'expires_at' => now()->addSeconds(Otp::TTL_SECONDS),
        ]);

        try {
            $user->notify(new SendOtpToUser($this->mobile, $code));

            session()->put('reset_mobile', $this->mobile);
            $this->countdown = $otp->remainingSeconds();
            $this->step = 2;

            $this->dispatch('success', 'کد بازیابی با موفقیت ارسال شد.');
            $this->dispatch('start-countdown');
        } catch (\Exception $e) {
            $otp->delete();
            Log::error('Send Recovery Code Error', ['error' => $e->getMessage()]);
            $this->errorMessage = 'متاسفانه ارسال پیامک با خطا مواجه شد.';
            $this->dispatch('error', $this->errorMessage);
        }

        $this->isLoading = false;
    }

    public function resendCode()
    {
        $this->code = '';
        $this->errorMessage = '';
        $this->sendCode();
        $this->dispatch('otp-cleared');
    }

    public function backToMobileStep()
    {
        $this->step = 1;
        $this->code = '';
        $this->errorMessage = '';
        $this->resetValidation();
    }

    public function countdownFinished()
    {
        $this->countdown = 0;
    }

    public function verifyCode()
    {
        $this->isLoading = true;
        $this->mobile = $this->convertToEnglishDigits($this->mobile);
        $this->code = $this->convertToEnglishDigits($this->code);

        $this->errorMessage = '';

        $validator = Validator::make([
            'code' => $this->code,
        ], [
            'code' => ['required', 'numeric', 'digits:6'],
        ], [
            'code.required' => 'وارد کردن کد تایید الزامی است.',
            'code.numeric' => 'کد تایید باید فقط شامل اعداد باشد.',
            'code.digits' => 'کد تایید باید ۶ رقم باشد.',
        ]);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            $this->dispatch('otp-error');
            return;
        }

        $this->resetValidation();

        $mobile = session('reset_mobile', $this->mobile);

        $otp = Otp::forMobile($mobile)
            ->where('code', $this->code)
            ->unused()
            ->latest()
            ->first();

        if (!$otp) {
            $this->isLoading = false;
            $this->errorMessage = 'کد وارد شده صحیح نیست.';
            $this->dispatch('error', $this->errorMessage);
            $this->dispatch('otp-error');
            return;
        }

        if ($otp->isExpired()) {
            $this->isLoading = false;
            $this->countdown = 0;
            $this->errorMessage = 'زمان این کد تمام شده است. دوباره کد بگیرید.';
            $this->dispatch('error', $this->errorMessage);
            $this->dispatch('otp-error');
            return;
        }

        $markedAsUsed = Otp::query()
            ->whereKey($otp->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        if (! $markedAsUsed) {
            $this->isLoading = false;
            $this->errorMessage = 'این کد قبلاً استفاده شده است. دوباره کد جدید بگیرید.';
            $this->dispatch('error', $this->errorMessage);
            $this->dispatch('otp-error');
            return;
        }

        $this->isLoading = false;
        $this->step = 3;
        $this->dispatch('otp-success');
        $this->dispatch('success', 'کد تایید شد. لطفاً رمز عبور جدید را وارد کنید.');
    }

    public function resetPassword()
    {
        $this->isLoading = true;
        $this->errorMessage = '';

        if (!$this->isPasswordValid()) {
            $this->isLoading = false;
            $this->addError('password', 'رمز عبور باید حداقل ۸ کاراکتر، یک حرف و یک عدد داشته باشد.');
            return;
        }

        $validator = Validator::make([
            'password' => $this->password,
            'passwordConfirmation' => $this->passwordConfirmation,
        ], [
            'password' => ['required', 'min:8'],
            'passwordConfirmation' => ['required', 'same:password'],
        ], [
            'password.required' => 'وارد کردن رمز عبور جدید الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'passwordConfirmation.required' => 'تکرار رمز عبور الزامی است.',
            'passwordConfirmation.same' => 'رمز عبور و تکرار آن مطابقت ندارند.',
        ]);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            return;
        }

        $this->resetValidation();

        $mobile = session('reset_mobile', $this->mobile);

        $user = User::where('mobile', $mobile)->first();

        if (!$user) {
            $this->isLoading = false;
            $this->errorMessage = 'کاربر یافت نشد. لطفاً دوباره تلاش کنید.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        session()->forget('reset_mobile');

        $this->isLoading = false;
        $this->step = 4;
        $this->dispatch('success', 'رمز عبور با موفقیت تغییر کرد!');
    }

    public function loginAndRedirect()
    {
        $mobile = $this->mobile ?: session('reset_mobile');

        if ($mobile) {
            $user = User::where('mobile', $mobile)->first();
            if ($user) {
                auth()->login($user, true);
            }
        }

        return redirect()->route('client.profile.dashboard');
    }

    public function render()
    {
        return view('livewire.client.auth.forgot-password')->layout('layouts.client.app-auth');
    }
}
