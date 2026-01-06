<?php

namespace App\Livewire\Client\Terms;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }

    public function placeholder()
    {
        return view('layouts.client.placeholder.terms');
    }


    public function seoConfig()
    {
        $this->seo()
            ->setTitle('قوانین و مقررات')
            ->setDescription('در این صفحه شرایط استفاده از وبسایت آموزشی و مشاوره ای SDFR را مشاهده میکنید.');
    }

    public function render()
    {
        return view('livewire.client.terms.index')->layout('layouts.client.app');
    }
}
