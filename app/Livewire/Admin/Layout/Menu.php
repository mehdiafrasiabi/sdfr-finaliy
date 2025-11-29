<?php

namespace App\Livewire\Admin\Layout;

use Livewire\Component;

class Menu extends Component
{
    public function render()
    {
        return view('livewire.admin.layout.menu')->layout('layouts.admin.app');
    }
}
