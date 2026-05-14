<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\TrialWeek;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class WaitingForSupporter extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    public function mount(): void
    {
        $this->seo()->setTitle('در انتظار پشتیبان');
        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek) {
            redirect()->route('client.profile.dashboard');
            return;
        }

        // If supporter already assigned, redirect to trial guide
        if ($this->trialWeek->status !== TrialWeek::STATUS_PENDING) {
            redirect()->route('client.profile.trial.guide');
        }
    }

    // Polling: called every 30 seconds via wire:poll
    public function checkStatus(): void
    {
        if (!$this->trialWeek) {
            return;
        }

        $this->trialWeek->refresh();

        if ($this->trialWeek->status !== TrialWeek::STATUS_PENDING) {
            redirect()->route('client.profile.trial.guide');
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.waiting-for-supporter')
            ->layout('layouts.client.app');
    }
}
