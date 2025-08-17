<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class Dashboard extends Component
{

    use SEOTools;
    public function mount()
    {
        $this->seoConfig();
        $this->student = Auth::user()->student ?? null;

    }

    public function seoConfig()
    {
        $this->seo()->setTitle('پیشخوان');
    }


    public function render()
    {
        $supporterStudent= $this->student?->supporterStudent;

        return view('livewire.client.profile.dashboard',[
            'supporterStudent' => $supporterStudent,
        ])->layout('layouts.client.app');
    }
}

