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
            ->setTitle('درباره SDFR | اولین مشاوره تحصیلی هوشمند درایران')
            ->setDescription('تیم مشاوره تحصیلی هوشمند SDFR مفتخر است، با استفاده از رویکردی نوین، مشاور رتبه های برتر کنکور سراسری طی دهه گذشته باشد.!');
    }
    public function render()
    {
        return view('livewire.client.about-us.index')->layout('layouts.client.app');
    }
}
