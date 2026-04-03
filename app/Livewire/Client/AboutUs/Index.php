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
            ->setDescription('SDFR، اولین سامانه هوشمند مشاوره و آنالیز دقیق تحصیلی در ایران! با صرفه جویی در وقت و هزینه، پشتیبانی تحصیلی روزانه و ابزار های حرفه ای و هوشمند آموزشی حس پیشرفت در آزمون های تشریحی و تستی را تجربه کنید!');
    }
    public function render()
    {
        return view('livewire.client.about-us.index')->layout('layouts.client.app');
    }
}
