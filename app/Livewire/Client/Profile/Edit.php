<?php

namespace App\Livewire\Client\Profile;
use App\Notifications\SendOtpToUser;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use App\Models\City;
use App\Models\State;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;


class Edit extends Component

{

    use SEOTools, WithFileUploads, UploadFile;


    public $name, $email, $mobile, $photo, $new_photo;
    public $full_name, $gender, $state_id, $city_id, $birth_date;

    public $states = [];
    public $cities = [];


    // تغییر رمز با رمز فعلی

    public $current_password = '';

    public $new_password = '';

    public $new_password_confirmation = '';


    // فراموشی رمز عبور با OTP

    public $showForgotPassword = false;

    public $otp_code = '';

    public $forgot_new_password = '';

    public $forgot_new_password_confirmation = '';

    public $generated_otp = null;

    public $otp_sent_at = null;

    public $otp_verified = false;

    public function mount()
    {
        $this->seoConfig();

        $user = Auth::user(); // یا Student::find(Auth::id()) در صورت نیاز
        $this->name = $user->name;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
        $this->photo = $user->picture;
        $profile = $user->profile;
        $this->full_name = $profile?->full_name;
        $this->gender = $profile?->gender;
        $this->state_id = $profile?->state_id;
        $this->city_id = $profile?->city_id;
        $this->birth_date = $profile?->birth_date;

        $this->states = State::query()->select('id', 'name')->get();
        $this->cities = $this->state_id
            ? City::query()->where('state_id', $this->state_id)->select('id', 'name')->get()
            : collect();
    }

    public function updatedStateId($value): void
    {
        $this->city_id = null;
        $this->cities = $value
            ? City::query()->where('state_id', $value)->select('id', 'name')->get()
            : collect();

        // Dispatch event for Tom-Select reinitialization
        $this->dispatch('state-changed');
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ویرایش پروفایل');
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['nullable', 'email'],
            'full_name' => ['required', 'string', 'min:3', 'max:150'],
            'gender' => ['required', 'in:male,female'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'birth_date' => ['nullable', 'string', 'max:20'],
            'new_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'name.string' => 'فرمت نام معتبر نیست.',
            'name.min' => 'نام باید حداقل ۳ کاراکتر داشته باشد.',
            'name.max' => 'نام نمی‌تواند بیشتر از ۱۵۰ کاراکتر باشد.',

            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل معتبر نیست.',

            'full_name.required' => 'وارد کردن نام و نام خانوادگی الزامی است.',
            'full_name.min' => 'نام و نام خانوادگی باید حداقل ۳ کاراکتر داشته باشد.',
            'full_name.max' => 'نام و نام خانوادگی نمی‌تواند بیشتر از ۱۵۰ کاراکتر باشد.',

            'gender.required' => 'انتخاب جنسیت الزامی است.',
            'gender.in' => 'جنسیت انتخاب‌شده معتبر نیست.',

            'state_id.exists' => 'استان انتخاب‌شده معتبر نیست.',
            'city_id.exists' => 'شهر انتخاب‌شده معتبر نیست.',

            'new_photo.required' => 'انتخاب تصویر الزامی است.',
            'new_photo.image' => 'فایل انتخابی باید یک تصویر باشد.',
            'new_photo.mimes' => 'فرمت‌های مجاز: jpg, jpeg, png, webp',
            'new_photo.max' => 'حجم تصویر نباید بیشتر از ۱ مگابایت باشد.',
        ]);


        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;

        $profile = $user->profile()->firstOrNew();
        $profile->full_name = $this->full_name;
        $profile->gender = $this->gender;
        $profile->state_id = $this->state_id;
        $profile->city_id = $this->city_id;
        $profile->birth_date = $this->birth_date;

