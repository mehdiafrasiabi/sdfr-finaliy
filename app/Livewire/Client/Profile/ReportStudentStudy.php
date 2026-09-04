<?php

namespace App\Livewire\Client\Profile;

use App\Models\ReportMonthly;
use App\Models\SmartReportCard;
use App\Services\ExamPlanningService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportStudentStudy extends Component
{
    use WithPagination, SEOTools;

    public function mount()
    {
        $this->seo()
            ->setTitle('کارنامه وضعیت ماهانه من')
            ->setDescription('کارنامه وضعیت ماهانه من');
    }

    public function render()
    {
        $user = Auth::user();
        $studentId = $user->student->id ?? null;
        $examPlanning = app(ExamPlanningService::class);
        $hideForExamProgramTrialStudent = $examPlanning
            ->shouldHideTrialExamProgramReportCard($user);
        $isExamProgramReport = $examPlanning
            ->trialExamProgramReportCardUnlockedForUser($user);

        if ($hideForExamProgramTrialStudent) {
            return view('livewire.client.profile.report-student-study', [
                'reportMonthly'  => collect(),
                'smartCards'     => collect(),
                'isTrial'        => false,
                'isExamProgramReport' => false,
                'reportUnlocked' => true,
                'trialDay'       => null,
                'hideForExamProgramTrialStudent' => true,
            ])->layout('layouts.client.app');
        }

        // ── حالتِ یک هفته آزمایشی: قفل اصلی یک روز قبل پایان دسترسی در سرویس اعمال می‌شود. ──
        $trial = $user->trialWeek;
        $isTrial = $trial && ! $user->isSchoolStudent()
            && ! ($user->student && $user->student->hasActivePaidAccess())
            && ! $isExamProgramReport;

        $reportUnlocked = true;
        $trialDay = null;

        $reportMonthly = ReportMonthly::query()
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(12);

        $smartCards = SmartReportCard::query()
            ->where('student_id', $studentId)
            ->where('is_active', true)
            ->orderByDesc('jalali_year')
            ->orderByDesc('jalali_month')
            ->get();

        return view('livewire.client.profile.report-student-study', [
            'reportMonthly'  => $reportMonthly,
            'smartCards'     => $smartCards,
            'isTrial'        => $isTrial,
            'isExamProgramReport' => $isExamProgramReport,
            'reportUnlocked' => $reportUnlocked,
            'trialDay'       => $trialDay,
            'hideForExamProgramTrialStudent' => false,
        ])->layout('layouts.client.app');
    }
}
