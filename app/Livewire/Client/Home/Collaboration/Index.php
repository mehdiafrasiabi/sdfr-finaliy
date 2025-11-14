<?php

namespace App\Livewire\Client\Home\Collaboration;

use App\Models\SdfrSchool;
use Livewire\Component;

class Index extends Component
{

    public $schoolSdfr = [];
    public function mount()
    {
        $this->schoolSdfr = SdfrSchool::query()->where('status','=',true)->get();
    }
    public function render()
    {
        return view('livewire.client.home.collaboration.index');
    }
}
