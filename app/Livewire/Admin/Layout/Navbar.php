<?php

namespace App\Livewire\Admin\Layout;

use Livewire\Component;

class Navbar extends Component
{
    public function render()
    {
        return view('livewire.admin.layout.navbar')->layout('layouts.admin.app');
    }
}
