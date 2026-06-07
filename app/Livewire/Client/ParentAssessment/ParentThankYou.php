<?php

namespace App\Livewire\Client\ParentAssessment;

use App\Models\ParentAssessmentInvitation;
use Livewire\Component;

class ParentThankYou extends Component
{
    public string $token = '';
    public ?ParentAssessmentInvitation $invitation = null;

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->invitation = ParentAssessmentInvitation::where('token', $token)->first();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.parent-assessment.thank-you')
            ->layout('layouts.client.app-auth');
    }
}
