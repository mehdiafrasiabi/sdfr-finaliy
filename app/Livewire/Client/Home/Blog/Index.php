<?php

namespace App\Livewire\Client\Home\Blog;

use App\Models\Blog;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
//    public function placeholder()
//    {
//        return view('Layouts.client.placeholder.first-page.blog-skeleton');
//    }
    public function placeholder()

    {

        return view('layouts.client.placeholder.home.home-blog');

    }
    public function render()
    {
        $blogs = Blog::query()
            ->with('category', 'images')
            ->where('status', 'completed') // فقط منتشر شده‌ها
            ->latest()
            ->take(4) // فقط ۴ تا
            ->get();

        return view('livewire.client.home.blog.index', ['blogs' => $blogs])
            ->layout('layouts.client.app');
    }
}
