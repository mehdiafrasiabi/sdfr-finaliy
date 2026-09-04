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
            ->setTitle('SDFR چیست؟ | سامانه هوشمند برنامه‌ریزی و پیگیری تحصیلی')
            ->setDescription('با SDFR، پنل دانش‌آموز، اولیا و مدرسه، برنامه‌ریزی اختصاصی، ثبت مطالعه، گزارش روزانه، کارنامه هوشمند و پیگیری مشاور را یکپارچه تجربه کنید.');
    }
    public function render()
    {
        return view('livewire.client.about-us.index')->layout('layouts.client.app');
    }
}
