<?php

namespace App\Livewire\Client\Auth;

use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendOtpToUser;
use App\Traits\NormalizesDigits;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class Login extends Component
{
    use SEOTools, NormalizesDigits;

    public $loginMethod = 'password';

    public $mobile = '';
    public $password = '';
    public $rememberMe = true;
    public $showPassword = false;

    public $otpMobile = '';
    public $otpCode = '';
    public $otpStep = 1;
    public $countdown = 0;

    public $isLoading = false;
    public $errorMessage = '';

    protected $listeners = ['countdownFinished'];

    public function mount()
    {
        $this->seoConfig();

        $method = request()->query('method');
        if ($method === 'otp') {
            $this->loginMethod = 'otp';
        }
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ورود | SDFR')
            ->setDescription('در این صفحه اطلاعات کاربری خود را وارد کنید تا به پنل شخصی خود دسترسی پیدا کنید.')
        ;
    }

    public function updatedMobile($value)
    {
        $this->mobile = $this->convertToEnglishDigits($value);
    }

    public function updatedOtpMobile($value)
    {
        $this->otpMobile = $this->convertToEnglishDigits($value);
    }

    public function updatedOtpCode($value)
    {
        $this->otpCode = $this->convertToEnglishDigits($value);
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function switchMethod($method)
    {
        $this->loginMethod = $method;
        $this->resetValidation();
        $this->errorMessage = '';

        if ($method === 'otp') {
            $this->otpStep = 1;
        }
    }

    public function countdownFinished()
    {
        $this->countdown = 0;
    }

    public function loginWithPassword()
    {
        $this->isLoading = true;
        $this->errorMessage = '';

        $validator = Validator::make([
            'mobile' => $this->mobile,
            'password' => $this->password,
        ], [
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'password' => ['required', 'min:6'],
        ], [
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست. (مثال: ۰۹۱۲۳۴۵۶۷۸۹)',
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد.',
        ]);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            return;
        }

        $this->resetValidation();

        $userExists = User::where('mobile', $this->mobile)->exists();
        if (!$userExists) {
            $this->isLoading = false;
            $this->errorMessage = 'کاربری با این شماره موبایل یافت نشد.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        if (!Auth::attempt([
            'mobile' => $this->mobile,
            'password' => $this->password,
        ], $this->rememberMe)) {
            $this->isLoading = false;
            $this->errorMessage = 'شماره موبایل یا رمز عبور اشتباه است.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }
        $this->invalidateOtherSessions(Auth::id());

        $this->isLoading = false;
        $this->dispatch('success', 'خوش آمدید!');

        if ($this->rememberMe) {
            $recallerName = Auth::getRecallerName();
            $recaller = Cookie::get($recallerName);
            if ($recaller) {
                Cookie::queue($recallerName, $recaller, config('auth.expiration'));
            }
        }

        return redirect()->route('client.profile.dashboard');
    }

    public function sendOtp()
    {
        $this->isLoading = true;
        $this->otpMobile = $this->convertToEnglishDigits($this->otpMobile);
        $this->otpCode = $this->convertToEnglishDigits($this->otpCode);

        $this->errorMessage = '';

        $validator = Validator::make([
            'mobile' => $this->otpMobile,
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

        $user = User::where('mobile', $this->otpMobile)->first();
        if (!$user) {
            $this->isLoading = false;
            $this->errorMessage = 'کاربری با این شماره موبایل یافت نشد.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        $activeOtp = Otp::forMobile($this->otpMobile)
            ->unused()
            ->latest()
            ->first();

        if ($activeOtp && ! $activeOtp->isExpired()) {
            $this->otpStep = 2;
            $this->countdown = $activeOtp->remainingSeconds();
            session()->put('login_otp_mobile', $this->otpMobile);

            $this->isLoading = false;
            $this->errorMessage = '';
            $this->dispatch('start-countdown');
            $this->dispatch('success', 'کد قبلی هنوز معتبر است. همان کد را وارد کنید.');
            return;
        }

        $code = Otp::generateCode();
        $otp = Otp::create([
            'mobile' => $this->otpMobile,
            'code' => $code,
            'expires_at' => now()->addSeconds(Otp::TTL_SECONDS),
        ]);

        try {
            $user->notify(new SendOtpToUser($this->otpMobile, $code));

            $this->otpStep = 2;
            $this->countdown = $otp->remainingSeconds();
            session()->put('login_otp_mobile', $this->otpMobile);

            $this->dispatch('success', 'کد تایید با موفقیت ارسال شد.');
            $this->dispatch('start-countdown');
        } catch (\Exception $e) {
            $otp->delete();
            Log::error('Send OTP Error', ['error' => $e->getMessage()]);
            $this->errorMessage = 'متاسفانه ارسال پیامک با خطا مواجه شد.';
            $this->dispatch('error', $this->errorMessage);
        }

        $this->isLoading = false;
    }

    public function resendOtp()
    {
        $this->otpCode = '';
        $this->sendOtp();
    }

    public function verifyOtp()
    {
        $this->isLoading = true;
        $this->otpMobile = $this->convertToEnglishDigits($this->otpMobile);
        $this->otpCode = $this->convertToEnglishDigits($this->otpCode);

        $this->errorMessage = '';

        $validator = Validator::make([
            'code' => $this->otpCode,
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
            return;
        }

        $this->resetValidation();

        $mobile = session('login_otp_mobile', $this->otpMobile);

        if (!$mobile) {
            $this->isLoading = false;
            $this->otpStep = 1;
            $this->errorMessage = 'مشکلی در دریافت شماره موبایل پیش آمد. لطفاً دوباره تلاش کنید.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        $otp = Otp::forMobile($mobile)
            ->where('code', $this->otpCode)
            ->unused()
            ->latest()
            ->first();

        if (!$otp) {
            $this->isLoading = false;
            $this->errorMessage = 'کد وارد شده صحیح نیست.';
            $this->dispatch('error', $this->errorMessage);
            return;
        }

        if ($otp->isExpired()) {
            $this->isLoading = false;
            $this->countdown = 0;
            $this->errorMessage = 'زمان این کد تمام شده است. دوباره کد بگیرید.';
            $this->dispatch('error', $this->errorMessage);
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
            return;
        }

        $user = User::where('mobile', $mobile)->first();
        Auth::login($user, true);

        $this->invalidateOtherSessions($user->id);

        session()->forget('login_otp_mobile');

        $this->isLoading = false;
        $this->dispatch('success', 'خوش آمدید!');

        return redirect()->route('client.profile.dashboard');
    }

    public function backToMobileStep()
    {
        $this->otpStep = 1;
        $this->otpCode = '';
        $this->errorMessage = '';
        $this->resetValidation();
    }

    private function invalidateOtherSessions(int $currentUserId): void
    {
        // فقط نشست‌های دیگرِ «همین کاربر» را پاک می‌کنیم.
        // قبلاً این کوئری نشستِ همه‌ی کاربران و remember_token همه را پاک می‌کرد
        // و باعث می‌شد با هر ورودِ یک کاربر، بقیه‌ی کاربران (به‌خصوص روی iOS که
        // کوکی نشست را زود پاک می‌کند) برای همیشه logout شوند.
        DB::table('sessions')
            ->where('user_id', $currentUserId)
            ->where('id', '!=', session()->getId())
            ->delete();
    }


    public function clientLogout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('client.auth.login');
    }

    public function render()
    {
        return view('livewire.client.auth.login')->layout('layouts.client.app-auth');
    }
}
