<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Notifications extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('اعلانات');
    }
    public function render()
    {
        return view('livewire.client.profile.notifications')->layout('layouts.client.app');
    }
}
