<?php

namespace App\Livewire\Manager\Setting;

use App\Models\PercentCalculatorSetting;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class PercentCalculator extends Component
{
    use WithFileUploads, SEOTools, UploadFile;

    public $title = '';
    public $subtitle = '';
    public $description = '';
    public $meta_title = '';
    public $meta_description = '';
    public $photo = null;
    public $existingImage = null;
    public $settingId = null;

    public function mount()
    {
        $this->seo()->setTitle('درصد گیر');

        $setting = PercentCalculatorSetting::first();

        if ($setting) {
            $this->settingId      = $setting->id;
            $this->title          = $setting->title ?? '';
            $this->subtitle       = $setting->subtitle ?? '';
            $this->description    = $setting->description ?? '';
            $this->meta_title     = $setting->meta_title ?? '';
            $this->meta_description = $setting->meta_description ?? '';
            $this->existingImage  = $setting->image;
        }
    }

    public function removeExistingImage()
    {
        if ($this->existingImage) {
            $path = public_path('percent-calculator/' . $this->existingImage);
            if (file_exists($path)) {
                @unlink($path);
            }
            $setting = PercentCalculatorSetting::first();
            if ($setting) {
                $setting->update(['image' => null]);
            }
            $this->existingImage = null;
        }
    }

    public function save()
    {
        $validator = Validator::make(
            [
                'title'            => $this->title,
                'subtitle'         => $this->subtitle,
                'description'      => $this->description,
                'meta_title'       => $this->meta_title,
                'meta_description' => $this->meta_description,
                'photo'            => $this->photo,
            ],
            [
                'title'            => 'required|string|max:200',
                'subtitle'         => 'nullable|string|max:500',
                'description'      => 'nullable|string',
                'meta_title'       => 'nullable|string|max:200',
                'meta_description' => 'nullable|string|max:500',
                'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ],
            [
                'title.required' => 'عنوان الزامی است.',
                'photo.image'    => 'فایل باید تصویر باشد.',
                'photo.mimes'    => 'فرمت تصویر مجاز نیست.',
                'photo.max'      => 'حجم تصویر نباید بیشتر از ۴ مگابایت باشد.',
            ]
        );

        $validator->validate();

        $imageName = $this->existingImage;

        if ($this->photo) {
            // حذف عکس قدیمی
            if ($this->existingImage) {
                $oldPath = public_path('percent-calculator/' . $this->existingImage);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $imageName = $this->uploadImageInWebpFormatPercentCalculator($this->photo, 1200, null);
            $this->existingImage = $imageName;
            $this->photo = null;
        }

        $setting = PercentCalculatorSetting::updateOrCreate(
            ['id' => $this->settingId ?? 0],
            [
                'title'            => $this->title,
                'subtitle'         => $this->subtitle,
                'description'      => $this->description,
                'meta_title'       => $this->meta_title,
                'meta_description' => $this->meta_description,
                'image'            => $imageName,
            ]
        );

        $this->settingId = $setting->id;

        $this->dispatch('success', 'درصد گیر با موفقیت ذخیره شد.');
    }

    public function render()
    {
        return view('livewire.manager.setting.percent-calculator')
            ->layout('layouts.manager.app');
    }
}
