<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssessmentList extends Component
{
    public function start(string $slug, AssessmentService $service): void
    {
        $user = Auth::user();
        $assessment = Assessment::active()->where('slug', $slug)->firstOrFail();

        $attempt = $service->startOrResume($user, $assessment);

        if ($attempt->isCompleted()) {
            session()->flash('info', 'این آزمون قبلاً تکمیل شده است.');
            return;
        }

        $this->redirect(route('client.profile.assessment.take', ['slug' => $slug]), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $userId = Auth::id();

        $assessments = Assessment::active()
            ->forStudent()
            ->ordered()
            ->withCount(['questions' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $attempts = StudentAssessmentAttempt::where('user_id', $userId)
            ->whereIn('assessment_id', $assessments->pluck('id'))
            ->get()
            ->keyBy('assessment_id');

        $items = $assessments->map(function ($a) use ($attempts) {
            $attempt = $attempts->get($a->id);
            $total = $a->questions_count;
            $answered = $attempt?->answered_count ?? 0;
            $status = match (true) {
                $attempt && $attempt->status === StudentAssessmentAttempt::STATUS_COMPLETED => 'completed',
                $attempt && $attempt->status === StudentAssessmentAttempt::STATUS_IN_PROGRESS => 'in_progress',
                default => 'not_started',
            };
            return (object) [
                'assessment' => $a,
                'attempt'    => $attempt,
                'total'      => $total,
                'answered'   => $answered,
                'status'     => $status,
            ];
        });

        $totalCount = $items->count();
        $completedCount = $items->where('status', 'completed')->count();

        return view('livewire.client.profile.assessment.assessment-list', [
            'items'          => $items,
            'totalCount'     => $totalCount,
            'completedCount' => $completedCount,
        ])->layout('layouts.client.app');
    }
}
