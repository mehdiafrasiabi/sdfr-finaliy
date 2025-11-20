<?php

namespace App\Livewire\Manager\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Index extends Component
{

    public function submit($formData)
    {
        $validator = Validator::make($formData,
            [
                'email' => 'required|email|exists:admins',
                'mobile' => ['required','exists:admins','regex:/^09\d{9}$/'],
                'password' => 'required',
            ],
            [
                '*.required'=>'فیلد اجباری است .',
                'email.exists'=>'نام کاربری نامعتبر',
                'mobile.exists'=>'تلفن همراه  نامعتبر',
                'mobile.regex'=>'لطفا شماره تلفن همراه خود را به درستی وارد کنید',

            ]
        );
        $validator->validate();
        $this->resetValidation();
        $credentials = ['email'=> $formData['email'],'mobile'=> $formData['mobile'],'password' => $formData['password']];

        $admin = Auth::guard('manager');
        if ($admin->attempt($credentials)) {
            $adminUser = $admin->user();

            if (!$adminUser->hasRole('super admin')) {
                Auth::guard('manager')->logout();
                session()->flash('message');
                return; // از اینجا خارج شو، ری‌دایرکت یا نمایش پیام خطا
            }

            session()->flash('messageSuccess','مدیر کل عزیز، خوش آمدید');
            return redirect()->route('manager.dashboard.crm');
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('manager')->logout();
        return redirect()->route('manager.sign-in');
    }
    public function render()
    {
        return view('livewire.manager.auth.index')->layout('layouts.manager.auth');
    }
}
