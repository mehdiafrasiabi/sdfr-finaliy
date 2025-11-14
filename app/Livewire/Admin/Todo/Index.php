<?php

namespace App\Livewire\Admin\Todo;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.todo.index')->layout('layouts.admin.app');
    }
}
