<?php

namespace App\Livewire\Client\Profile\Installment;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class InstallmentDetail extends Component
{
    use SEOTools;
    public function mount()
    {
        // جزئیات اقساط در همان صفحهٔ اصلی اقساط نمایش داده می‌شود.
        return $this->redirect(route('client.profile.installment'), navigate: true);
    }
    public function render()
    {
        return view('livewire.client.profile.installment.installment-detail')->layout('layouts.client.app');
    }
}
