<?php

namespace App\Livewire\Client\Profile;

use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Edit extends Component
{
    use SEOTools,WithFileUploads,UploadFile;

    public $name, $email, $photo, $new_photo;

    public function mount()
    {
        $this->seoConfig();

        $user = Auth::user(); // یا Student::find(Auth::id()) در صورت نیاز
        $this->name = $user->name;
        $this->email = $user->email;
        $this->photo = $user->picture;
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
            'email' => ['required', 'email'],
            'new_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'name.string' => 'فرمت نام معتبر نیست.',
            'name.min' => 'نام باید حداقل ۳ کاراکتر داشته باشد.',
            'name.max' => 'نام نمی‌تواند بیشتر از ۱۵۰ کاراکتر باشد.',

            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل معتبر نیست.',

            'new_photo.required' => 'انتخاب تصویر الزامی است.',
            'new_photo.image' => 'فایل انتخابی باید یک تصویر باشد.',
            'new_photo.mimes' => 'فرمت‌های مجاز: jpg, jpeg, png, webp',
            'new_photo.max' => 'حجم تصویر نباید بیشتر از ۱ مگابایت باشد.',
        ]);


        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;


        if ($this->new_photo) {
            // حذف عکس قبلی
            if ($user->picture) {
                $oldPath = public_path("user/img/{$user->id}/" . $user->picture);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // تولید نام و ذخیره
            $filename = $this->uploadImageInWebpFormatProfile($this->new_photo, $user->id, 150, 150, 'img',);

            $user->picture = $filename; // فقط نام فایل
        }


        $user->save();
        $this->dispatch('success','پروفایل با موفقیت به‌روزرسانی شد');
        session()->flash('success', 'پروفایل با موفقیت به‌روزرسانی شد.');

        $this->redirect(route('client.profile.edit'));
    }
    public function render()
    {
        return view('livewire.client.profile.edit')->layout('layouts.client.app');
    }
}
