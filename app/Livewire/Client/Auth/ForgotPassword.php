<?php

namespace App\Livewire\Client\Auth;

use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ForgotPassword extends Component
{
    use SEOTools;
    public $step = 1;
    public $mobile;
    public $code;
    public $generatedCode;
    public $password;
    public $password_confirmation;

    public $successMessage;
    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()->setTitle('فراموشی رمز عبور');
    }

    public function updatedMobile($value)
    {
        // تبدیل اعداد فارسی و عربی به انگلیسی
        $this->mobile = preg_replace_callback('/[۰-۹٠-٩]/u', function ($match) {
            $map = [
                '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
                '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
                '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
                '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            ];
            return $map[$match[0]] ?? $match[0];
        }, $value);
    }
    public function sendCode()
    {
        $this->validate([
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'exists:users,mobile'],
        ], [
            'mobile.required' => 'شماره موبایل الزامی است.',
            'mobile.regex' => 'فقط از اعداد انگلیسی استفاده کنید (مثل 09123456789)',
            'mobile.exists' => 'کاربری با این شماره یافت نشد.',
        ]);

        // نرخ ارسال محدود شود (5 بار در 1 دقیقه مثلاً)
        $key = 'send-otp:' . $this->mobile;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->dispatch('error', '⛔ ارسال بیش از حد مجاز. لطفا بعداً امتحان کنید.');
            return;
        }

        RateLimiter::hit($key, 180); // 60 ثانیه

        $this->generatedCode = rand(100000, 999999);

        $user = User::where('mobile', $this->mobile)->first();
        $user->notify(new \App\Notifications\SendOtpToUser($this->mobile, $this->generatedCode));
        session()->put('reset_mobile', $this->mobile); // ذخیره برای مراحل بعد
        $this->dispatch('success', ' درحال پردازش ...');
        $this->step = 2;
    }

    public function verifyCode()
    {
        if ($this->code == $this->generatedCode) {
            $this->mobile = session()->get('reset_mobile');
            $this->dispatch('success', ' درحال پردازش ...');
            $this->step = 3;

        } else {
            $this->dispatch('error', 'کد وارد شده نادرست است.');
        }
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'رمز عبور الزامی است.',
            'password.min' => 'رمز باید حداقل ۶ کاراکتر باشد.',
            'password.confirmed' => 'رمزها یکسان نیستند.',
        ]);
        $mobile = session()->get('reset_mobile'); // گرفتن موبایل

        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            $user->password = Hash::make($this->password);
            $user->save();

            auth()->login($user); // ورود خودکار

            $this->dispatch('success', '✅ رمز با موفقیت تغییر کرد!');


            return redirect()->to(route('client.profile.dashboard')); // مسیر دلخواه
        }
    }

    public function render()
    {
        return view('livewire.client.auth.forgot-password')->layout('layouts.client.app-auth');
    }
}
