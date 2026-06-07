<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\ClassificationProject;
use App\Models\ClassSchedule;
use App\Models\StudentClassification;
use App\Models\StudentClassificationSubmission;
use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\TrialWeek;
use App\Services\AssessmentInterpretationService;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Guide extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    public bool $showLockConfirm = false;
    public bool $showHoursModal = false;
    public int $dailyStudyHours = 2;
    public bool $isLocking = false;

    public function mount(): void
    {
        $this->seo()->setTitle('هفته آزمایشی');
        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek) {
            redirect()->route('client.profile.dashboard');
        }
    }

    // بررسی تکمیل شدن طبقه‌بندی
    public function getClassificationDoneProperty(): bool
    {
        if (!$this->trialWeek) {
            return false;
        }
        $project = $this->activeProject; // اینجا تغییر بده
        if (!$project) {
            return false;
        }
        return StudentClassificationSubmission::where('user_id', Auth::id())
            ->where('classification_project_id', $project->id)
            ->exists();
    }

    public function getClassScheduleFinalizedProperty(): bool
    {
        if (!$this->trialWeek) {
            return false;
        }
        return ClassSchedule::where('student_id', $this->trialWeek->student_id)
            ->where('is_finalized', true)
            ->exists();
    }

    public function getPreSessionCompletedProperty(): bool
    {
        return $this->trialWeek?->advisingSession?->preSession?->status === 'completed';
    }

    /**
     * خلاصهٔ وضعیت شخصیتی دانش‌آموز بر اساس آزمون‌های تکمیل‌شده.
     * خروجی شامل تیپ MBTI، پروفایل VARK و نقاط قوت/ضعف تست‌های اختصاصی + پرچم‌ها.
     */
    public function getPersonalitySummaryProperty(): ?array
    {
        $interpreter = app(AssessmentInterpretationService::class);

        $attempts = StudentAssessmentAttempt::where('user_id', Auth::id())
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->with('assessment')
            ->get();

        if ($attempts->isEmpty()) {
            return null;
        }

        $summary = ['mbti' => null, 'vark' => null, 'custom' => [], 'flags' => []];

        foreach ($attempts as $attempt) {
            $kind = $attempt->assessment?->kind;
            $cr   = $attempt->computed_result;

            if ($kind === Assessment::KIND_MBTI) {
                $summary['mbti'] = $interpreter->interpretMbti($cr);
            } elseif ($kind === Assessment::KIND_VARK) {
                $summary['vark'] = $interpreter->interpretVark($cr);
            } else {
                $custom = $interpreter->interpretCustom($cr, $attempt->assessment);
                if (!empty($custom['facets'])) {
                    $summary['custom'][$attempt->assessment->name_fa] = $custom['facets'];
                }
                foreach ($custom['flags'] ?? [] as $flag) {
                    $summary['flags'][] = $flag;
                }
            }
        }

        return $summary;
    }

    public function getActiveProjectProperty(): ?ClassificationProject
    {
        // For trial users, always prefer the trial classification project.
        return ClassificationProject::where('is_trial', true)->where('is_active', true)->first()
            ?? ClassificationProject::where('is_active', true)->where('is_trial', false)->first();
    }

    // قفل طبقه‌بندی + تایید
    public function openLockConfirm(): void
    {
        if (!$this->classificationDone) {
            $this->dispatch('warning', 'ابتدا باید طبقه‌بندی را تکمیل کنید.');
            return;
        }
        $this->showLockConfirm = true;
    }

    public function closeLockConfirm(): void
    {
        $this->showLockConfirm = false;
    }

    public function lockClassification(TrialWeekService $service): void
    {
        $this->isLocking = true;

        if (!$this->classificationDone) {
            $this->addError('lock', 'ابتدا باید طبقه‌بندی را کامل کنید.');
            $this->isLocking = false;
            return;
        }

        $service->lockClassification($this->trialWeek);
        $this->trialWeek->refresh();
        $this->showLockConfirm = false;
        $this->isLocking = false;
    }

    // ساخت برنامه
    public function openHoursModal(): void
    {
        if ($this->trialWeek->status !== TrialWeek::STATUS_PRE_SESSION_DONE) {
            return;
        }
        $this->showHoursModal = true;
    }

    public function closeHoursModal(): void
    {
        $this->showHoursModal = false;
    }

    public function buildProgram(TrialWeekService $service): void
    {
        $this->validate([
            'dailyStudyHours' => ['required', 'integer', 'min:1', 'max:12'],
        ], [
            'dailyStudyHours.min' => 'حداقل ۱ ساعت مطالعه روزانه انتخاب کنید.',
            'dailyStudyHours.max' => 'حداکثر ۱۲ ساعت مطالعه روزانه مجاز است.',
        ]);

        $service->buildProgram($this->trialWeek, $this->dailyStudyHours);
        $this->trialWeek->refresh();
        $this->showHoursModal = false;

        $this->dispatch('success', 'برنامه مطالعاتی شما با موفقیت ساخته شد!');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.guide', [
            'activeProject' => $this->activeProject,
        ])->layout('layouts.client.app');
    }
}
