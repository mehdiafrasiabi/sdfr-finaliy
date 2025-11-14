<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog;
use App\Models\Category;
use App\Models\BlogImage;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads,UploadFile;

    public $photos = [];
    public $coverIndex = 0;
    public $categories = [];
    public $blogId;
    public $blog;

    public $title;
    public $slug;
    public $description;
    public $study_time;
    public $category_id;

    public $meta_title;
    public $meta_description;

    public function mount()
    {
        if (request()->has('blog_id')) {
            $this->blogId = request('blog_id');
            $this->blog   = Blog::with('seo','images')->findOrFail($this->blogId);

            $this->title          = $this->blog->title;
            $this->slug           = $this->blog->seo->slug ?? Str::slug($this->blog->title);
            $this->description    = $this->blog->description;
            $this->study_time     = $this->blog->study_time;
            $this->category_id    = $this->blog->category_id;
            $this->meta_title     = $this->blog->seo->meta_title ?? '';
            $this->meta_description = $this->blog->seo->meta_description ?? '';
        }

        $this->categories = Category::all();
    }

    public function updatedTitle()
    {
        $this->slug = Str::slug($this->title, '-', null);
    }

    public function submit($formData, Blog $blog)
    {

        $formData['photos']       = $this->photos;
        $formData['coverIndex']   = $this->coverIndex;
        $formData['slug']         = $this->slug;
        $formData['meta_title']   = $this->meta_title;
        $formData['meta_description'] = $this->meta_description;

        $validator = Validator::make($formData, [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'study_time'   => 'required|string|max:30',
            'category_id'  => 'required|exists:categories,id',
            'slug'         => 'required|string',
            'meta_title'   => 'required|string',
            'meta_description' => 'required|string',
            'photos.*'     => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'coverIndex'   => 'required',
        ]);

        $validator->validate();
        $this->resetValidation();

        // ذخیره در مدل Blog
        $blog->submit($formData, $this->blogId, $this->photos, $this->coverIndex);

        session()->flash('success', $this->blogId ? 'بلاگ ویرایش شد.' : 'بلاگ جدید ساخته شد و در انتظار تایید ادمین است.');
        return $this->redirect(route('admin.blog.index'));
    }

    public function setCoverImage($index)
    {
        $this->coverIndex = $index;
    }

    public function removePhoto($index)
    {
        if ($index === $this->coverIndex) {
            $this->coverIndex = null;
        }
        array_splice($this->photos, $index, 1);
    }

    public function render()
    {
        return view('livewire.admin.blog.create')->layout('layouts.admin.app');
    }
}
