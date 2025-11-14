<?php

namespace App\Livewire\Manager\AdminManage;

use App\Models\Admin;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Random\RandomException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use SEOTools, WithPagination, WithFileUploads; // 👈 اینجا هم اضافه کن

    public $name;
    public $email;
    public $mobile;
    public $permissions=[];
    public $roles=[];
    public $selectedPermissions=[];
    public $selectedRoles=[];

    // فیلدهای جدید
    public $national_code;
    public $contract;
    public $address;
    public $postal_code;
    public $document;

    public function mount()
    {
        $this->roles = Role::all();
        $this->permissions=Permission::all();
        $this->seoCoinfig();;
    }

    public function seoCoinfig()
    {
        $this->seo()->setTitle('مدیرت ادمین ها و پشتیبانی');
    }
    /**
     * @throws RandomException
     * @throws ValidationException
     */
    public function submit($formData)
    {
        $formData['selectedRoles'] = $this->selectedRoles;
        $formData['selectedPermissions'] = $this->selectedPermissions;

        $validator = Validator::make(array_merge($formData, [
            'national_code' => $this->national_code,
            'address'       => $this->address,
            'postal_code'   => $this->postal_code,
            'document'      => $this->document,
            'contract'      => $this->contract,
        ]), [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|string|email|max:255|unique:admins,email',
            'mobile'               => 'required|regex:/^09\d{9}$/|unique:admins,mobile',
            'national_code'        => 'required|digits:10|unique:admins,national_code',
            'address'              => 'required|string|max:500',
            'postal_code'          => 'required|digits:10',
            'document'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'contract'             => 'required|file|mimes:pdf|max:2048',
            'selectedRoles'        => 'required|array',
            'selectedRoles.*'      => 'exists:roles,id',
            'selectedPermissions'  => 'required|array',
            'selectedPermissions.*'=> 'exists:permissions,id',
        ], [
            '*.required'   => 'فیلد ضروری است',
            'email.email'  => 'یک ایمیل معتبر وارد کنید',
            '*.unique'     => 'این مقدار تکراری است',
            '*.regex'      => 'فرمت اشتباه است',
            '*.digits'     => 'باید دقیقا :digits رقم باشد',
            'document.mimes' => 'فقط pdf یا تصویر مجاز است',
            'contract.mimes' => 'فقط pdf  مجاز است',
        ]);

        $validator->validate();
        $this->resetValidation();

        $password = $this->generatePassword();

        $admin = Admin::query()->create([
            'name'         => $formData['name'],
            'email'        => $formData['email'],
            'mobile'       => $formData['mobile'],
            'password'     => Hash::make($password),
            'national_code'=> $this->national_code,
            'address'      => $this->address,
            'postal_code'  => $this->postal_code,
        ]);
        if ($this->document) {
            $random= mt_rand(10000000,99999999999999);
            $filename = 'document' . time() .$random. '.' . $this->document->getClientOriginalExtension();

            // مسیر پوشه public/adminsFile/{adminId}/
            $path = public_path("adminsFile/{$admin->id}/");

            // اگر پوشه وجود نداشت می‌سازیم
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // انتقال فایل به پوشه public
            $this->document->storeAs("adminsFile/{$admin->id}", $filename, 'public');

            // فقط نام فایل ذخیره بشه
            $admin->update(['document' => $filename]);
        }

        if ($this->contract) {
            $random= mt_rand(10000000,99999999999999);
            $filename = 'contract_' . time() .$random. '.' . $this->contract->getClientOriginalExtension();

            // مسیر پوشه public/adminsFile/{adminId}/
            $path = public_path("adminsFile/{$admin->id}/");

            // اگر پوشه وجود نداشت می‌سازیم
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // انتقال فایل به پوشه public
            $this->contract->storeAs("adminsFile/{$admin->id}", $filename, 'public');

            // فقط نام فایل ذخیره بشه
            $admin->update(['contract' => $filename]);
        }
        $admin->roles()->sync($formData['selectedRoles']);
        $admin->permissions()->sync($formData['selectedPermissions']);
        $this->dispatch('success','با موفقیت افزوده شد');
        session()->flash('message', 'ادمین با موفقیت افزوده شد ، پسورد :'.$password);
        $this->reset(['name','email','mobile','national_code','address','postal_code','document','contract','selectedRoles','selectedPermissions']);

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

    public function delete(int $adminId)
    {
        // پیدا کردن ادمین یا ارور 404
        $admin = Admin::findOrFail($adminId);

        // اگر می‌خواهید رابطه‌ها را جدا کنید (اختیاری)
        $admin->roles()->detach();
        $admin->permissions()->detach();

        // حذف رکورد
        $admin->delete();

        // فلاش مسیج برای اطلاع‌رسانی
        $this->dispatch('success', "ادمین «{$admin->name}» با موفقیت حذف شد.");

    }

    public function render()
    {
        $admins = Admin::query()->with('roles.permissions')->latest()->paginate(10);
        return view('livewire.manager.admin-manage.index',
            ['admins'=>$admins])->layout('layouts.manager.app');
    }
}
