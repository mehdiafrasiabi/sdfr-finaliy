<?php

namespace App\Livewire\Client\ParentAssessment;

use App\Models\Assessment;
use App\Models\ParentAssessmentAttempt;
use App\Models\ParentAssessmentInvitation;
use App\Services\AssessmentService;
use App\Services\ParentInvitationService;
use Livewire\Component;

class ParentAssessmentList extends Component
{
    public string $token = '';
    public ?ParentAssessmentInvitation $invitation = null;
    public bool $expired = false;

    public function mount(string $token, ParentInvitationService $svc): void
    {
        $this->token = $token;
        $inv = $svc->verifyToken($token);
        if (! $inv) {
            $this->expired = true;
            return;
        }
        $this->invitation = $inv;
    }

    public function start(string $slug, AssessmentService $assessmentService): void
    {
        if (! $this->invitation) {
            return;
        }
        $assessment = Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->where('slug', $slug)
            ->firstOrFail();

        $attempt = $assessmentService->startOrResumeParent($this->invitation, $assessment);
        if ($attempt->isCompleted()) {
            session()->flash('info', 'این آزمون قبلاً تکمیل شده است.');
            return;
        }

        $this->redirect(
            route('client.parent.assessment.take', ['token' => $this->token, 'slug' => $slug]),
            navigate: true,
        );
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $items = collect();
        $completedCount = 0;
        $totalCount = 0;

        if ($this->invitation && ! $this->expired) {
            $assessments = Assessment::active()
                ->where('audience', Assessment::AUDIENCE_PARENT)
                ->ordered()
                ->withCount(['questions' => fn ($q) => $q->where('is_active', true)])
                ->get();

            $attempts = ParentAssessmentAttempt::where('invitation_id', $this->invitation->id)
                ->whereIn('assessment_id', $assessments->pluck('id'))
                ->get()
                ->keyBy('assessment_id');

            $items = $assessments->map(function ($a) use ($attempts) {
                $attempt = $attempts->get($a->id);
                $status = match (true) {
                    $attempt && $attempt->status === ParentAssessmentAttempt::STATUS_COMPLETED => 'completed',
                    $attempt && $attempt->status === ParentAssessmentAttempt::STATUS_IN_PROGRESS => 'in_progress',
                    default => 'not_started',
                };
                return (object) [
                    'assessment' => $a,
                    'attempt'    => $attempt,
                    'total'      => $a->questions_count,
                    'answered'   => $attempt?->answered_count ?? 0,
                    'status'     => $status,
                ];
            });

            $totalCount = $items->count();
            $completedCount = $items->where('status', 'completed')->count();
        }

        return view('livewire.client.parent-assessment.list', [
            'items'          => $items,
            'totalCount'     => $totalCount,
            'completedCount' => $completedCount,
        ])->layout('layouts.client.app-auth');
    }
}
