<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use Illuminate\Database\Eloquent\Builder;

class ExamMonitor extends Monitor
{
    protected function applyStudentTypeScope(Builder $query): Builder
    {
        return $query->whereHas('student.examSchedules');
    }

    protected function weeklyProgramForTrial(?TrialWeek $trialWeek): ?WeeklyProgram
    {
        return $this->currentExamSchedule($trialWeek)?->weeklyProgram;
    }

    public function render()
    {
        $trials = $this->applySearch($this->baseTrialQuery())
            ->latest()
            ->limit(30)
            ->get();

        $selectedTrial = $this->selectedTrialId
            ? $this->baseTrialQuery()->with(['user.personalInformation', 'student.user.personalInformation', 'advisingSession'])->find($this->selectedTrialId)
            : $trials->first();

        if (! $selectedTrial && $trials->isNotEmpty()) {
            $selectedTrial = $trials->first();
            $this->selectedTrialId = $selectedTrial->id;
        } elseif (! $this->selectedTrialId && $selectedTrial) {
            $this->selectedTrialId = $selectedTrial->id;
        }

        $examSchedule = $this->currentExamSchedule($selectedTrial);
        $weeklyProgram = $examSchedule?->weeklyProgram;

        $studentIds = $this->scopedStudentIds()->map(fn ($id) => (int) $id)->toArray();
        $reportDate = $this->getReportDate();
        $effectiveToday = $this->getEffectiveToday();
        $monitorLocked = ! $this->hasSuccessfulCall($selectedTrial);

        return view('livewire.admin.trial-acquisition.exam-monitor', [
            'trials' => $trials,
            'selectedTrial' => $selectedTrial,
            'examSchedule' => $examSchedule,
            'weeklyProgram' => $weeklyProgram,
            'monitorLocked' => $monitorLocked,
            'examProgramSummary' => $this->examProgramSummary($selectedTrial),
            'monitorSummary' => $this->monitorSummary($selectedTrial, $weeklyProgram),
            'weekDays' => $this->weekDays($weeklyProgram, $selectedTrial?->student_id ? (int) $selectedTrial->student_id : null),
            'expectedReportRows' => $this->expectedReportRows($selectedTrial, $weeklyProgram),
            'makeupSessions' => $this->makeupSessionsForMonitor($selectedTrial, $weeklyProgram),
            'missingReports' => $this->missingReports($studentIds),
            'reportDateJalali' => jdate($reportDate)->format('Y/m/d'),
            'reportDateDayName' => ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'][jdate($reportDate)->getDayOfWeek()] ?? '-',
            'canGoBack' => $reportDate->copy()->subDay()->gte($effectiveToday->copy()->subDays(2)),
            'canGoForward' => ! is_null($this->viewDate),
            'isViewingPast' => ! is_null($this->viewDate),
        ])->layout('layouts.admin.app');
    }
}
