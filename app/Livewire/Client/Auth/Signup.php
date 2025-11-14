<?php

namespace App\Livewire\Client\Auth;

use App\Notifications\SendOtpToUser;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\User;
use App\Models\Otp;

class Signup extends Component
{
    use SEOTools;
    public $step = 'mobile';
    public $sendSmsError = '';
    public $userMobile;
    public $name;
    public $otpCode;
    public $codeErrorMessage;
    public $password;
    public $countdown = 0;
    public $mobile = '';
    public $passwordStrength = [
        'length' => false,
        'number' => false,
        'case' => false,
    ];

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('ثبت نام در آبان ترید');
    }

    public function updatedPassword($value)
    {
        $this->validatePasswordStrength($value);
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
        $this->code = preg_replace_callback('/[۰-۹٠-٩]/u', function ($match) {
            $map = [
                '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
                '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
                '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
                '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            ];
            return $map[$match[0]] ?? $match[0];
        }, $value);
    }
    public function validatePasswordStrength($password)
    {
        $this->passwordStrength['length'] = strlen($password) >= 8;
        $this->passwordStrength['number'] = preg_match('/\d/', $password);
        $this->passwordStrength['case'] = preg_match('/[A-Z]/', $password) || preg_match('/[a-z]/', $password);
    }

    public function sendOtp($formData)
    {
        $validator = Validator::make($formData, [
            'name' => ['required', 'string', 'max:55', 'regex:/^[\p{Arabic}\s]+$/u'],
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
        ], [
            'name.required' => 'وارد کردن نام الزامی است!',
            'name.string' => 'فرمت نام صحیح نیست.',
            'name.regex' => 'نام و نام خانوادگی باید فقط با حروف فارسی نوشته شود!',
            'mobile.required' => 'شماره موبایل الزامی است!',
            'mobile.regex' => 'فقط از اعداد انگلیسی استفاده کنید (مثل 09123456789)',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است!',
        ]);

        $validator->validate();
        $this->resetValidation();
        $this->sendOtpCode($formData['mobile'], $formData['name']);
    }

    public function resendOtp()
    {
        $this->sendOtpCode($this->userMobile, $this->name);
        $this->dispatch('reset-timer', countdown: 60); // ارسال رویداد برای ریست تایمر
    }

    protected function sendOtpCode($mobile, $name)
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        Otp::create([
            'mobile' => $mobile,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        try {
            $user = new User(['mobile' => $mobile]);
            $user->notify(new SendOtpToUser($mobile, $code));

            $this->step = 'otp';
            $this->name = $name;
            $this->userMobile = $mobile;
            $this->otpCode = $code;
            $this->countdown = 60; // اطمینان از تنظیم مقدار 60
            $this->sendSmsError = '';
            $this->resetValidation();

            // برای اطمینان از رفرش ویو
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            Log::error('Send OTP Notification Error', ['error' => $e->getMessage()]);
            $this->sendSmsError = 'متاسفانه پیامک ارسال نشد. خطا: ' . $e->getMessage();
        }
    }

    public function verifyOtp($formData)
    {
        $validator = Validator::make($formData, [
            'code' => ['required', 'numeric', 'digits:6'],
        ], [
            'required' => 'لطفا این قسمت را خالی نگذارید!',
            'code.digits' => 'کد باید 6 رقمی باشد!',
        ]);

        $validator->validate();
        $this->resetValidation();

        $otp = Otp::where('mobile', $this->userMobile)
            ->where('code', $formData['code'])
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            $this->codeErrorMessage = 'کد نامعتبر یا منقضی شده است!';
            return;
        }

        $otp->update(['is_used' => true]);
        $this->step = 'password';
    }

    public function register($formData)
    {
        $validator = Validator::make($formData, [
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'
            ]
        ], [
            '*.required' => 'رمز عبور الزامی است!',
            '*.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد!',
            '*.regex' => 'رمز عبور باید شامل حداقل یک عدد، یک حرف بزرگ یا کوچک، باشد!',
        ]);

        $validator->validate();

        $existingUser = User::query()->where('mobile', $this->userMobile)->first();

        if (!$existingUser) {
            $newUser = User::query()->create([
                'name' => $this->name,
                'mobile' => $this->userMobile,
                'password' => bcrypt($formData['password']),
            ]);
            Auth::login($newUser, true);
        } else {
            $existingUser->update(['password' => bcrypt($formData['password'])]);
            Auth::login($existingUser, true);
        }

        return redirect()->route('client.profile.dashboard');
    }

    public function render()
    {
        return view('livewire.client.auth.signup')->layout('layouts.client.app-auth');
    }
}
