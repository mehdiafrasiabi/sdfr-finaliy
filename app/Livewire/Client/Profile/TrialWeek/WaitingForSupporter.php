<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class WaitingForSupporter extends Component
{
    public $trial;

    public function mount(): void
    {
        $user = Auth::user();
        $this->trial = $user?->trialWeek;

        // اگر پشتیبان قبلاً اختصاص داده شده، مستقیم به داشبورد برو
        if ($this->trial && $this->trial->supporter_id) {
            $this->redirect(route('client.profile.dashboard'));
        }
    }

    #[Layout('layouts.client.app-auth')]
    public function render()
    {
        return view('livewire.client.profile.trial-week.waiting-for-supporter');
    }
}
