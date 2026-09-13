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
    public int         $dailyStudyHours = 2;

    /** id مودالِ انتخاب ساعت مطالعه — برای x-ui.modal (dispatch('open-modal'/'close-modal', ...)) */
    public const HOURS_MODAL_ID = 'trial-hours-modal';

    /** بعد از ساخت برنامه، اورلی «در حال ساخت» (۴۵ ثانیه) نمایش داده می‌شود. */
    public bool $programJustBuilt = false;

    /** مدت نمایش اورلی ساخت برنامه (ثانیه) */
    public const BUILD_OVERLAY_SECONDS = 45;

    public function mount(TrialWeekService $service): void
    {
        $this->seo()->setTitle('تحلیل وضعیت آزمایشی');

        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (Auth::user()?->student && ! Auth::user()->student->is_trial) {
            redirect()->route('client.profile.dashboard');
            return;
        }

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
        $this->dispatch('open-modal', self::HOURS_MODAL_ID);
    }

    public function closeHoursModal(): void
    {
        $this->dispatch('close-modal', self::HOURS_MODAL_ID);
    }

    public function buildProgram(TrialWeekService $service): void
    {
        // ۱. جلوگیری از ساخت دوباره (مثلاً دابل‌کلیک سریع)
        if (!$this->trialWeek || $this->trialWeek->status !== TrialWeek::STATUS_PRE_SESSION_DONE) {
            return;
        }

        // ۲. اعتبار سنجی میزان ساعت انتخاب شده
        $this->validate(['dailyStudyHours' => ['required', 'integer', 'min:1', 'max:14']]);

        // ۳. اجرای منطق ساخت برنامه در دیتابیس
        $service->buildProgram($this->trialWeek, $this->dailyStudyHours);
        $this->trialWeek->refresh();

        // ۴. بستن مودال انتخاب ساعت
        $this->dispatch('close-modal', self::HOURS_MODAL_ID);

        // ۵. [اصلی] هدایت آنی و مستقیم کاربر به داشبورد (بدون فعال کردن وضعیت اورلی ساخت برنامه)
        $this->redirect(route('client.profile.dashboard'), navigate: true);
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
            ->withCount(['exams', 'qas', 'assignments', 'requestedParts'])
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
            'exams'       => (int) $ps->exams_count,
            'qas'         => (int) $ps->qas_count,
            'assignments' => (int) $ps->assignments_count,
            'requested'   => (int) $ps->requested_parts_count,
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
