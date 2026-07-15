<?php

namespace App\Livewire\Client\Profile\Assessment;

use App\Models\StudentAssessmentAttempt;
use App\Models\TrialWeek;
use App\Services\AssessmentInterpretationService;
use App\Services\AssessmentService;
use App\Services\ExamPlanningService;
use App\Services\TrialWeekService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * صفحهٔ ورود مرحله‌ای آزمون‌ها:
 *   - اگر آزمونی ناتمام مانده: صفحهٔ «خوش آمدی، ادامه دهیم» و رفتن مستقیم به سوال بعدی.
 *   - اگر همه تکمیل شده: کارنامهٔ تحلیلی وضعیت (فقط آزمون‌های فعال) و سپس
 *     انتخاب مسیر: «شروع هفتهٔ آزمایشی» یا «خرید دوره».
 */
class AssessmentList extends Component
{
    /** بعد از خواندن کارنامهٔ تحلیلی، باکس‌های انتخاب مسیر نمایش داده می‌شوند. */
    public bool $showChoice = false;

    public bool $isStartingTrial = false;

    public array $trialChoiceCopy = [];

    /**
     * شروع/ادامهٔ آزمون جاری — کاربر را مستقیم به اولین سوال بی‌پاسخ می‌برد.
     */
    public function start(AssessmentService $service): void
    {
        $next = $service->nextStudentAssessment(Auth::user());

        if (!$next) {
            // همه تکمیل شده — همین صفحه کارنامهٔ تحلیلی را نشان می‌دهد.
            return;
        }

        // مطمئن می‌شویم attempt وجود دارد، سپس به صفحهٔ پاسخ‌دهی می‌رویم.
        $service->startOrResume(Auth::user(), $next);
        $this->redirect(route('client.profile.assessment.take', ['slug' => $next->slug]), navigate: true);
    }

    /**
     * «ادامه» بعد از خواندن کارنامهٔ تحلیلی — نمایش انتخاب مسیر.
     */
    public function continueToChoice(TrialWeekService $service): void
    {
        $user = Auth::user();

        // کاربری که از قبل هفتهٔ آزمایشی دارد، انتخاب مسیر ندارد و ادامه می‌دهد.
        if ($user->trialWeek) {
            $this->redirect(route('client.profile.trial.guide'), navigate: true);
            return;
        }

        // دانش‌آموز مدرسه یا کسی که دسترسی پرداختیِ فعال دارد، نیازی به انتخاب
        // آزمایشی/خرید ندارد و مستقیم به داشبورد می‌رود.
        if ($user->isSchoolStudent() || ($user->student && $user->student->hasActivePaidAccess())) {
            $this->redirect(route('client.profile.dashboard'), navigate: true);
            return;
        }

        // (C8) اگر کاربر از صفحهٔ اصلی با دکمهٔ مشخص (آزمایشی/نقدی) آمده باشد،
        // صفحهٔ انتخابِ مسیر نمایش داده نمی‌شود و همان مسیر مستقیم دنبال می‌شود.
        $intended = session('intended_plan');
        if ($intended === 'exam') {
            session()->forget('intended_plan');
            $this->confirmTrial($service);
            return;
        }
        if ($intended === 'trial') {
            session()->forget('intended_plan');
            $this->confirmTrial($service);
            return;
        }
        if ($intended === 'cash') {
            session()->forget('intended_plan');
            $this->goToPurchase();
            return;
        }

        $this->showChoice = true;
    }

    /**
     * شروع هفتهٔ آزمایشی — اطلاعات پایه/رشته از ثبت‌نام خوانده می‌شود
     * و کاربر به صفحهٔ تخصیص «مشاور جذب» می‌رود.
     */
    public function confirmTrial(TrialWeekService $service): void
    {
        if ($this->isStartingTrial) {
            return;
        }
        $this->isStartingTrial = true;

        $user = Auth::user();

        if ($user->trialWeek) {
            $this->redirect(route('client.profile.trial.guide'), navigate: true);
            return;
        }

        $info = $user->personalInformation;
        if (!$info) {
            $this->isStartingTrial = false;
            session()->flash('error', 'اطلاعات ثبت‌نام شما کامل نیست. لطفاً با پشتیبانی تماس بگیرید.');
            return;
        }

        $grade = $info->is_graduate ? TrialWeek::GRADE_GRADUATE : (int) $info->grade;

        $service->start(
            $user,
            $grade,
            $info->field,
            $info->father_mobile,
            $info->mother_mobile,
            (bool) $info->attends_school,
        );

        $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
    }

    public function goToPurchase(): void
    {
        $this->redirect(route('client.purchase'), navigate: true);
    }

    public function render(AssessmentService $service, AssessmentInterpretationService $interpreter): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $this->trialChoiceCopy = $this->resolveTrialChoiceCopy($user);

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

        // کارنامهٔ تحلیلی فقط از آزمون‌های «فعال» — آزمون غیرفعال‌شده نمایش داده نمی‌شود.
        $summary = $isAllDone ? $interpreter->summaryForUser($user) : null;

        return view('livewire.client.profile.assessment.assessment-list', [
            'next'           => $next,
            'isAllDone'      => $isAllDone,
            'hasStarted'     => $hasStarted,
            'currentStage'   => $currentStage,
            'totalQuestions' => $totalQuestions,
            'answeredTotal'  => $answeredTotal,
            'summary'        => $summary,
            'hasTrial'       => (bool) $user->trialWeek,
            'trialChoiceCopy'=> $this->trialChoiceCopy,
        ])->layout('layouts.client.app');
    }

    private function resolveTrialChoiceCopy($user): array
    {
        $default = [
            'plan' => 'trial',
            'title' => 'شروع ۱ هفته آزمایشی',
            'description' => 'تجربه‌ی کامل امکانات بدون پرداخت، با نظارت مشاور اختصاصی',
            'cta' => 'ادامه به هفته‌ی آزمایشی',
        ];

        $info = $user?->personalInformation;
        if (! $info || $info->grade === null) {
            return $default;
        }

        $grade = $info->is_graduate ? TrialWeek::GRADE_GRADUATE : (int) $info->grade;
        $field = (int) $grade === 9 ? null : $info->field;
        $setting = app(ExamPlanningService::class)->resolveActiveSettingForGradeField($grade, $field);

        if (! $setting) {
            return $default;
        }

        $termTitle = $setting->term_type_label === 'امتحانات'
            ? 'امتحانات'
            : 'امتحانات ' . $setting->term_type_label;

        return [
            'plan' => 'exam',
            'title' => 'شروع برنامه ' . $termTitle,
            'description' => 'مسیر رایگان امتحانی؛ ساخت برنامه مخصوص امتحانات و ثبت ساعت مطالعه تا پایان بازه امتحانات',
            'cta' => 'ادامه به برنامه امتحانی',
        ];
    }
}
