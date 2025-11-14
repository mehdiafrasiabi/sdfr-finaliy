<?php

namespace App\Livewire\Manager\Story;

use App\Models\Story;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,WithFileUploads,SEOTools;
    public $title;
    public $thumbnail;
    public $story;

    public $selectedMedia; // URL
    public $mediaType;     // 'image' یا 'video'


    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('استوری ها');
    }
    public function submit($formData)
    {
        if ($this->thumbnail) {
            $formData['thumbnail'] = $this->thumbnail;
        }
        if ($this->story) {
            $formData['story'] = $this->story;
        }

        $validator = Validator::make($formData, [
            'title' => 'required|string|max:50',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp|max:1024',//1MB
            'story' => 'required|mimes:mp4,MOV|max:61440',//60MB
        ], [
            '*.required' => 'فیلد ضروری است.',
            '*.string' => 'فرمت اشتباه است !',
            'thumbnail.mimes' => 'فرمت های مجاز تصویر : jpg,jpeg,png,webp !',
            'story.mimes' => 'فرمت های مجاز استوری : mp4 !',
            'thumbnail.max' => 'سایز تصویر حداکثر : ! 1MB',
            'story.max' => 'سایز استوری حداکثر : ! 60MB',
        ]);

        $validator->validate();
        $this->resetValidation();


        $thumbnailFilename = pathinfo($this->thumbnail->hashName(), PATHINFO_FILENAME) . '.' . $this->thumbnail->extension();
        $storyFilename = pathinfo($this->story->hashName(), PATHINFO_FILENAME) . '.' . $this->story->extension();

        Story::query()->create([
            'title' => $formData['title'],
            'thumbnail' => $thumbnailFilename,
            'story' => $storyFilename,
        ]);

        Storage::disk('public')->put('stories/thumbnail', $this->thumbnail);
        Storage::disk('public')->put('stories/story', $this->story);
        $this->dispatch('success','با موفقیت اضافه شد .');


    }

    public function delete(Story $story)
    {
        $thumbnail= $story->thumbnail;
        $storyFile = $story->story;
        File::delete('stories/thumbnail/' . $thumbnail);
        File::delete('stories/story/' . $storyFile);
        $story->delete();
        $this->dispatch('success','با موفقیت حذف شد .');
    }

    public function changeStatus(Story $story)
    {
        if ($story->status){
            $story->update(['status' => false]);
        }else{
            $story->update(['status' => true]);
        }
        $this->dispatch('success',' عملیات با موفقیت انجام شد');

    }
    public function showMedia($type, $url)
    {
        $this->mediaType = $type;
        $this->selectedMedia = $url;

        $this->dispatch('open-selfie-modal');
    }
    public function render()
    {
        $stories = Story::query()->latest()->paginate(10);
        return view('livewire.manager.story.index',['stories'=>$stories])->layout('layouts.manager.app');
    }
}
