<?php

namespace App\Livewire\Client\Home\TopStudent;

use App\Models\SdfrStudent;
use Livewire\Component;

class Index extends Component
{
    public $topStudent = [];
    public function mount()
    {
        $this->topStudent = SdfrStudent::query()->where('status','=',true)->get();
    }
    public function render()
    {
        return view('livewire.client.home.top-student.index')->layout('layouts.client.app');
    }
}
