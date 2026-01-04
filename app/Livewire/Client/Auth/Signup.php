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


    public $currentStep = 1;

    public $sendSmsError = '';

    public $name = '';

    public $mobile = '';

    public $email = '';

    public $password = '';

    public $otpCode = '';

    public $userInputCode = '';

    public $codeErrorMessage = '';

    public $countdown = 90;

    public $isLoading = false;

    public $acceptTerms = false;


    public $passwordStrength = [

        'length' => false,

        'letter' => false,

        'number' => false,

    ];


    protected $listeners = ['startCountdown'];


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('ثبت نام در آبان ترید');

    }


    /**
     * Convert Persian/Arabic digits to English
     */

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


    /**
     * Step 1: Validate name and mobile, then go to step 2
     */

    public function goToStep2()

    {

        $this->isLoading = true;


        $validator = Validator::make([

            'name' => $this->name,

            'mobile' => $this->mobile,

        ], [

            'name' => ['required', 'string', 'min:3', 'max:55', 'regex:/^[\p{Arabic}\s]+$/u'],

            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],

        ], [

            'name.required' => 'وارد کردن نام و نام خانوادگی الزامی است.',

            'name.min' => 'نام و نام خانوادگی باید حداقل ۳ کاراکتر باشد.',

            'name.max' => 'نام و نام خانوادگی نباید بیشتر از ۵۵ کاراکتر باشد.',

            'name.regex' => 'نام و نام خانوادگی باید فقط با حروف فارسی نوشته شود.',

            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',

            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست. (مثال: ۰۹۱۲۳۴۵۶۷۸۹)',

            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',

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


    /**
     * Step 2: Validate email and password, then go to step 3 and send OTP
     */

    public function goToStep3()

    {

        $this->isLoading = true;


        // Validate password strength first

        if (!$this->isPasswordValid()) {

            $this->isLoading = false;

            $this->addError('password', 'رمز عبور باید حداقل ۸ کاراکتر، یک حرف و یک عدد داشته باشد.');

            return;

        }


        $rules = [

            'password' => [

                'required',

                'min:8',

                'regex:/^(?=.*[A-Za-z])(?=.*\d).{8,}$/'

            ]

        ];


        $messages = [

            'password.required' => 'وارد کردن رمز عبور الزامی است.',

            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',

            'password.regex' => 'رمز عبور باید شامل حداقل یک حرف و یک عدد باشد.',

        ];


        // Email is optional but validate if provided

        if (!empty($this->email)) {

            $rules['email'] = ['email', 'unique:users,email'];

            $messages['email.email'] = 'فرمت ایمیل صحیح نیست.';

            $messages['email.unique'] = 'این ایمیل قبلاً ثبت شده است.';

        }


        $validator = Validator::make([

            'email' => $this->email,

            'password' => $this->password,

        ], $rules, $messages);


        if ($validator->fails()) {

            $this->isLoading = false;

            $this->setErrorBag($validator->errors());

            return;

        }


        $this->resetValidation();


        // Send OTP automatically when entering step 3

        $this->sendOtpCode();


        $this->isLoading = false;

        $this->currentStep = 3;


        // Start countdown after rendering

        $this->dispatch('start-countdown', countdown: 90);

    }


    /**
     * Go back to previous step
     */

    public function goToPreviousStep()

    {

        if ($this->currentStep > 1) {

            $this->currentStep--;

            $this->resetValidation();

            $this->codeErrorMessage = '';

        }

    }


    /**
     * Send OTP code to mobile
     */

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


            $this->dispatch('success', 'کد تایید با موفقیت ارسال شد.');

        } catch (\Exception $e) {

            Log::error('Send OTP Notification Error', ['error' => $e->getMessage()]);

            $this->sendSmsError = 'متاسفانه ارسال پیامک با خطا مواجه شد. لطفاً دوباره تلاش کنید.';

            $this->dispatch('warning', $this->sendSmsError);

        }

    }


    /**
     * Resend OTP code
     */

    public function resendOtp()

    {

        $this->isLoading = true;

        $this->codeErrorMessage = '';

        $this->userInputCode = '';


        $this->sendOtpCode();


        $this->isLoading = false;

        $this->dispatch('start-countdown', countdown: 90);

    }


    /**
     * Verify OTP and register user
     */

    public function verifyAndRegister()

    {

        $this->isLoading = true;

        $this->codeErrorMessage = '';


        $validator = Validator::make([

            'code' => $this->userInputCode,

            'acceptTerms' => $this->acceptTerms,

        ], [

            'code' => ['required', 'numeric', 'digits:6'],

            'acceptTerms' => ['accepted'],

        ], [

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


        // Verify OTP

        $otp = Otp::where('mobile', $this->mobile)
            ->where('code', $this->userInputCode)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();


        if (!$otp) {

            $this->isLoading = false;

            $this->codeErrorMessage = 'کد وارد شده نامعتبر یا منقضی شده است.';

            $this->dispatch('warning', $this->codeErrorMessage);

            return;

        }


        // Mark OTP as used

        $otp->update(['is_used' => true]);


        // Register user - only after successful OTP verification

        $existingUser = User::where('mobile', $this->mobile)->first();


        if (!$existingUser) {

            $userData = [

                'name' => $this->name,

                'mobile' => $this->mobile,

                'password' => bcrypt($this->password),

            ];


            if (!empty($this->email)) {

                $userData['email'] = $this->email;

            }


            $newUser = User::create($userData);

            Auth::login($newUser, true);


            $this->dispatch('success', 'ثبت نام با موفقیت انجام شد.');

        } else {

            // Update existing user (if somehow they exist)

            $existingUser->update(['password' => bcrypt($this->password)]);

            Auth::login($existingUser, true);

        }


        $this->isLoading = false;


        return redirect()->route('client.profile.dashboard');

    }


    public function render()

    {

        return view('livewire.client.auth.signup')->layout('layouts.client.app-auth');

    }

}
