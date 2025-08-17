<?php

namespace App\Livewire\Client\Layout;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Header extends Component
{
    public $cart=0;

    public function mount()
    {
        $this->cart = Cart::query()
            ->where('user_id', Auth()->id())->count();

    }

    #[On('add-to-cart')]
    public function getUserCart()
    {
        $this->cart = $this->cart + 1;
    }

    #[On('remove-from-cart')]
    public function removeUserCart($newCount)
    {
        $this->cart = $newCount;
    }
    public function getProfilePictureUrlAttribute()
    {
        $path = public_path("user/img/{$this->id}/{$this->picture}");
        if ($this->picture && file_exists($path)) {
            return asset("user/img/{$this->id}/{$this->picture}");
        }
        return asset('client/assets/images/avatars/01.jpeg');
    }
    public function render()
    {
        return view('livewire.client.layout.header')->layout('layouts.client.app');
    }
}
