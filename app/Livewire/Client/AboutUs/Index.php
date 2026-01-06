<?php

namespace App\Livewire\Client\AboutUs;

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
        return view('layouts.client.placeholder.about-us');
    }


    public function seoConfig()
    {
        $this->seo()
            ->setTitle('درباره ما')
            ->setDescription('در این صفحه میتوانید راجب ما وتیم ما بیشتر بدانید.');
    }
    public function render()
    {
        return view('livewire.client.about-us.index')->layout('layouts.client.app');
    }
}
