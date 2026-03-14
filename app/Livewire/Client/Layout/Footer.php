<?php

namespace App\Livewire\Client\Layout;

use App\Models\GeneralSetting;
use Livewire\Component;

class Footer extends Component
{
    public function render()
    {
        $settings = GeneralSetting::first();
        return view('livewire.client.layout.footer', [

            'settings' => $settings

        ])->layout('layouts.client.app');

    }

}
