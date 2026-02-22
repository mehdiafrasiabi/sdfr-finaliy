<?php

namespace App\Livewire\Client\Download;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public function mount(): void
    {
        $this->seoConfig();
    }

    public function seoConfig(): void
    {
        $this->seo()
            ->setTitle('دانلود اپلیکیشن SDFR | نسخه PWA')
            ->setDescription('اپلیکیشن SDFR را به صورت PWA نصب کنید و با یک کلیک روی گوشی یا دسکتاپ به خدمات سایت دسترسی داشته باشید.');
    }

    public function render()
    {
        return view('livewire.client.download.index')->layout('layouts.client.app');
    }
}
