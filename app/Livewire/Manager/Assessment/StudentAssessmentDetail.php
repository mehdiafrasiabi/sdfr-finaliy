<?php

namespace App\Livewire\Manager\Assessment;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use Livewire\Component;

class StudentAssessmentDetail extends Component
{
    public User $user;

    public function mount(int $user): void
    {
        $this->user = User::with('personalInformation', 'trialWeek')->findOrFail($user);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $assessments = Assessment::active()->forStudent()->ordered()
            ->with(['questions' => fn ($q) => $q->where('is_active', true)->with('options')->orderBy('order')])
            ->get();

        $attempts = StudentAssessmentAttempt::where('user_id', $this->user->id)
            ->whereIn('assessment_id', $assessments->pluck('id'))
            ->with(['answers.option', 'answers.question'])
            ->get()
            ->keyBy('assessment_id');

        return view('livewire.manager.assessment.student-assessment-detail', [
            'assessments' => $assessments,
            'attempts'    => $attempts,
        ])->layout('layouts.manager.app');
    }
}
