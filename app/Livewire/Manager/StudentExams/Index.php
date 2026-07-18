<?php

namespace App\Livewire\Manager\StudentExams;

use App\Models\ExamPlanningSetting;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class Index extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $editingId = null;

    public int $grade = 12;
    public ?string $field = 'math';
    public string $term_type = ExamPlanningSetting::TERM_FIRST;
    public string $input_mode = ExamPlanningSetting::INPUT_STUDENT;
    public string $activation_starts_at = '';
    public string $activation_ends_at = '';
    public string $exam_starts_at = '';
    public string $exam_ends_at = '';
    public int $max_daily_study_hours = 12;
    public bool $is_active = true;

    public function mount(): void
    {
        $this->activation_starts_at = jdate(now())->format('Y/m/d');
        $this->activation_ends_at = jdate(now()->addMonth())->format('Y/m/d');
    }

    public function updatedGrade($value): void
    {
        if ((int) $value === 9) {
            $this->field = null;
        } elseif ($this->field === null) {
            $this->field = 'math';
        }
    }

    public function updatedInputMode($value): void
    {
        if ($value === ExamPlanningSetting::INPUT_STUDENT) {
            $this->exam_starts_at = '';
            $this->exam_ends_at = '';
        }
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $settingId): void
    {
        $setting = ExamPlanningSetting::findOrFail($settingId);

        $this->editingId = $setting->id;
        $this->grade = (int) $setting->grade;
        $this->field = $setting->field;
        $this->term_type = $setting->term_type;
        $this->input_mode = $setting->input_mode;
        $this->activation_starts_at = $this->toJalali($setting->activation_starts_at?->toDateString());
        $this->activation_ends_at = $this->toJalali($setting->activation_ends_at?->toDateString());
        $this->exam_starts_at = $this->toJalali($setting->exam_starts_at?->toDateString());
        $this->exam_ends_at = $this->toJalali($setting->exam_ends_at?->toDateString());
        $this->max_daily_study_hours = (int) $setting->max_daily_study_hours;
        $this->is_active = (bool) $setting->is_active;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $activationStart = $this->fromJalali($this->activation_starts_at);
        $activationEnd = $this->fromJalali($this->activation_ends_at);
        $examStart = $this->fromJalali($this->exam_starts_at);
        $examEnd = $this->fromJalali($this->exam_ends_at);

        $rules = [
            'grade' => ['required', 'integer', 'in:9,10,11,12,13'],
            'term_type' => ['required', 'in:first,second,final'],
            'input_mode' => ['required', 'in:manager,student'],
            'max_daily_study_hours' => ['required', 'integer', 'min:4', 'max:16'],
            'is_active' => ['boolean'],
        ];

        $messages = [
            'grade.required' => 'پایه الزامی است.',
            'term_type.required' => 'نوع نوبت الزامی است.',
            'input_mode.required' => 'نوع ثبت الزامی است.',
            'max_daily_study_hours.required' => 'حداکثر ساعت روزانه الزامی است.',
        ];

        $this->validate($rules, $messages);

        if ((int) $this->grade !== 9 && ! $this->field) {
            $this->addError('field', 'برای این پایه انتخاب رشته الزامی است.');
            return;
        }

        if (! $activationStart || ! $activationEnd) {
            $this->addError('activation_starts_at', 'بازه فعال‌سازی معتبر نیست.');
            return;
        }

        if ($activationEnd < $activationStart) {
            $this->addError('activation_ends_at', 'تاریخ پایان فعال‌سازی باید بعد از شروع باشد.');
            return;
        }

        if ($this->input_mode === ExamPlanningSetting::INPUT_MANAGER) {
            if (! $examStart || ! $examEnd) {
                $this->addError('exam_starts_at', 'برای حالت ثبت توسط مدیر، بازه امتحانات الزامی است.');
                return;
            }

            if ($examEnd < $examStart) {
                $this->addError('exam_ends_at', 'تاریخ پایان امتحانات باید بعد از شروع باشد.');
                return;
            }
        } else {
            $examStart = null;
            $examEnd = null;
        }

        $setting = ExamPlanningSetting::query()->updateOrCreate(
            ['id' => $this->editingId],
            [
                'grade' => $this->grade,
                'field' => (int) $this->grade === 9 ? null : $this->field,
                'term_type' => $this->term_type,
                'input_mode' => $this->input_mode,
                'activation_starts_at' => $activationStart,
                'activation_ends_at' => $activationEnd,
                'exam_starts_at' => $examStart,
                'exam_ends_at' => $examEnd,
                'max_daily_study_hours' => $this->max_daily_study_hours,
                'is_active' => $this->is_active,
            ]
        );

        $this->dispatch('success', $this->editingId ? 'تنظیم امتحان ویرایش شد.' : 'تنظیم امتحان ایجاد شد.');
        $this->closeModal();

        if ($setting->input_mode === ExamPlanningSetting::INPUT_MANAGER) {
            return redirect()->route('manager.student-exams.detail', $setting->id);
        }
    }

    public function toggleActive(int $settingId): void
    {
        $setting = ExamPlanningSetting::findOrFail($settingId);
        $setting->update(['is_active' => ! $setting->is_active]);
    }

    public function delete(int $settingId): void
    {
        ExamPlanningSetting::findOrFail($settingId)->delete();
        $this->dispatch('success', 'تنظیم انتخاب‌شده حذف شد.');
    }

    public function render()
    {
        $settings = ExamPlanningSetting::query()
            ->withCount(['days', 'sampleQuestions'])
            ->orderByDesc('activation_starts_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('livewire.manager.student-exams.index', [
            'settings' => $settings,
            'gradeOptions' => ExamPlanningSetting::GRADE_LABELS,
            'fieldOptions' => ExamPlanningSetting::FIELD_LABELS,
        ])->layout('layouts.manager.app');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'showModal',
        ]);

        $this->grade = 12;
        $this->field = 'math';
        $this->term_type = ExamPlanningSetting::TERM_FIRST;
        $this->input_mode = ExamPlanningSetting::INPUT_STUDENT;
        $this->activation_starts_at = jdate(now())->format('Y/m/d');
        $this->activation_ends_at = jdate(now()->addMonth())->format('Y/m/d');
        $this->exam_starts_at = '';
        $this->exam_ends_at = '';
        $this->max_daily_study_hours = 12;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    private function fromJalali(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $normalized = strtr($value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '-' => '/',
        ]);

        try {
            return Jalalian::fromFormat('Y/m/d', $normalized)->toCarbon()->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function toJalali(?string $value): string
    {
        return $value ? jdate($value)->format('Y/m/d') : '';
    }
}
