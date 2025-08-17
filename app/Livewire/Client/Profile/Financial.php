<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Financial extends Component
{
    use SEOTools,WithPagination;

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('مالی و پرداخت');
    }


    public function render()
    {
        $payments = Auth::user()->payments()->with('order.orderItems.product')->latest()->paginate(10);

        return view('livewire.client.profile.financial',['payments'=>$payments])->layout('layouts.client.app');
    }
}
