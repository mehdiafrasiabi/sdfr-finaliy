<?php

namespace App\Livewire\Manager\Users;

use App\Models\Coupons;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,SEOTools;

    public string $search = '';
    public $name, $email, $mobile,$password;
    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()->setTitle(' لیست کاربران');
    }
    protected $updatesQueryString = ['search'];

    public function submit($formData)
    {
        $validator = Validator::make($formData, [
            'name' => ['required', 'string', 'max:55', 'regex:/^[\p{Arabic}\s]+$/u'],
            'mobile' => ['required', 'regex:/^09\d{9}$/', 'unique:users,mobile'],
        ], [
            'name.required' => 'وارد کردن نام الزامی است!',
            'name.string' => 'فرمت نام صحیح نیست.',
            'name.regex' => 'نام و نام خانوادگی باید فقط با حروف فارسی نوشته شود!',
            'mobile.required' => 'شماره موبایل الزامی است!',
            'mobile.regex' => 'شماره موبایل نامعتبر است!',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است!',
        ]);
        $validator->validate();

        User::create([
            'name' => $this->name,
            'mobile' => $this->mobile,
            'created_at' => now(),
            'password' => bcrypt('@Sdfr1404'),
        ]);

        $this->dispatch('success', 'با موفقیت افزوده شد.');
        $this->dispatch('closeModal'); // 🟢 ارسال event برای بستن مودال با جاوااسکریپت

        $this->reset(['name', 'mobile']);
        $this->resetPage(); // برای اینکه داده‌ها رفرش بشن
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')->latest()
            ->paginate(10);

        return view('livewire.manager.users.index',[
            'users' => $users,
        ])->layout('layouts.manager.app');
    }
}
