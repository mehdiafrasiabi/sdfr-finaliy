<?php

namespace App\Livewire\Manager\Layout;

use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        return view('livewire.manager.layout.header')->layout('layouts.manager.app');
    }
}
