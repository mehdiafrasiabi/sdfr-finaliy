<?php

namespace App\Livewire\Manager\Dashboard;

use Livewire\Component;

class Analytics extends Component
{
    public function render()
    {
        return view('livewire.manager.dashboard.analytics')->layout('layouts.manager.app');
    }
}
