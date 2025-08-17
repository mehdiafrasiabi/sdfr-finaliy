<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Star extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seo()
            ->setTitle('تبدیل امتیاز ');
    }
    public function render()
    {
        return view('livewire.client.profile.star')->layout('layouts.client.app');
    }
}
