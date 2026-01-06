<?php

namespace App\Livewire\Client\Home\Feedback;

use Livewire\Component;

class Index extends Component
{
    public function placeholder()

    {

        return view('layouts.client.placeholder.home.home-feedback');

    }
    public function render()
    {
        return view('livewire.client.home.feedback.index')->layout('layouts.client.app');
    }
}
