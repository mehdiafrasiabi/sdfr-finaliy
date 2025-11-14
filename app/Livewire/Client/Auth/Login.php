<?php

namespace App\Livewire\Client\Auth;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Login extends Component
{
    use SEOTools;
    public $mobile = '';
    public $password = '';

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()->setTitle('ورورد');
    }

    public function submit($formData)
    {
        $validator = Validator::make($formData,
            [
                'mobile' => ['required', 'exists:users', 'regex:/^09[0-9]{9}$/', 'digits:11'],
                'password' => 'required', 'min:8',
            ],
            [
                '*.required' => 'لطفا این قسمت را خالی نگذارید',
                'mobile.exists' => 'نام کاربری نامعتبر',
                'mobile.digits' => 'شماره موبایل باید دقیقا ۱۱ رقم باشد.',
                'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد!',
                'mobile.regex' => 'فقط از اعداد انگلیسی استفاده کنید (مثل 09123456789)'
            ]
        );

        $validator->validate();
        $this->resetValidation();

        if (!Auth::attempt([
            'mobile' => $formData['mobile'],
            'password' => $formData['password'],
        ], remember: true)) {
            $this->dispatch('error', 'نام کاربری یا رمز عبور نامعتبر است .');
        } else {
            $this->dispatch('success', 'خوش اومدی!!!');
            return redirect()->route('client.profile.dashboard');
        }
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
