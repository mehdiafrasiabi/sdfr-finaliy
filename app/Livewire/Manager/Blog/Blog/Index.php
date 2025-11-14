<?php

namespace App\Livewire\Manager\Blog\Blog;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $status = 'all';
    public $search = '';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Blog::query()->with('category','images');

        if($this->status != 'all'){
            $query->where('status', $this->status);
        }

        if($this->search){
            $query->where(function($q){
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('blog_code', 'like', '%'.$this->search.'%');
            });
        }

        $blogs = $query->latest()->paginate(10);
        return view('livewire.manager.blog.blog.index',['blogs'=>$blogs])->layout('layouts.manager.app');
    }
}
