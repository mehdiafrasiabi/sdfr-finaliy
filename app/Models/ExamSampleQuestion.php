<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSampleQuestion extends Model
{
    use HasFactory;

    public const EXAM_PERIOD_MONTHS = [
        3 => 'خرداد',
        6 => 'شهریور',
        10 => 'دی',
    ];

    public const EXAM_PERIOD_YEARS = [
        1398,
        1399,
        1400,
        1401,
        1402,
        1403,
        1404,
        1405,
    ];

    protected $guarded = [];

    protected $casts = [
        'duration_minutes' => 'integer',
        'exam_period_month' => 'integer',
        'exam_period_year' => 'integer',
        'is_main' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(ExamPlanningSetting::class, 'exam_planning_setting_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }

    public function getDownloadUrlAttribute(): string
    {
        return FileHelper::publicUrl($this->pdf_path);
    }

    public function getExamPeriodMonthLabelAttribute(): string
    {
        return self::EXAM_PERIOD_MONTHS[(int) $this->exam_period_month] ?? '';
    }

    public function getExamPeriodLabelAttribute(): string
    {
        if (! $this->exam_period_month || ! $this->exam_period_year) {
            return 'دسته‌بندی نشده';
        }

        return trim($this->exam_period_month_label . ' ' . $this->exam_period_year);
    }

    public static function defaultTitle(?int $month, ?int $year): string
    {
        $monthLabel = self::EXAM_PERIOD_MONTHS[(int) $month] ?? null;

        if (! $monthLabel || ! $year) {
            return 'امتحان نهایی';
        }

        return "امتحان نهایی {$monthLabel} {$year}";
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}
