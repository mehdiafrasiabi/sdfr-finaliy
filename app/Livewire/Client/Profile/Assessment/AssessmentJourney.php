<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Services\AssessmentJourneyService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Router component بدون UI — فقط بر اساس وضعیت کاربر redirect می‌کند.
 */
class AssessmentJourney extends Component
{
    public function mount(AssessmentJourneyService $journey): void
    {
        $user = Auth::user();
        $step = $journey->determineNextStep($user);

        match ($step['route']) {
            'welcome' => $this->redirect(
                route('client.profile.assessment.welcome', ['stage' => $step['stage']]),
                navigate: true,
            ),
            'review'  => $this->redirect(
                route('client.profile.assessment.review'),
                navigate: true,
            ),
            'waiting' => $this->redirect(
                route('client.profile.waiting-for-supporter'),
                navigate: true,
            ),
            default   => null,
        };
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.assessment.journey')
            ->layout('layouts.client.app');
    }
}
