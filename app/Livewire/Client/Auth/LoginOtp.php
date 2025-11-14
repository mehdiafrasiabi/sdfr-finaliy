<?php

namespace App\Livewire\Client\Auth;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class LoginOtp extends Component
{
    public $step = 1;
    public $mobile;
    public $code;
    public $countdown = 0;


    public function updated($property)
    {
        if (in_array($property, ['mobile', 'code'])) {
            $this->$property = $this->convertPersianToEnglish($this->$property);
        }
    }

    public function sendOtp()
    {
        $this->resetErrorBag();

        Validator::make(
            ['mobile' => $this->mobile],
            ['mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'exists:users,mobile']],
            ['mobile.required' => 'شماره موبایل الزامی است.',

                'mobile.regex' => 'فقط از اعداد انگلیسی استفاده کنید (مثل 09123456789)',
                'mobile.exists' => 'کاربری با این شماره یافت نشد.']
        )->validate();

        // جلوگیری از ارسال مکرر
        $lastOtp = Otp::where('mobile', $this->mobile)
            ->where('created_at', '>', now()->subMinutes(1))
            ->latest()->first();

        if ($lastOtp) {
            $this->addError('mobile', 'لطفاً یک دقیقه صبر کنید.');
            return;
        }

        $code = rand(100000, 999999);

        Otp::create([
            'mobile' => $this->mobile,
            'code' => $code,
            'expires_at' => now()->addMinutes(2),
        ]);

        // ارسال نوتیفیکیشن
        $user = User::where('mobile', $this->mobile)->first();
        $user->notify(new \App\Notifications\SendOtpToUser($this->mobile, $code));

        $this->countdown = 60;
        $this->dispatch('start-timer');
        $this->step = 2;
        session()->put('otp_mobile', $this->mobile);
    }

    public function verifyOtp()
    {
        $this->resetErrorBag();

        Validator::make(
            ['code' => $this->code],
            ['code' => ['required', 'digits:6']],
            [
                'code.required' => 'کد الزامی است.',
                'code.digits' => 'کد باید ۶ رقمی باشد.',
            ]
        )->validate();

        $this->mobile = session('otp_mobile'); // 🔑 دریافت شماره از سشن

        if (!$this->mobile) {
            $this->addError('code', 'مشکلی در دریافت شماره موبایل پیش آمد. لطفا مجدد تلاش کنید.');
            $this->step = 1;
            return;
        }

        $otp = Otp::where('mobile', $this->mobile)
            ->where('code', $this->code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            $this->addError('code', 'کد وارد شده معتبر نیست یا منقضی شده است.');
            return;
        }

        // علامت‌گذاری کد به عنوان استفاده شده
        $otp->update(['is_used' => true]);

        // ورود
        Auth::loginUsingId(User::where('mobile', $this->mobile)->first()->id);
        session()->forget('otp_mobile');

        return redirect()->route('client.profile.dashboard');
    }

    private function convertPersianToEnglish($string)
    {
        return str_replace(
            ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'],
            ['0','1','2','3','4','5','6','7','8','9'],
            $string
        );
    }
    public function tick()
    {
        if ($this->countdown > 0) {
            $this->countdown--;
        }
    }

    public function render()
    {
        return view('livewire.client.auth.login-otp')->layout('layouts.client.app-auth');
    }
}
