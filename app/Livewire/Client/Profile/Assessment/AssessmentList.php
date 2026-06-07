<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Models\StudentAssessmentAttempt;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * صفحهٔ ورود مرحله‌ای آزمون‌ها (به‌جای صفحهٔ باکسی قبلی):
 *   - اگر آزمونی ناتمام مانده: صفحهٔ «خوش آمدی، ادامه دهیم» و رفتن مستقیم به سوال بعدی.
 *   - اگر همه تکمیل شده: صفحهٔ تشکر و ادامه به راهنمای هفتهٔ آزمایشی.
 */
class AssessmentList extends Component
{
    /**
     * شروع/ادامهٔ آزمون جاری — کاربر را مستقیم به اولین سوال بی‌پاسخ می‌برد.
     */
    public function start(AssessmentService $service): void
    {
        $next = $service->nextStudentAssessment(Auth::user());

        if (!$next) {
            // همه تکمیل شده — به راهنما هدایت می‌کنیم.
            $this->redirect(route('client.profile.trial.guide'), navigate: true);
            return;
        }

        // مطمئن می‌شویم attempt وجود دارد، سپس به صفحهٔ پاسخ‌دهی می‌رویم.
        $service->startOrResume(Auth::user(), $next);
        $this->redirect(route('client.profile.assessment.take', ['slug' => $next->slug]), navigate: true);
    }

    /**
     * ادامه پس از تشکر — ورود به راهنمای هفتهٔ آزمایشی.
     */
    public function continueToGuide(): void
    {
        $this->redirect(route('client.profile.trial.guide'), navigate: true);
    }

    public function render(AssessmentService $service): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        $stageAssessments = $service->studentAssessmentsInStageOrder();

        // پیشرفت کلی: مجموع پاسخ‌ها و کل سوالات فعال در همهٔ آزمون‌ها.
        $totalQuestions = 0;
        $answeredTotal  = 0;

        $attempts = StudentAssessmentAttempt::where('user_id', $user->id)
            ->whereIn('assessment_id', $stageAssessments->pluck('id'))
            ->get()
            ->keyBy('assessment_id');

        foreach ($stageAssessments as $a) {
            $totalQuestions += $a->questions()->where('is_active', true)->count();
            $answeredTotal  += $attempts->get($a->id)?->answered_count ?? 0;
        }

        $next = $service->nextStudentAssessment($user);
        $isAllDone = $next === null;

        // آیا قبلاً آزمونی شروع شده تا متن «خوش آمدی، ادامه دهیم» نمایش داده شود؟
        $hasStarted = $attempts->isNotEmpty();

        $currentStage = $next ? $service->stageNumberFor($next) : 2;

        return view('livewire.client.profile.assessment.assessment-list', [
            'next'           => $next,
            'isAllDone'      => $isAllDone,
            'hasStarted'     => $hasStarted,
            'currentStage'   => $currentStage,
            'totalQuestions' => $totalQuestions,
            'answeredTotal'  => $answeredTotal,
        ])->layout('layouts.client.app');
    }
}
