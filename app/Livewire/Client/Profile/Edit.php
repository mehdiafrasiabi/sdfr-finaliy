<?php

namespace App\Livewire\Client\Profile;
use App\Models\Avatar;
use App\Notifications\SendOtpToUser;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\City;
use App\Models\State;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;


class Edit extends Component

{

    use SEOTools, WithFileUploads, UploadFile;


    public $name, $email, $mobile, $photo;
    public $full_name, $gender, $state_id, $city_id, $birth_date;

    // اطلاعات تکمیلی ثبت‌نام (فقط نمایشی)
    public $code_mell, $father_mobile, $mother_mobile;
    public $grade, $field, $is_graduate = false, $attends_school = true;
    public $place_of_birth, $address;

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
        $this->photo = $user->picture ?: $user->profile?->picture;
        $profile = $user->profile;
        $this->full_name = $profile?->full_name;
        $this->gender = $profile?->gender;
        $this->state_id = $profile?->state_id;
        $this->city_id = $profile?->city_id;

        // اطلاعات تکمیلی از جدول personal_information
        $pi = $user->personalInformation;
        $this->code_mell      = $pi?->code_mell;
        $this->father_mobile  = $pi?->father_mobile;
        $this->mother_mobile  = $pi?->mother_mobile;
        $this->grade          = $pi?->grade;
        $this->field          = $pi?->field;
        $this->is_graduate    = (bool) ($pi?->is_graduate);
        $this->attends_school = (bool) ($pi?->attends_school);
        $this->birth_date     = $pi?->birth_date;
        $this->place_of_birth = $pi?->place_of_birth;
        $this->address        = $pi?->address;

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

    /**
     * فهرست آواتارهای آماده بر اساس جنسیت (همان آواتارهای صفحه ثبت‌نام)
     */
    public function avatarOptions(): array
    {
        $gender = $this->gender === 'female' ? 'female' : 'male';

        return Avatar::imagePathsForGender($gender);
    }

    /**
     * انتخاب آواتار آماده — فقط آواتارِ هم‌جنسِ کاربر مجاز است.
     */
    public function selectAvatar(string $path): void
    {
        if (! in_array($path, $this->avatarOptions(), true)) {
            $this->dispatch('warning', 'آواتار انتخابی معتبر نیست.');
            return;
        }

        $user = Auth::user();

        // اگر عکس قبلی یک فایلِ آپلودشده بوده (نه آواتارِ آماده)، آن را حذف کن.
        if ($user->picture && ! str_starts_with($user->picture, '/')) {
            $oldPath = public_path("user/img/{$user->id}/" . $user->picture);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $user->picture = $path;
        $user->save();

        $profile = $user->profile()->firstOrNew();
        $profile->picture = $path;
        $user->profile()->save($profile);

        $this->photo = $path;
        $this->dispatch('success', 'آواتار شما با موفقیت به‌روزرسانی شد.');
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
     * تایید خودکار کد به محض وارد شدن ۶ رقم
     */
    public function updatedOtpCode($value): void
    {
        // فقط ارقام را نگه می‌داریم
        $clean = preg_replace('/\D/', '', (string) $value);
        if ($clean !== $value) {
            $this->otp_code = $clean;
        }
        // به محض کامل شدن ۶ رقم، خودکار اعتبارسنجی شود
        if (strlen($clean) === 6 && !$this->otp_verified) {
            $this->verifyOtp();
        }
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
