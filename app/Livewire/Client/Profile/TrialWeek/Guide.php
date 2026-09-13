<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\ClassificationProject;
use App\Models\ClassSchedule;
use App\Models\StudentClassificationSubmission;
use App\Models\TrialWeek;
use App\Services\AssessmentInterpretationService;
use App\Services\ExamPlanningService;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Guide extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    public int $dailyStudyHours = 2;
    public bool $isLocking = false;
    public bool $examPlanningMode = false;

    /** id مودال‌ها — برای x-ui.modal (dispatch('open-modal'/'close-modal', ...)) */
    public const LOCK_CONFIRM_MODAL_ID = 'trial-lock-confirm';
    public const HOURS_MODAL_ID        = 'trial-hours-modal-guide';

    public function mount(): void
    {
        $this->seo()->setTitle('هفته آزمایشی');
        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (Auth::user()?->student && ! Auth::user()->student->is_trial) {
            redirect()->route('client.profile.dashboard');
            return;
        }
        $this->examPlanningMode = app(ExamPlanningService::class)->shouldExposeTrialModule(Auth::user());

        if (!$this->trialWeek) {
            redirect()->route('client.profile.dashboard');
        }
    }

    public function goToExamPlanning()
    {
        return redirect()->route('client.profile.exam-planning');
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
     * فقط آزمون‌های «فعال» لحاظ می‌شوند — آزمون غیرفعال‌شده توسط مدیر نمایش داده نمی‌شود.
     */
    public function getPersonalitySummaryProperty(): ?array
    {
        return app(AssessmentInterpretationService::class)->summaryForUser(Auth::user());
    }

    /**
     * آیا «برنامه کلاسی مدرسه» برای این دانش‌آموز لازم است؟
     * فارغ‌التحصیل‌ها و کسانی که مدرسه نمی‌روند معاف‌اند.
     */
    public function getNeedsScheduleProperty(): bool
    {
        return (bool) $this->trialWeek?->needsClassSchedule();
    }

    /**
     * اطلاعات «مشاور جذب» برای نمایش در باکس مرحلهٔ ۱.
     */
    public function getConsultantProperty(): ?array
    {
        $consultant = $this->trialWeek?->acquisitionSupporter;
        if (!$consultant) {
            return null;
        }

        return [
            'name'   => $consultant->name,
            'mobile' => $consultant->mobile,
            'avatar' => $consultant->picture
                ? asset('adminsFile/' . $consultant->id . '/' . $consultant->picture)
                : null,
        ];
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
        $this->dispatch('open-modal', self::LOCK_CONFIRM_MODAL_ID);
    }

    public function closeLockConfirm(): void
    {
        $this->dispatch('close-modal', self::LOCK_CONFIRM_MODAL_ID);
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
        $this->dispatch('close-modal', self::LOCK_CONFIRM_MODAL_ID);
        $this->isLocking = false;
    }

    // ساخت برنامه
    public function openHoursModal(): void
    {
        if ($this->trialWeek->status !== TrialWeek::STATUS_PRE_SESSION_DONE) {
            return;
        }
        $this->dispatch('open-modal', self::HOURS_MODAL_ID);
    }

    public function closeHoursModal(): void
    {
        $this->dispatch('close-modal', self::HOURS_MODAL_ID);
    }

    public function buildProgram(TrialWeekService $service): void
    {
        $this->validate([
            'dailyStudyHours' => ['required', 'integer', 'min:2', 'max:12'],
        ], [
            'dailyStudyHours.min' => 'حداقل ۲ ساعت مطالعه روزانه انتخاب کنید.',
            'dailyStudyHours.max' => 'حداکثر ۱۲ ساعت مطالعه روزانه مجاز است.',
        ]);

        $service->buildProgram($this->trialWeek, $this->dailyStudyHours);
        $this->trialWeek->refresh();
        $this->dispatch('close-modal', self::HOURS_MODAL_ID);

        // (F/G) بعد از ساخت برنامه مستقیم به داشبورد می‌رویم و تورِ راهنمای داشبورد را فعال می‌کنیم.
        session()->put('start_dashboard_tour', true);
        $this->redirect(route('client.profile.dashboard'), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        // (HOTFIX) ناهماهنگی وضعیت را بررسی و به طور خودکار اصلاح می‌کند.
        // این حالت ممکن است زمانی رخ دهد که رویداد تکمیل PreSession به درستی اجرا نشود.
        if ($this->trialWeek->status === TrialWeek::STATUS_CLASSIFICATION_DONE) {
            $preSessionIsDone = $this->preSessionCompleted;
            $scheduleIsDone   = !$this->needsSchedule || $this->classScheduleFinalized;

            if ($preSessionIsDone && $scheduleIsDone) {
                app(TrialWeekService::class)->completePreSession($this->trialWeek);
                $this->trialWeek->refresh();
            }
        }

        return view('livewire.client.profile.trial-week.guide', [
            'activeProject'    => $this->activeProject,
            'examPlanningMode' => $this->examPlanningMode,
        ])->layout('layouts.client.app');
    }
}
