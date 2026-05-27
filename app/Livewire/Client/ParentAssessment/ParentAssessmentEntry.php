<?php

namespace App\Livewire\Client\ParentAssessment;

use App\Models\ParentAssessmentInvitation;
use App\Services\ParentInvitationService;
use Carbon\Carbon;
use Livewire\Component;

class ParentAssessmentEntry extends Component
{
    public string $token = '';
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    public function mount(string $token, ParentInvitationService $svc): void
    {
        $this->token = $token;

        $inv = ParentAssessmentInvitation::where('token', $token)->first();
        if (! $inv) {
            $this->expired = true;
            return;
        }
        if ($inv->isExpired()) {
            $this->expired = true;
            $this->invitation = $inv;
            return;
        }

        $this->invitation = $inv;
        if (! $inv->first_accessed_at) {
            $inv->update(['first_accessed_at' => Carbon::now()]);
        }
    }

    public function proceed(): void
    {
        if (! $this->invitation || $this->expired) {
            return;
        }
        $this->redirect(
            route('client.parent.assessment.list', ['token' => $this->token]),
            navigate: true,
        );
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.parent-assessment.entry')
            ->layout('layouts.client.app-auth');
    }
}
