<?php

namespace App\Livewire\Manager\Layout;

use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return view('livewire.manager.layout.sidebar')->layout('layouts.manager.app');
    }
}
