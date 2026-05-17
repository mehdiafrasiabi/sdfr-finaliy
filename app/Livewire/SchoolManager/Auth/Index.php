<?php

namespace App\Livewire\SchoolManager\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Index extends Component
{
    public function submit($formData)
    {
        Validator::make($formData, [
            'mobile'   => ['required', 'regex:/^09\d{9}$/'],
            'password' => 'required|string',
        ], [
            '*.required'    => 'فیلد ضروری است',
            'mobile.regex'  => 'شماره موبایل معتبر نیست',
        ])->validate();

        $ok = Auth::guard('school-manager')->attempt([
            'mobile'   => $formData['mobile'],
            'password' => $formData['password'],
        ]);

        if (!$ok) {
            $this->addError('password', 'موبایل یا رمز عبور نادرست است');
            return;
        }

        session()->flash('messageSuccess', 'خوش آمدید');
        return redirect()->route('school-manager.dashboard');
    }

    public function logout()
    {
        Session::flush();
        Auth::guard('school-manager')->logout();
        return redirect()->route('school-manager.sign-in');
    }

    public function render()
    {
        return view('livewire.school-manager.auth.index')->layout('layouts.school-manager.auth');
    }
}
