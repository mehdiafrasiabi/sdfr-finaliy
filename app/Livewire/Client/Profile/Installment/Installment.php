<?php

namespace App\Livewire\Client\Profile\Installment;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Installment extends Component
{
    use SEOTools;

    public function mount()
    {
        $this->seo()
            ->setTitle('اقساط(بزودی)')
            ->setDescription('اقساط(بزودی)');
    }

    public function render()
    {
        return view('livewire.client.profile.installment.installment')->layout('layouts.client.app');
    }
}
