<?php

namespace App\Livewire\Admin\EducationalManager\Appointment;

use App\Models\Admin;
use App\Models\AdminWorkSchedule;
use App\Models\Student;
use App\Models\StudentSchedulePreference;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';

    // modal state
    public ?int $selectedPreferenceId = null;
    public array $matchedConsultants = [];

    public function openAssign(int $preferenceId): void
    {
        $pref = StudentSchedulePreference::with('times')->find($preferenceId);
        if (!$pref) {
            return;
        }
        $this->selectedPreferenceId = $preferenceId;
        $this->matchedConsultants = $this->computeMatches($pref);
    }

    public function closeAssign(): void
    {
        $this->selectedPreferenceId = null;
        $this->matchedConsultants = [];
    }

    /**
     * مچ کردن مشاوران با اسلات‌های دانش‌آموز و خروجی مرتب بر اساس بهترین تطابق.
     */
    protected function computeMatches(StudentSchedulePreference $pref): array
    {
        $consultants = Admin::role('academic_advisor')
            ->with('workSchedules')
            ->withCount('advisedStudents')
            ->get();

        $studentSlots = $pref->times; // Collection
        $result = [];

        foreach ($consultants as $c) {
            $overlapMinutes = 0;
            $matchedDays = [];

            foreach ($studentSlots as $st) {
                foreach ($c->workSchedules as $ws) {
                    if (!$ws->is_active) continue;
                    if ($ws->day_of_week !== $st->day_of_week) continue;

                    $sStart = substr($st->start_time, 0, 5);
                    $sEnd   = substr($st->end_time, 0, 5);
                    $wStart = substr($ws->start_time, 0, 5);
                    $wEnd   = substr($ws->end_time, 0, 5);

                    $overlapStart = max($sStart, $wStart);
                    $overlapEnd   = min($sEnd, $wEnd);
                    if ($overlapStart < $overlapEnd) {
                        $mins = $this->minutesBetween($overlapStart, $overlapEnd);
                        $overlapMinutes += $mins;
                        $matchedDays[] = [
                            'day'   => $ws->day_of_week,
                            'start' => $overlapStart,
                            'end'   => $overlapEnd,
                        ];
                    }
                }
            }

            if ($overlapMinutes > 0) {
                $result[] = [
                    'id'               => $c->id,
                    'name'             => $c->name,
                    'email'            => $c->email,
                    'mobile'           => $c->mobile,
                    'active_students'  => $c->advised_students_count,
                    'overlap_minutes'  => $overlapMinutes,
                    'matched_days'     => $matchedDays,
                ];
            }
        }

        // مرتب‌سازی: بیشترین تطابق، سپس کمترین تعداد دانش‌آموز
        usort($result, function ($a, $b) {
            if ($a['overlap_minutes'] !== $b['overlap_minutes']) {
                return $b['overlap_minutes'] <=> $a['overlap_minutes'];
            }
            return $a['active_students'] <=> $b['active_students'];
        });

        return $result;
    }

    protected function minutesBetween(string $start, string $end): int
    {
        [$sh, $sm] = array_map('intval', explode(':', $start));
        [$eh, $em] = array_map('intval', explode(':', $end));
        return ($eh * 60 + $em) - ($sh * 60 + $sm);
    }

    public function assignConsultant(int $consultantId): void
    {
        $pref = StudentSchedulePreference::with('student')->find($this->selectedPreferenceId);
        if (!$pref) {
            return;
        }
        $consultant = Admin::find($consultantId);
        if (!$consultant) {
            return;
        }

        DB::transaction(function () use ($pref, $consultant) {
            $pref->update([
                'assigned_advisor_id' => $consultant->id,
                'status'              => StudentSchedulePreference::STATUS_APPROVED,
                'approved_at'         => now(),
            ]);
            // اختصاص در مدل Student
            $pref->student->update(['advisor_id' => $consultant->id]);

            // علامت‌گذاری ترجیح‌های قبلی
            StudentSchedulePreference::where('student_id', $pref->student_id)
                ->where('id', '!=', $pref->id)
                ->where('status', '!=', StudentSchedulePreference::STATUS_REPLACED)
                ->update(['status' => StudentSchedulePreference::STATUS_REPLACED]);
        });

        session()->flash('message', 'مشاور با موفقیت برای دانش‌آموز انتخاب شد.');
        $this->closeAssign();
    }

    public function reject(int $preferenceId): void
    {
        $pref = StudentSchedulePreference::find($preferenceId);
        if ($pref) {
            $pref->update(['status' => StudentSchedulePreference::STATUS_REPLACED]);
            session()->flash('message', 'درخواست رد شد.');
        }
    }

    public function render()
    {
        $query = StudentSchedulePreference::query()
            ->with(['student.user', 'times', 'assignedAdvisor']);

        if ($this->statusFilter === 'pending') {
            $query->where('status', StudentSchedulePreference::STATUS_PENDING);
        } elseif ($this->statusFilter === 'approved') {
            $query->where('status', StudentSchedulePreference::STATUS_APPROVED);
        }

        $preferences = $query->latest('submitted_at')->paginate(15);
        $selectedPref = $this->selectedPreferenceId
            ? StudentSchedulePreference::with(['student.user', 'times'])->find($this->selectedPreferenceId)
            : null;

        return view('livewire.admin.educational-manager.appointment.index', [
            'preferences'  => $preferences,
            'days'         => AdminWorkSchedule::DAYS,
            'selectedPref' => $selectedPref,
        ])->layout('layouts.admin.app');
    }
}
