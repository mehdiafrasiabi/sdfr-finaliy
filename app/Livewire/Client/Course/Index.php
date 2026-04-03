<?php

namespace App\Livewire\Client\Course;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;
    public function mount()
    {
        $this->seo()
            ->setTitle('دوره های ویدیویی مشاوره ای')
            ->setDescription('دوره و آموزش تا روز کنکور-آموزش انتخاب رشته-راهنمای انتخاب رشته تحصیلی')
        ;
    }
    public function render()
    {
        return view('livewire.client.course.index')->layout('layouts.client.app');
    }
}
