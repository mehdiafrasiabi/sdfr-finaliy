<?php

namespace App\Livewire\Manager\StudentExams;

use App\Models\ExamPlanningSetting;
use App\Models\ExamPlanningSettingDay;
use App\Services\ExamPlanningService;
use Livewire\Component;

class Detail extends Component
{
    public ExamPlanningSetting $setting;
    public array $daySubjectSelections = [];

    public function mount(int $settingId): void
    {
        $this->setting = ExamPlanningSetting::with(['days.subject'])->findOrFail($settingId);
    }

    public function addExamToDay(string $date, string $selectionKey): void
    {
        $subjectId = (int) ($this->daySubjectSelections[$selectionKey] ?? 0);
        if ($subjectId <= 0) {
            $this->dispatch('warning', 'ابتدا درس آن روز را انتخاب کنید.');
            return;
        }

        if ($this->setting->days()->where('cc_subject_id', $subjectId)->exists()) {
            $this->dispatch('warning', 'برای این درس قبلاً تاریخ امتحان ثبت شده است.');
            return;
        }

        $this->setting->days()->create([
            'exam_date' => $date,
            'cc_subject_id' => $subjectId,
        ]);

        $this->daySubjectSelections[$selectionKey] = '';
        $this->setting->refresh();
        $this->dispatch('success', 'امتحان روز موردنظر ثبت شد.');
    }

    public function removeExamDay(int $dayId): void
    {
        ExamPlanningSettingDay::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->findOrFail($dayId)
            ->delete();

        $this->setting->refresh();
        $this->dispatch('success', 'درس این روز حذف شد.');
    }

    public function render()
    {
        $curriculum = app(ExamPlanningService::class)->curriculumForSetting($this->setting);
        $calendarDays = $this->calendarDays();

        return view('livewire.manager.student-exams.detail', [
            'calendarDays' => $calendarDays,
            'subjectOptions' => collect($curriculum['specialized_subjects'])
                ->concat($curriculum['general_subjects'])
                ->map(fn (array $subject) => [
                    'id' => $subject['id'],
                    'name' => $subject['name'],
                    'type' => $subject['type'],
                ])
                ->values(),
        ])->layout('layouts.manager.app');
    }

    private function calendarDays(): array
    {
        if (! $this->setting->exam_starts_at || ! $this->setting->exam_ends_at) {
            return [];
        }

        $rows = [];
        $currentWeek = [];

        $current = $this->setting->exam_starts_at->copy();
        $end = $this->setting->exam_ends_at->copy();

        while ($current->lte($end)) {
            $jalali = jdate($current);
            $weekday = $jalali->getDayOfWeek();

            if (empty($currentWeek)) {
                for ($i = 0; $i < $weekday; $i++) {
                    $currentWeek[] = null;
                }
            }

            $currentWeek[] = [
                'date' => $current->toDateString(),
                'selection_key' => 'day_' . $current->format('Y_m_d'),
                'jalali' => $jalali->format('Y/m/d'),
                'day_name' => $jalali->format('l'),
                'day_number' => $jalali->format('d'),
                'subjects' => $this->setting->days
                    ->filter(fn ($day) => $day->exam_date?->toDateString() === $current->toDateString())
                    ->values(),
            ];

            if (count($currentWeek) === 7) {
                $rows[] = $currentWeek;
                $currentWeek = [];
            }

            $current->addDay();
        }

        if (! empty($currentWeek)) {
            while (count($currentWeek) < 7) {
                $currentWeek[] = null;
            }
            $rows[] = $currentWeek;
        }

        return $rows;
    }
}
