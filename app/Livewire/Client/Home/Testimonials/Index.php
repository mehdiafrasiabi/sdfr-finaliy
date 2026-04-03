<?php

namespace App\Livewire\Client\Home\Testimonials;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.client.home.testimonials.index')->layout('layouts.client.app');
    }
}
