<?php

namespace App\Livewire\Client\Blog\Weblog;

use App\Models\Blog;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Show extends Component
{
    use SEOTools;

    public $blog;

    public function mount($blog_code, $slug)
    {
        $this->blog = Blog::where('blog_code', $blog_code)
            ->whereHas('seo', function($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->with('images', 'category', 'seo')
            ->firstOrFail();

        $this->seo()
            ->setTitle($this->blog->seo->meta_title ?? $this->blog->title)
            ->setDescription($this->blog->seo->meta_description ?? substr($this->blog->excerpt, 0, 150));
    }

    public function render()
    {
        return view('livewire.client.blog.weblog.show')->layout('layouts.client.app');
    }
}
