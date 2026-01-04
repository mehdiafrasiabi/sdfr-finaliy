<?php



namespace App\Livewire\Client\Auth;



use App\Models\Otp;

use App\Models\User;

use App\Notifications\SendOtpToUser;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;



class Login extends Component

{

    use SEOTools;



    // Login method: 'password' or 'otp'

    public $loginMethod = 'password';



    // Password login fields

    public $mobile = '';

    public $password = '';

    public $rememberMe = false;



    // OTP login fields

    public $otpMobile = '';

    public $otpCode = '';

    public $otpStep = 1; // 1: enter mobile, 2: enter code

    public $countdown = 0;



    // Common fields

    public $isLoading = false;

    public $errorMessage = '';



    public function mount()

    {

        $this->seoConfig();
        // Check if method is passed via query parameter

        $method = request()->query('method');

        if ($method === 'otp') {

            $this->loginMethod = 'otp';

        }

    }



    public function seoConfig()

    {

        $this->seo()->setTitle('ورود به حساب کاربری');

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



    public function updatedOtpMobile($value)

    {

        $this->otpMobile = $this->convertToEnglishDigits($value);

    }



    public function updatedOtpCode($value)

    {

        $this->otpCode = $this->convertToEnglishDigits($value);

    }



    /**

     * Switch login method (password or otp)

     */

    public function switchMethod($method)

    {

        $this->loginMethod = $method;

        $this->resetValidation();

        $this->errorMessage = '';



        // Reset OTP step when switching

        if ($method === 'otp') {

            $this->otpStep = 1;

        }

    }



    /**

     * Login with password

     */

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



        // Check if user exists

        $userExists = User::where('mobile', $this->mobile)->exists();

        if (!$userExists) {

            $this->isLoading = false;

            $this->errorMessage = 'کاربری با این شماره موبایل یافت نشد.';

            $this->dispatch('warning', $this->errorMessage);

            return;

        }



        // Attempt login

        if (!Auth::attempt([

            'mobile' => $this->mobile,

            'password' => $this->password,

        ], $this->rememberMe)) {

            $this->isLoading = false;

            $this->errorMessage = 'شماره موبایل یا رمز عبور اشتباه است.';

            $this->dispatch('warning', $this->errorMessage);

            return;

        }



        $this->isLoading = false;

        $this->dispatch('success', 'خوش آمدید!');



        return redirect()->route('client.profile.dashboard');

    }



    /**

     * Send OTP code for login

     */

    public function sendOtp()

    {

        $this->isLoading = true;

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



        // Check if user exists

        $user = User::where('mobile', $this->otpMobile)->first();

        if (!$user) {

            $this->isLoading = false;

            $this->errorMessage = 'کاربری با این شماره موبایل یافت نشد.';

            $this->dispatch('warning', $this->errorMessage);

            return;

        }



        // Check for rate limiting

        $lastOtp = Otp::where('mobile', $this->otpMobile)

            ->where('created_at', '>', now()->subMinutes(1))

            ->latest()

            ->first();



        if ($lastOtp) {

            $this->isLoading = false;

            $this->errorMessage = 'لطفاً یک دقیقه صبر کنید و سپس دوباره تلاش کنید.';

            $this->dispatch('warning', $this->errorMessage);

            return;

        }



        // Generate and save OTP

        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);



        Otp::create([

            'mobile' => $this->otpMobile,

            'code' => $code,

            'expires_at' => now()->addMinutes(5),

        ]);



        try {

            $user->notify(new SendOtpToUser($this->otpMobile, $code));



            $this->otpStep = 2;

            $this->countdown = 90;

            session()->put('login_otp_mobile', $this->otpMobile);



            $this->dispatch('success', 'کد تایید با موفقیت ارسال شد.');

            $this->dispatch('start-countdown', countdown: 90);

        } catch (\Exception $e) {

            Log::error('Send OTP Error', ['error' => $e->getMessage()]);

            $this->errorMessage = 'متاسفانه ارسال پیامک با خطا مواجه شد.';

            $this->dispatch('warning', $this->errorMessage);

        }



        $this->isLoading = false;

    }



    /**

     * Resend OTP code

     */

    public function resendOtp()

    {

        $this->otpCode = '';

        $this->sendOtp();

    }



    /**

     * Verify OTP and login

     */

    public function verifyOtp()

    {

        $this->isLoading = true;

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

            $this->dispatch('warning', $this->errorMessage);

            return;

        }



        // Verify OTP

        $otp = Otp::where('mobile', $mobile)

            ->where('code', $this->otpCode)

            ->where('is_used', false)

            ->where('expires_at', '>', now())

            ->latest()

            ->first();



        if (!$otp) {

            $this->isLoading = false;

            $this->errorMessage = 'کد وارد شده نامعتبر یا منقضی شده است.';

            $this->dispatch('warning', $this->errorMessage);

            return;

        }



        // Mark OTP as used

        $otp->update(['is_used' => true]);



        // Login user

        $user = User::where('mobile', $mobile)->first();

        Auth::login($user, true);



        session()->forget('login_otp_mobile');



        $this->isLoading = false;

        $this->dispatch('success', 'خوش آمدید!');



        return redirect()->route('client.profile.dashboard');

    }



    /**

     * Go back to mobile input step

     */

    public function backToMobileStep()

    {

        $this->otpStep = 1;

        $this->otpCode = '';

        $this->errorMessage = '';

        $this->resetValidation();

    }



    /**

     * Logout user

     */

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
