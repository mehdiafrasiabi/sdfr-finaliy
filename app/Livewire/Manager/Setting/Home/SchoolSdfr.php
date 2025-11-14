<?php

namespace App\Livewire\Manager\Setting\Home;

use App\Models\SdfrSchool;
use App\Models\SdfrStudent;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class SchoolSdfr extends Component
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
            ->setTitle('حضور SDFR ، در هر مدرسه');
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


        $filename = $this->uploadImageInWebpFormatSdfrSchool(
            $this->document,
            'schoolSdfr', // می‌تونی اینجا ID یا دسته‌بندی بدی
            300,       // عرض دلخواه
            300,       // ارتفاع دلخواه
            'client/sdfr/'
        );


        // ذخیره در دیتابیس
        SdfrSchool::query()->create([
            'name' => $formData['name'],
            'document' => $filename,
        ]);

        // پاک کردن ورودی‌ها
        $this->reset(['name','document']);

        $this->dispatch('success','با موفقیت اضافه شد .');
    }


    public function changeStatus(SdfrSchool $story)
    {
        if ($story->status){
            $story->update(['status' => false]);
        }else{
            $story->update(['status' => true]);
        }
        $this->dispatch('success',' عملیات با موفقیت انجام شد');

    }


    public function delete(SdfrSchool $story)
    {
        $document = $story->document;

        if ($document && Storage::disk('public')->exists('client/sdfr/schoolSdfr/' . $document)) {
            Storage::disk('public')->delete('client/sdfr/schoolSdfr/' . $document);
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
        $sdfrStudents = SdfrSchool::query()->latest()->paginate(10);
        return view('livewire.manager.setting.home.school-sdfr',[
            'sdfrStudents'=>$sdfrStudents
        ])->layout('layouts.manager.app');
    }
}
