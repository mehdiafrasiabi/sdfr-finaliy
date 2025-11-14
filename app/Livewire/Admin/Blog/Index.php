<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,SEOTools;
    public $search = '';


    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('بلاگ');
    }

    public function render()
    {
        $blogs = Blog::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('blog_code', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->with('category')
            ->paginate(10);

        return view('livewire.admin.blog.index',['blogs'=>$blogs])->layout('layouts.admin.app');
    }
}
