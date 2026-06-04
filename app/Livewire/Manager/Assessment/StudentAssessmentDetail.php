<?php

namespace App\Livewire\Manager\Assessment;

use App\Models\Assessment;
use App\Models\ParentAssessmentAttempt;
use App\Models\ParentAssessmentInvitation;
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

        $parentAssessments = Assessment::active()
            ->where('audience', Assessment::AUDIENCE_PARENT)
            ->ordered()
            ->with(['questions' => fn ($q) => $q->where('is_active', true)->with('options')->orderBy('order')])
            ->get();

        $studentAttempts = StudentAssessmentAttempt::where('user_id', $this->user->id)
            ->whereIn('assessment_id', $studentAssessments->pluck('id'))
            ->with(['answers.option', 'answers.question'])
            ->get()
            ->keyBy('assessment_id');

        $invitations = ParentAssessmentInvitation::where('user_id', $this->user->id)
            ->with(['attempts.answers.option', 'attempts.answers.question', 'attempts.assessment'])
            ->get()
            ->keyBy('parent_role');

        // attempts والد به شکل: [assessment_id => [parent_role => attempt]]
        $parentAttemptsByAssessment = [];
        foreach ($invitations as $role => $inv) {
            foreach ($inv->attempts as $att) {
                $parentAttemptsByAssessment[$att->assessment_id][$role] = $att;
            }
        }

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
        foreach ($parentAssessments as $a) {
            foreach (($parentAttemptsByAssessment[$a->id] ?? []) as $role => $att) {
                $interpretations['parent'][$a->id][$role] = $interpreter->interpretCustom($att->computed_result, $a);
            }
        }

        // مپ کردن تست‌های parallel: parent slug ⇄ student slug
        $parallelMap = [
            'fear-of-parents-parent'   => 'fear-of-parents',
            'friends-influence-parent' => 'friends-influence',
        ];

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
            'parentAssessments'          => $parentAssessments,
            'studentAttempts'            => $studentAttempts,
            'invitations'                => $invitations,
            'parentAttemptsByAssessment' => $parentAttemptsByAssessment,
            'interpretations'            => $interpretations,
            'parallelMap'                => $parallelMap,
            'globalFlagBanners'          => array_filter($globalFlagBanners),
        ])->layout('layouts.manager.app');
    }
}
