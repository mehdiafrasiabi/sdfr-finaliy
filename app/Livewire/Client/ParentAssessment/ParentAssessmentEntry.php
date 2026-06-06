<?php

namespace App\Livewire\Client\ParentAssessment;

use App\Models\ParentAssessmentInvitation;
use App\Services\ParentInvitationService;
use Carbon\Carbon;
use Livewire\Component;

/**
 * نقطه‌ی ورود والد بعد از کلیک روی لینک SMS. بعد از validate توکن
 * مستقیما به welcome یا thank-you redirect می‌کند.
 */
class ParentAssessmentEntry extends Component
{
    public string $token = '';
    public bool $expired = false;
    public ?ParentAssessmentInvitation $invitation = null;

    public function mount(string $token, ParentInvitationService $svc): void
    {
        $this->token = $token;

        $inv = ParentAssessmentInvitation::where('token', $token)->first();
        if (! $inv || $inv->isExpired()) {
            $this->expired = true;
            $this->invitation = $inv;
            return;
        }

        if (! $inv->first_accessed_at) {
            $inv->update(['first_accessed_at' => Carbon::now()]);
        }

        if ($inv->isCompleted()) {
            $this->redirect(
                route('client.parent.assessment.thank-you', ['token' => $token]),
                navigate: true,
            );
            return;
        }

        $this->redirect(
            route('client.parent.assessment.welcome', ['token' => $token]),
            navigate: true,
        );
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.parent-assessment.entry')
            ->layout('layouts.client.app-auth');
    }
}
