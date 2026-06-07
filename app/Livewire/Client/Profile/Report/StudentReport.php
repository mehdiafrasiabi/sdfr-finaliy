<?php

namespace App\Livewire\Client\Profile\Report;

use App\Models\AdvisingSession;
use App\Models\ProgramPart;
use App\Models\TrialWeek;
use App\Models\TypedExamAttempt;
use App\Models\WeeklyProgram;
use App\Services\AssessmentInterpretationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * کارنامه‌ی نهایی دانش‌آموز — بعد از ساخت برنامه‌ی هفتگی توسط manager.
 *   ۱) پروفایل روان‌شناختی کامل
 *   ۲) برنامه‌ی هفتگی فعلی
 *   ۳) چکیده‌ی فعالیت دوره
 *   ۴) توصیه‌های سبک مطالعه‌ی شخصی‌سازی‌شده
 */
class StudentReport extends Component
{
    public function mount(): void
    {
        $user = Auth::user();
        $trial = $user?->trialWeek;

        if (! $trial || $trial->status !== TrialWeek::STATUS_PROGRAM_BUILT) {
            session()->flash('info', 'کارنامه پس از ساخت برنامه‌ی هفتگی فعال می‌شود.');
            $this->redirect(route('client.profile.dashboard'), navigate: true);
            return;
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $trial = $user->trialWeek;

        $report = app(AssessmentInterpretationService::class)->fullReport($user);

        $studentId = $trial->student_id;

        $program = WeeklyProgram::where('student_id', $studentId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        $weekDays = $program ? $program->getWeekDays() : [];

        $sessionsCount = AdvisingSession::where('student_id', $studentId)->count();

        $examAttempts = TypedExamAttempt::where('student_id', $studentId)
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('livewire.client.profile.report.student-report', [
            'report'        => $report,
            'trial'         => $trial,
            'program'       => $program,
            'weekDays'      => $weekDays,
            'sessionsCount' => $sessionsCount,
            'examAttempts'  => $examAttempts,
        ])->layout('layouts.client.app');
    }
}
