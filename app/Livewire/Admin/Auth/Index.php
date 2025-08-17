<?php

namespace App\Livewire\Admin\Auth;

use App\Models\Admin;
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
        $adminGuard = Auth::guard('admin');

        if ($adminGuard->attempt($credentials)) {
            $adminUser = $adminGuard->user();
            if ($adminUser->hasRole('super admin')) {
                // اگر سوپر ادمین است، فوراً لاگ‌اوت کن
                $adminGuard->logout();
                session()->flash('message', ' اجازه ورود به این بخش را ندارید.');
                return redirect()->route('admin.sign-in');
            }

            session()->flash('messageSuccess','مدیر عزیز، خوش اومدی');
            return redirect()->route('admin.dashboard.index');
        } else {
            session()->flash('message','نام کاربری یا رمز عبور نامعتبر است .');
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('admin')->logout();
        return redirect()->route('admin.sign-in');
    }

    public function render()
    {

        return view('livewire.admin.auth.index')->layout('layouts.admin.auth');
    }
}
