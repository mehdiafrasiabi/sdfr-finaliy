<?php

namespace App\Livewire\Client\Home\Intro;

use Livewire\Component;

class Index extends Component
{
    public function placeholder()
    {
        return view('Layouts.client.placeholders.first-page.intro-skeleton');
    }
    public function render()
    {
        return view('livewire.client.home.intro.index');
    }
}
