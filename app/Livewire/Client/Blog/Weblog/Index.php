<?php
namespace App\Livewire\Client\Blog\Weblog;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;
use Artesaos\SEOTools\Traits\SEOTools;

class Index extends Component
{
    use WithPagination, SEOTools;

    public $categoryId = null;
    public $perPage = 6;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->seo()
            ->setTitle('مقالات سایت');
    }
    public function placeholder()

    {

        return view('layouts.client.placeholder.blog-weblog');

    }
    public function loadMore()
    {
        $this->perPage += 6;
    }

    public function render()
    {
        $blogs = Blog::query()->with('category','images')
            ->where('status', 'completed') // فقط منتشر شده‌ها
            ->when($this->categoryId, function ($q) {
                $q->where('category_id', $this->categoryId);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.client.blog.weblog.index', compact('blogs'))
            ->layout('layouts.client.app');
    }
}
