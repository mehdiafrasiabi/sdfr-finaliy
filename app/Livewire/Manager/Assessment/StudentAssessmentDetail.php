<?php

namespace App\Livewire\Manager\Assessment;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use App\Services\AssessmentInterpretationService;
use Livewire\Component;

class StudentAssessmentDetail extends Component
{
    public User $user;

    public function mount(int $user): void
    {
        $this->user = User::with('personalInformation', 'trialWeek')->findOrFail($user);
    }

    public function render(AssessmentInterpretationService $interpreter): \Illuminate\Contracts\View\View
    {
        $studentAssessments = Assessment::active()->forStudent()->ordered()
            ->with(['questions' => fn ($q) => $q->where('is_active', true)->with('options')->orderBy('order')])
            ->get();

        $studentAttempts = StudentAssessmentAttempt::where('user_id', $this->user->id)
            ->whereIn('assessment_id', $studentAssessments->pluck('id'))
            ->with(['answers.option', 'answers.question'])
            ->get()
            ->keyBy('assessment_id');

        // تفسیر برای هر assessment
        $interpretations = [];
        foreach ($studentAssessments as $a) {
            $att = $studentAttempts->get($a->id);
            $cr = $att?->computed_result;
            $interpretations[$a->id] = match ($a->kind) {
                Assessment::KIND_MBTI   => ['mbti' => $interpreter->interpretMbti($cr)],
                Assessment::KIND_VARK   => ['vark' => $interpreter->interpretVark($cr)],
                Assessment::KIND_CUSTOM => ['custom' => $interpreter->interpretCustom($cr, $a)],
                default                 => [],
            };
        }
        // هشدارهای flag روی پاسخ‌های دانش‌آموز
        $globalFlags = [];
        foreach ($studentAttempts as $att) {
            $flags = $att->computed_result['flags'] ?? [];
            foreach ($flags as $flag => $triggered) {
                if ($triggered) {
                    $globalFlags[$flag] = true;
                }
            }
        }
        $globalFlagBanners = [];
        foreach (array_keys($globalFlags) as $flag) {
            $globalFlagBanners[$flag] = $interpreter->interpretCustom(['flags' => [$flag => true]], null)['flags'][$flag] ?? null;
        }

        return view('livewire.manager.assessment.student-assessment-detail', [
            'studentAssessments'         => $studentAssessments,
            'studentAttempts'            => $studentAttempts,
            'interpretations'            => $interpretations,
            'globalFlagBanners'          => array_filter($globalFlagBanners),
        ])->layout('layouts.manager.app');
    }
}
