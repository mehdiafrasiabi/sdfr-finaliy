<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\AdvisingPreSession;
use App\Models\ClassSchedule;
use App\Models\TrialWeek;
use App\Services\AssessmentInterpretationService;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SessionAnalysis extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek   = null;
    public array       $analysis    = [];
    public bool        $showHoursModal = false;
    public int         $dailyStudyHours = 2;

    /** بعد از ساخت برنامه، اورلی «در حال ساخت» (۴۵ ثانیه) نمایش داده می‌شود. */
    public bool $programJustBuilt = false;

    /** مدت نمایش اورلی ساخت برنامه (ثانیه) */
    public const BUILD_OVERLAY_SECONDS = 45;

    public function mount(TrialWeekService $service): void
    {
        $this->seo()->setTitle('تحلیل وضعیت آزمایشی');

        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek || !$this->trialWeek->canBuildProgram()) {
            if ($this->trialWeek?->status === TrialWeek::STATUS_PROGRAM_BUILT) {
                // اگر برنامه قبلاً ساخته شده، اجازه مشاهده داشته باشد
            } elseif (!$this->trialWeek || $this->trialWeek->step < 3) {
                redirect()->route('client.profile.trial.guide');
                return;
            }
        }

        $this->analysis = $service->getClassificationAnalysis(Auth::id());
    }

    public function openHoursModal(): void
    {
        $this->showHoursModal = true;
    }

    public function closeHoursModal(): void
    {
        $this->showHoursModal = false;
    }

    public function buildProgram(TrialWeekService $service): void
    {
        // جلوگیری از ساخت دوباره (مثلاً دابل‌کلیک سریع)
        if (!$this->trialWeek || $this->trialWeek->status !== TrialWeek::STATUS_PRE_SESSION_DONE) {
            return;
        }

        $this->validate(['dailyStudyHours' => ['required', 'integer', 'min:1', 'max:14']]);

        $service->buildProgram($this->trialWeek, $this->dailyStudyHours);
        $this->trialWeek->refresh();
        $this->showHoursModal = false;

        // اورلی ۴۵ ثانیه‌ای «در حال ساخت برنامه» → سپس «آماده‌ای شروع کنیم؟»
        $this->programJustBuilt = true;
    }

    /**
     * «بریم!» — پایان اورلی ساخت برنامه و ورود به داشبورد.
     */
    public function goToDashboard(): void
    {
        $this->redirect(route('client.profile.dashboard'), navigate: true);
    }

    public function getPreSessionProperty(): ?AdvisingPreSession
    {
        if (!$this->trialWeek?->advising_session_id) {
            return null;
        }
        return AdvisingPreSession::where('advising_session_id', $this->trialWeek->advising_session_id)
            ->first();
    }

    /**
     * خلاصهٔ دقیق پیش‌جلسه برای کارنامهٔ پیش از ساخت برنامه.
     */
    public function getPreSessionSummaryProperty(): array
    {
        $ps = $this->preSession;
        if (!$ps) {
            return ['exams' => 0, 'qas' => 0, 'assignments' => 0, 'requested' => 0, 'misc' => false];
        }

        return [
            'exams'       => $ps->exams()->count(),
            'qas'         => $ps->qas()->count(),
            'assignments' => $ps->assignments()->count(),
            'requested'   => $ps->requestedParts()->count(),
            'misc'        => (bool) $ps->miscellaneous,
        ];
    }

    /**
     * خلاصهٔ آزمون‌های شخصیت‌شناسی (مایندست، MBTI و…) — فقط آزمون‌های فعال.
     */
    public function getPersonalitySummaryProperty(): ?array
    {
        return app(AssessmentInterpretationService::class)->summaryForUser(Auth::user());
    }

    public function getNeedsScheduleProperty(): bool
    {
        return (bool) $this->trialWeek?->needsClassSchedule();
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

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.session-analysis', [
            'preSession'        => $this->preSession,
            'preSessionSummary' => $this->preSessionSummary,
            'personality'       => $this->personalitySummary,
        ])->layout('layouts.client.app');
    }
}
