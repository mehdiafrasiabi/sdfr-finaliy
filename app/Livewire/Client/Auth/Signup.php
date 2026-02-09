<?php

namespace App\Livewire\Client\Auth;

use App\Notifications\SendOtpToUser;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\User;
use App\Models\Otp;
use App\Models\City;
use App\Models\State;
use App\Models\UserProfile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Signup extends Component
{
    use SEOTools, WithFileUploads, UploadFile;

    public $currentStep = 1;
    public $sendSmsError = '';

    public $name = '';
    public $fullName = '';
    public $stateId = '';
    public $cityId = '';
    public $gender = '';
    public $picture;
    public $passwordConfirmation = '';
    public $mobile = '';
    public $email = '';
    public $password = '';
    public $otpCode = '';
    public $userInputCode = '';
    public $codeErrorMessage = '';
    public $countdown = 90;
    public $isLoading = false;
    public $acceptTerms = false;
    public $showPassword = false;
    public $showPasswordConfirmation = false;

    public $states = [];
    public $cities = [];

    public $passwordStrength = [
        'length' => false,
        'letter' => false,
        'number' => false,
    ];

    protected $listeners = ['countdownFinished'];

    public function mount()
    {
        $this->seoConfig();
        $this->states = State::query()->orderBy('name')->get();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('ثبت نام در آبان ترید');
    }

    protected function convertToEnglishDigits($value)
    {
        return preg_replace_callback('/[۰-۹٠-٩]/u', function ($match) {
            $map = [
                '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
                '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
                '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
                '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            ];
            return $map[$match[0]] ?? $match[0];
        }, $value);
    }

    public function updatedMobile($value)
    {
        $this->mobile = $this->convertToEnglishDigits($value);
    }

    public function updatedUserInputCode($value)
    {
        $this->userInputCode = $this->convertToEnglishDigits($value);
    }

    public function updatedPassword($value)
    {
        $this->validatePasswordStrength($value);
    }

    public function updatedStateId($value)
    {
        $this->cityId = '';
        $this->cities = City::query()
            ->where('state_id', $value)
            ->orderBy('name')
            ->get();
    }

    public function validatePasswordStrength($password)
    {
        $this->passwordStrength['length'] = strlen($password) >= 8;
        $this->passwordStrength['number'] = (bool) preg_match('/\d/', $password);
        $this->passwordStrength['letter'] = (bool) preg_match('/[A-Za-z]/', $password);
    }

    public function isPasswordValid()
    {
        return $this->passwordStrength['length']
            && $this->passwordStrength['number']
            && $this->passwordStrength['letter'];
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordConfirmationVisibility()
    {
        $this->showPasswordConfirmation = !$this->showPasswordConfirmation;
    }

    public function goToStep2()
    {
        $this->isLoading = true;

        $validator = Validator::make([
            'name' => $this->name,
            'fullName' => $this->fullName,
            'stateId' => $this->stateId,
            'cityId' => $this->cityId,
            'gender' => $this->gender,
        ], [
            'name' => ['required', 'string', 'min:2', 'max:55', 'regex:/^[\p{Arabic}\s]+$/u'],
            'fullName' => ['required', 'string', 'min:2', 'max:55', 'regex:/^[\p{Arabic}\s]+$/u'],
            'stateId' => ['required', 'exists:states,id'],
            'cityId' => ['required', 'exists:cities,id'],
            'gender' => ['required', 'in:male,female'],
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'name.regex' => 'نام باید فقط شامل حروف فارسی باشد.',
            'fullName.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'fullName.regex' => 'نام خانوادگی باید فقط شامل حروف فارسی باشد.',
            'stateId.required' => 'انتخاب استان الزامی است.',
            'cityId.required' => 'انتخاب شهر الزامی است.',
            'gender.required' => 'انتخاب جنسیت الزامی است.',
        ]);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            return;
        }

        $this->resetValidation();
        $this->isLoading = false;
        $this->currentStep = 2;
    }

    public function goToStep3()
    {
        $this->isLoading = true;

        $rules = [
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'picture' => ['nullable', 'image', 'max:2048'],
        ];

        $messages = [
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست. (مثال: ۰۹۱۲۳۴۵۶۷۸۹)',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'email.email' => 'فرمت ایمیل صحیح نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'picture.image' => 'فایل انتخابی باید تصویر باشد.',
            'picture.max' => 'حجم تصویر باید کمتر از ۲ مگابایت باشد.',
        ];

        $validator = Validator::make([
            'mobile' => $this->mobile,
            'email' => $this->email,
            'picture' => $this->picture,
        ], $rules, $messages);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            return;
        }

        $this->resetValidation();
        $this->sendOtpCode();
        $this->isLoading = false;
        $this->currentStep = 3;
    }

    public function goToPreviousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->resetValidation();
            $this->codeErrorMessage = '';
        }
    }

    protected function sendOtpCode()
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        Otp::create([
            'mobile' => $this->mobile,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        try {
            $user = new User(['mobile' => $this->mobile]);
            $user->notify(new SendOtpToUser($this->mobile, $code));
            $this->otpCode = $code;
            $this->countdown = 90;
            $this->sendSmsError = '';

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'کد تایید با موفقیت ارسال شد.'
            ]);

            $this->dispatch('start-countdown');
        } catch (\Exception $e) {
            Log::error('Send OTP Notification Error', ['error' => $e->getMessage()]);
            $this->sendSmsError = 'متاسفانه ارسال پیامک با خطا مواجه شد. لطفاً دوباره تلاش کنید.';

            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => $this->sendSmsError
            ]);
        }
    }

    public function resendOtp()
    {
        $this->isLoading = true;
        $this->codeErrorMessage = '';
        $this->userInputCode = '';
        $this->sendOtpCode();
        $this->isLoading = false;
    }

    public function countdownFinished()
    {
        $this->countdown = 0;
    }

    public function verifyAndRegister()
    {
        $this->isLoading = true;
        $this->codeErrorMessage = '';

        if (!$this->isPasswordValid()) {
            $this->isLoading = false;
            $this->addError('password', 'رمز عبور باید حداقل ۸ کاراکتر، یک حرف و یک عدد داشته باشد.');
            return;
        }

        $validator = Validator::make([
            'password' => $this->password,
            'passwordConfirmation' => $this->passwordConfirmation,
            'code' => $this->userInputCode,
            'acceptTerms' => $this->acceptTerms,
        ], [
            'password' => ['required', 'min:8'],
            'passwordConfirmation' => ['required', 'same:password'],
            'code' => ['required', 'numeric', 'digits:6'],
            'acceptTerms' => ['accepted'],
        ], [
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'passwordConfirmation.required' => 'تکرار رمز عبور الزامی است.',
            'passwordConfirmation.same' => 'تکرار رمز عبور با رمز عبور یکسان نیست.',
            'code.required' => 'وارد کردن کد تایید الزامی است.',
            'code.numeric' => 'کد تایید باید فقط شامل اعداد باشد.',
            'code.digits' => 'کد تایید باید ۶ رقم باشد.',
            'acceptTerms.accepted' => 'پذیرش قوانین و مقررات الزامی است.',
        ]);

        if ($validator->fails()) {
            $this->isLoading = false;
            $this->setErrorBag($validator->errors());
            return;
        }

        $otp = Otp::where('mobile', $this->mobile)
            ->where('code', $this->userInputCode)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            $this->isLoading = false;
            $this->codeErrorMessage = 'کد وارد شده نامعتبر یا منقضی شده است.';

            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => $this->codeErrorMessage
            ]);
            return;
        }

        $otp->update(['is_used' => true]);

        $userData = [
            'name' => $this->name,
            'mobile' => $this->mobile,
            'password' => bcrypt($this->password),
        ];

        if (!empty($this->email)) {
            $userData['email'] = $this->email;
        }

        // آپلود تصویر با تبدیل به WebP
        if ($this->picture) {
            try {
                $picturePath = $this->uploadImageInWebpFormatProfile(
                    $this->picture,
                    'temp_' . uniqid(),
                    400,
                    400,
                    'profiles'
                );
                $userData['picture'] = 'user/profiles/temp_' . uniqid() . '/' . $picturePath;
            } catch (\Exception $e) {
                Log::error('Image Upload Error', ['error' => $e->getMessage()]);
            }
        }

        $newUser = User::create($userData);

        // به‌روزرسانی مسیر تصویر با ID واقعی کاربر
        if (isset($userData['picture'])) {
            $oldPath = public_path($userData['picture']);
            $newPath = public_path('user/profiles/' . $newUser->id);

            if (!file_exists($newPath)) {
                mkdir($newPath, 0755, true);
            }

            if (file_exists($oldPath)) {
                $newFileName = basename($oldPath);
                rename($oldPath, $newPath . '/' . $newFileName);
                $newUser->update(['picture' => 'user/profiles/' . $newUser->id . '/' . $newFileName]);

                // حذف فولدر موقت
                $tempDir = dirname($oldPath);
                if (is_dir($tempDir)) {
                    rmdir($tempDir);
                }
            }
        }

        UserProfile::create([
            'user_id' => $newUser->id,
            'full_name' => trim($this->name . ' ' . $this->fullName),
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'gender' => $this->gender,
            'picture' => $userData['picture'] ?? null,
        ]);

        Auth::login($newUser, true);

        $this->isLoading = false;

        return redirect()->route('client.profile.dashboard');
    }

    public function render()
    {
        return view('livewire.client.auth.signup')->layout('layouts.client.app-auth');
    }
}