        if ($this->new_photo) {
            // حذف عکس قبلی
            if ($user->picture) {
                $oldPath = public_path("user/img/{$user->id}/" . $user->picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            // تولید نام و ذخیره
            $filename = $this->uploadImageInWebpFormatProfile($this->new_photo, $user->id, 150, 150, 'img');
            $user->picture = $filename; // فقط نام فایل
            $profile->picture = $filename;
        }
        $user->save();
        $user->profile()->save($profile);
        $this->photo = $user->picture;
        $this->dispatch('success','پروفایل با موفقیت به‌روزرسانی شد.');
        $this->redirectRoute('client.profile.edit');
    }
    /**
     * تغییر رمز عبور با رمز فعلی
     */
    public function changePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // حداقل یک حرف کوچک
                'regex:/[A-Z]/',      // حداقل یک حرف بزرگ
                'regex:/[0-9]/',      // حداقل یک عدد
            ],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ], [
            'current_password.required' => 'وارد کردن رمز فعلی الزامی است.',
            'new_password.required' => 'وارد کردن رمز جدید الزامی است.',
            'new_password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'new_password.regex' => 'رمز عبور باید شامل حرف کوچک، حرف بزرگ و عدد باشد.',
            'new_password_confirmation.required' => 'تکرار رمز جدید الزامی است.',
            'new_password_confirmation.same' => 'تکرار رمز جدید با رمز جدید مطابقت ندارد.',
        ]);
        $user = Auth::user();
        // بررسی رمز فعلی
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'رمز عبور فعلی صحیح نیست.');
            $this->dispatch('warning','رمز عبور فعلی صحیح نیست.');
            return;
        }
        // تغییر رمز
        $user->password = Hash::make($this->new_password);
        $user->save();
        // پاک کردن فیلدها
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->dispatch('warning','شماره موبایل در حساب کاربری ثبت نشده است.');
        session()->flash('password_success', 'رمز عبور با موفقیت تغییر کرد.');
    }
    /**
     * نمایش فرم فراموشی رمز عبور
     */
    public function toggleForgotPassword()
    {
        $this->showForgotPassword = !$this->showForgotPassword;
        $this->reset(['otp_code', 'forgot_new_password', 'forgot_new_password_confirmation', 'otp_verified']);
        $this->resetErrorBag();
    }
    /**
     * ارسال کد OTP
     */
    public function sendOtp()
    {
        $user = Auth::user();
        if (!$user->mobile) {
            $this->addError('otp_code', 'شماره موبایل در حساب کاربری ثبت نشده است.');
            $this->dispatch('success','رمز عبور با موفقیت تغییر کرد.');
            return;
        }
        // بررسی محدودیت زمانی (۲ دقیقه)
        if ($this->otp_sent_at && now()->diffInSeconds($this->otp_sent_at) < 120) {
            $remaining = 120 - now()->diffInSeconds($this->otp_sent_at);
            $this->addError('otp_code', "لطفاً {$remaining} ثانیه دیگر تلاش کنید.");
            $this->dispatch('warning',"لطفاً {$remaining} ثانیه دیگر تلاش کنید.");
            return;
        }
        // تولید کد ۶ رقمی
        $this->generated_otp = rand(100000, 999999);
        $this->otp_sent_at = now();
        $this->otp_verified = false;
        // ارسال SMS
        $user->notify(new SendOtpToUser($user->mobile, $this->generated_otp));
        session()->flash('otp_sent', 'کد تایید به شماره موبایل شما ارسال شد.');
        $this->dispatch('success',"کد تایید به شماره موبایل شما ارسال شد.");

    }
    /**
     * اعتبارسنجی کد OTP
     */
    public function verifyOtp()
    {
        $this->validate([
            'otp_code' => ['required', 'digits:6'],
        ], [
            'otp_code.required' => 'وارد کردن کد تایید الزامی است.',
            'otp_code.digits' => 'کد تایید باید ۶ رقم باشد.',
        ]);
        // بررسی انقضا (۵ دقیقه)
        if (!$this->otp_sent_at || now()->diffInMinutes($this->otp_sent_at) > 5) {
            $this->addError('otp_code', 'کد تایید منقضی شده است. لطفاً کد جدید دریافت کنید.');
            $this->dispatch('warning',"کد تایید منقضی شده است. لطفاً کد جدید دریافت کنید.");
            return;
        }
        if ($this->otp_code != $this->generated_otp) {
            $this->addError('otp_code', 'کد تایید صحیح نیست.');
            $this->dispatch('warning',"کد تایید صحیح نیست.");
            return;
        }
        $this->otp_verified = true;
        session()->flash('otp_verified', 'کد تایید با موفقیت تایید شد.');
        $this->dispatch('success',"کد تایید با موفقیت تایید شد.");
    }
    /**
     * تغییر رمز با فراموشی رمز (بعد از تایید OTP)
     */
    public function changePasswordWithOtp()
    {
        if (!$this->otp_verified) {
            $this->addError('otp_code', 'ابتدا کد تایید را وارد کنید.');
            $this->dispatch('warning',"ابتدا کد تایید را وارد کنید.");
            return;
        }
        $this->validate([
            'forgot_new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'forgot_new_password_confirmation' => ['required', 'same:forgot_new_password'],
        ], [
            'forgot_new_password.required' => 'وارد کردن رمز جدید الزامی است.',
            'forgot_new_password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'forgot_new_password.regex' => 'رمز عبور باید شامل حرف کوچک، حرف بزرگ و عدد باشد.',
            'forgot_new_password_confirmation.required' => 'تکرار رمز جدید الزامی است.',
            'forgot_new_password_confirmation.same' => 'تکرار رمز جدید با رمز جدید مطابقت ندارد.',
        ]);
        $user = Auth::user();
        $user->password = Hash::make($this->forgot_new_password);
        $user->save();
        // ریست کردن همه فیلدها
        $this->reset([
            'showForgotPassword', 'otp_code', 'forgot_new_password',
            'forgot_new_password_confirmation', 'generated_otp', 'otp_sent_at', 'otp_verified'
        ]);
        $this->dispatch('success',"رمز عبور با موفقیت تغییر کرد.");
        session()->flash('password_success', 'رمز عبور با موفقیت تغییر کرد.');

    }

    public function render()
    {
        return view('livewire.client.profile.edit')->layout('layouts.client.app');
    }
}
