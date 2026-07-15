<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamPlanningSetting extends Model
{
    use HasFactory;

    public const TERM_FIRST = 'first';
    public const TERM_SECOND = 'second';
    public const TERM_FINAL = 'final';

    public const INPUT_MANAGER = 'manager';
    public const INPUT_STUDENT = 'student';

    public const GRADE_LABELS = [
        9 => 'نهم',
        10 => 'دهم',
        11 => 'یازدهم',
        12 => 'دوازدهم',
        13 => 'فارغ‌التحصیل',
    ];

    public const FIELD_LABELS = [
        'math' => 'ریاضی',
        'experimental' => 'تجربی',
        'human' => 'انسانی',
    ];

    protected $guarded = [];

    protected $casts = [
        'activation_starts_at' => 'date',
        'activation_ends_at' => 'date',
        'exam_starts_at' => 'date',
        'exam_ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function days(): HasMany
    {
        return $this->hasMany(ExamPlanningSettingDay::class)->orderBy('exam_date')->orderBy('cc_subject_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(StudentExamSchedule::class)->latest('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWindowOpen($query, ?Carbon $date = null)
    {
        $date = ($date ?? now())->toDateString();

        return $query
            ->whereDate('activation_starts_at', '<=', $date)
            ->whereDate('activation_ends_at', '>=', $date);
    }

    public function getGradeLabelAttribute(): string
    {
        return self::GRADE_LABELS[(int) $this->grade] ?? 'پایه ' . $this->grade;
    }

    public function getFieldLabelAttribute(): string
    {
        if ((int) $this->grade === 9) {
            return 'بدون رشته';
        }

        return self::FIELD_LABELS[$this->field] ?? 'بدون رشته';
    }

    public function getInputModeLabelAttribute(): string
    {
        return $this->input_mode === self::INPUT_MANAGER ? 'توسط مدیر' : 'توسط دانش‌آموز';
    }

    public function getTermTypeLabelAttribute(): string
    {
        return match ($this->term_type) {
            self::TERM_FIRST => 'نوبت اول',
            self::TERM_SECOND => 'نوبت دوم',
            default => 'امتحانات',
        };
    }

    public function usesManagerCalendar(): bool
    {
        return $this->input_mode === self::INPUT_MANAGER;
    }

    public function managerCalendarReady(): bool
    {
        return $this->usesManagerCalendar() && $this->days()->exists();
    }
}
