<?php

namespace App\Livewire\Client\Profile\ProfessionalTools;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ابزار های حرفه ای')
            ->setDescription('ابزار های حرفه ای - پروفایل کاربری دانش آموزان');
    }
    public function render()
    {
        return view('livewire.client.profile.professional-tools.index')->layout('layouts.client.app');
    }
}
