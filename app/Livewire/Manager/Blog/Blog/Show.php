<?php

namespace App\Livewire\Manager\Blog\Blog;

use App\Models\Blog;
use Livewire\Component;

class Show extends Component
{
    public $blog;

    public function mount($blog)
    {
        $this->blog = Blog::with('images','seo')->findOrFail($blog);
    }

    public function approve()
    {
        $this->blog->update(['status' => 'completed']);
        session()->flash('success', 'بلاگ تایید شد.');
        return redirect()->route('manager.blog.index');
    }

    public function reject()
    {
        $this->blog->update(['status' => 'rejected']);
        session()->flash('success', 'بلاگ رد شد.');
        return redirect()->route('manager.blog.index');
    }

    public function render()
    {
        return view('livewire.manager.blog.blog.show')->layout('layouts.manager.app');
    }
}
