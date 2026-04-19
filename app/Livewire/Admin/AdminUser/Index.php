<?php

namespace App\Livewire\Admin\AdminUser;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Random\RandomException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    public $name;
    public $email;
    public $mobile;
    public $permissions=[];
    public $roles=[];
    public $selectedPermissions=[];
    public $selectedRoles=[];


    public function mount()
    {
        $this->roles = Role::all();
        $this->permissions=Permission::all();
    }
    /**
     * @throws RandomException
     * @throws ValidationException
     */
    public function submit($formData)
    {
        $formData['selectedRoles'] = $this->selectedRoles;
        $formData['selectedPermissions'] = $this->selectedPermissions;
        $validator = Validator::make($formData,
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'mobile' => 'required|regex:/^09\d{9}$/|unique:admins.mobile',
                'selectedRoles'=>'required|array',
                'selectedRoles.*'=>'exists:roles,id',
                'selectedPermissions'=> 'required|array',
                'selectedPermissions.*'=>'exists:permissions,id'
            ],
            [
                '*.required'=>'فیلد ضروری است',
                'email.email'=>'یک ایمیل معتبر وارد کنید',
                '*.unique'=>'تکراری است و این شماره موبایل یا ایمیل قبلا وجود داشته است ',
                '*.regex'=>'شماره موبایل اشتباه است ',
                '*.string'=>'فرمت نوشتاری شما اشتباه است',
                '*.max'=>'حداکثر نوشتن : 255 کارکتر',
                '*.exists'=>'نا معتبر ! نامعتبر!',
            ]
        );
        $validator->validate();
        $this->resetValidation();
        $password = $this->generatePassword();
        $admin= Admin::query()->create(
            [
                'name' => $formData['name'],
                'email' => $formData['email'],
                'mobile' => $formData['mobile'],
                'password' => Hash::make($password)
            ]
        );
        $admin->roles()->sync($formData['selectedRoles']);
        $admin->permissions()->sync($formData['selectedPermissions']);
        session()->flash('message', 'ادمین با موفقیت افزوده شد ، پسورد :'.$password);
    }

    /**
     * @throws RandomException
     */
    public function generatePassword($length=12)
    {
        //کارکتر های مختلف
        $numbers='0123456789';
        $lowercases = 'abcdefghijklmnopqrstuvwxyz';
        $uppercases = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbol = '!@#$%^&*()[}]|\/';

        //حداقل یک عدد ، یک حرف بزرگ ، یک حرف کوچک و یک سمبول اضافه میشود
        $password =[
            $numbers[random_int(0, strlen($numbers) - 1)],
            $lowercases[random_int(0, strlen($lowercases) - 1)],
            $uppercases[random_int(0, strlen($uppercases) - 1)],
            $symbol[random_int(0, strlen($symbol) - 1)],
        ];
        //انتخاب کارکتر تصادفی
        $allCharset = $numbers . $lowercases . $uppercases . $symbol;
        while (count($password) < $length) {
            $char = $allCharset[random_int(0, strlen($allCharset) - 1)];
            if (count($password) > 0 && $password[count($password) - 1] === $char) {
                continue;
            }
            $password[] = $char;

        }
        //ترکیب کارکتر ها
        shuffle($password);
        return implode('', $password);
    }


    public function render()
    {
        $admins = Admin::query()->with(['roles.permissions', 'workSchedules'])->paginate(10);
        return view('livewire.admin.admin-user.index',
            ['admins'=>$admins]
        )->layout('layouts.admin.app');
    }
}
