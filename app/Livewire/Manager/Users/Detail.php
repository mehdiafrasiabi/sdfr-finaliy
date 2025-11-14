<?php

namespace App\Livewire\Manager\Users;

use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Detail extends Component
{
    use SEOTools;
    public User $user;


    public function mount($id)
    {
        $this->user = User::query()->findOrFail($id);
        $this->seo()->setTitle($this->user->name);
    }
    public function render()
    {
        return view('livewire.manager.users.detail')->layout('layouts.manager.app');
    }
}
