<?php

namespace App\Livewire\Client\Home;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Perview extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('SDFR | پلتفرم هوشمند پایش و مشاوره‌ی تحصیلی')
            ->setDescription('SDFR یک پلتفرم یکپارچه برای پایش مطالعه، برنامه‌ریزی هفتگی، مشاوره‌ی تخصصی و گزارش‌گیری هوشمند است؛ همراهی مطمئن برای دانش‌آموزان، اولیا و مدارس در مسیر پیشرفت تحصیلی.');
    }

    public function render()
    {
        return view('livewire.client.home.perview')->layout('layouts.client.app');
    }
}
