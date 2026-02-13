<?php

namespace App\Livewire\Client\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateNotification extends Component
{
    public int $userId;

    public function mount()
    {
        $this->userId = Auth::id();
    }

    public function render()
    {
        return view('livewire.client.profile.update-notification')->layout('layouts.client.app');
    }
}
