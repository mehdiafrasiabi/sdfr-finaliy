<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\AdvisingPreSession;
use App\Models\WeeklyProgram;

class ExamMonitor extends Monitor
{
    public function render()
    {
        $trials = $this->baseTrialQuery()
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($u) => $u
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('mobile', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->limit(30)
            ->get();

        $selectedTrial = $this->selectedTrialId
            ? $this->baseTrialQuery()->with(['user.personalInformation', 'student.user.personalInformation', 'advisingSession'])->find($this->selectedTrialId)
            : $trials->first();

        if (! $this->selectedTrialId && $selectedTrial) {
            $this->selectedTrialId = $selectedTrial->id;
        }

        $weeklyProgram = $selectedTrial?->student_id
            ? WeeklyProgram::with(['parts', 'restDays', 'examDays'])
                ->where('student_id', $selectedTrial->student_id)
                ->where('is_active', true)
                ->latest('start_date')
                ->first()
            : null;

        $preSessions = $selectedTrial?->student_id
            ? AdvisingPreSession::where('student_id', $selectedTrial->student_id)
                ->with(['advisingSession', 'exams'])
                ->latest()
                ->get()
            : collect();

        $studentIds = $this->scopedStudentIds()->map(fn ($id) => (int) $id)->toArray();
        $reportDate = $this->getReportDate();
        $effectiveToday = $this->getEffectiveToday();
        $monitorLocked = ! $this->hasSuccessfulCall($selectedTrial);

        return view('livewire.admin.trial-acquisition.exam-monitor', [
            'trials' => $trials,
            'selectedTrial' => $selectedTrial,
            'weeklyProgram' => $weeklyProgram,
            'monitorLocked' => $monitorLocked,
            'examProgramSummary' => $this->examProgramSummary($selectedTrial),
            'preSessions' => $preSessions,
            'missingReports' => $this->missingReports($studentIds),
            'reportDateJalali' => jdate($reportDate)->format('Y/m/d'),
            'reportDateDayName' => ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'][jdate($reportDate)->getDayOfWeek()] ?? '-',
            'canGoBack' => $reportDate->copy()->subDay()->gte($effectiveToday->copy()->subDays(2)),
            'canGoForward' => ! is_null($this->viewDate),
            'isViewingPast' => ! is_null($this->viewDate),
        ])->layout('layouts.admin.app');
    }
}
