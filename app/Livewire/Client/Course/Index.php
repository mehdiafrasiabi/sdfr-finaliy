<?php

namespace App\Livewire\Client\Course;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.client.course.index')->layout('layouts.client.app');
    }
}
