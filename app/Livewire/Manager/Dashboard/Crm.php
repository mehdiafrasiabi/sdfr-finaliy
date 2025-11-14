<?php

namespace App\Livewire\Manager\Dashboard;

use Livewire\Component;

class Crm extends Component
{
    public function render()
    {
        return view('livewire.manager.dashboard.crm')->layout('layouts.manager.app');
    }
}
