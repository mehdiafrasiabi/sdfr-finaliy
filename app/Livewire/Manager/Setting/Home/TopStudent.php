<?php

namespace App\Livewire\Manager\Setting\Home;

use App\Models\SdfrStudent;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class TopStudent extends Component
{
    use WithPagination,UploadFile,WithFileUploads,SEOTools;
    public $name;
    public $document;
    public $selectedMedia; // URL
    public $mediaType;     // 'image' یا 'video'

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('گوشه از لبخند ستارگان');
    }

    public function submit($formData)
    {
        if ($this->document) {
            $formData['document'] = $this->document;
        }

        $validator = Validator::make($formData, [
            'name' => 'required|string|max:50',
            'document' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
        ], [
            '*.required' => 'فیلد ضروری است.',
            '*.string' => 'فرمت اشتباه است !',
            'document.mimes' => 'فرمت های مجاز تصویر : jpg,jpeg,png,webp !',
            'document.max' => 'سایز تصویر حداکثر : 2MB',
        ]);

        $validator->validate();
        $this->resetValidation();


        $filename = $this->uploadImageInWebpFormatSdfrStudent(
            $this->document,
            'topStudent', // می‌تونی اینجا ID یا دسته‌بندی بدی
            300,       // عرض دلخواه
            300,       // ارتفاع دلخواه
            'client/sdfr/'
        );


        // ذخیره در دیتابیس
        SdfrStudent::query()->create([
            'name' => $formData['name'],
            'document' => $filename,
        ]);

        // پاک کردن ورودی‌ها
        $this->reset(['name','document']);

        $this->dispatch('success','با موفقیت اضافه شد .');
    }


    public function changeStatus(SdfrStudent $story)
    {
        if ($story->status){
            $story->update(['status' => false]);
        }else{
            $story->update(['status' => true]);
        }
        $this->dispatch('success',' عملیات با موفقیت انجام شد');

    }


    public function delete(SdfrStudent $story)
    {
        $document = $story->document;

        if ($document && Storage::disk('public')->exists('client/sdfr/topStudent/' . $document)) {
            Storage::disk('public')->delete('client/sdfr/topStudent/' . $document);
        }

        $story->delete();
        $this->dispatch('success','با موفقیت حذف شد .');
    }


    public function showMedia($type, $url)
    {
        $this->mediaType = $type;
        $this->selectedMedia = $url;

        $this->dispatch('open-selfie-modal');
    }

    public function render()
    {
        $sdfrStudents = SdfrStudent::query()->latest()->paginate(10);
        return view('livewire.manager.setting.home.top-student',['sdfrStudents'=>$sdfrStudents])->layout('layouts.manager.app');
    }
}
