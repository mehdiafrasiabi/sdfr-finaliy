<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramPart extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'grade' => 'string',
        'part_date' => 'date',
        'day_of_week' => 'integer',

    ];

    public function studyPartSessions(): HasMany
    {
        return $this->hasMany(StudyPartSession::class);
    }

    const PART_TYPE_TEST = 'test';
    const PART_TYPE_DESCRIPTIVE = 'descriptive';
    const PART_TYPE_VIDEO = 'video';
    const PART_TYPE_TOPIC_EXAM = 'topic_exam';
    const PART_TYPE_COMPREHENSIVE_EXAM = 'comprehensive_exam';
    const PART_TYPE_EXAM_ANALYSIS = 'exam_analysis';
    const LESSON_TYPE_GENERAL = 'general';
    const LESSON_TYPE_SPECIALIZED = 'specialized';
    // Source types - where the part originated from
    const SOURCE_NORMAL = 'normal';
    const SOURCE_CLASS_QA = 'class_qa';
    const SOURCE_EXAM = 'exam';
    const SOURCE_HOMEWORK = 'homework';
    const SOURCE_DAILY_READING = 'daily_reading';
    const SOURCE_PRE_READING = 'pre_reading';
    const SOURCE_CLASSIFICATION = 'classification';
    const SOURCE_COMPREHENSIVE_EXAM = 'comprehensive_exam';

    public function weeklyProgram(): BelongsTo
    {
        return $this->belongsTo(WeeklyProgram::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    // نمایش نوع پارت فارسی
    public function getPartTypeLabelAttribute(): string
    {
        return match ($this->part_type) {
            self::PART_TYPE_TEST => 'تستی',
            self::PART_TYPE_DESCRIPTIVE => 'تشریحی',
            self::PART_TYPE_VIDEO => 'ویدئو',
            self::PART_TYPE_TOPIC_EXAM => 'آزمون مبحثی',
            self::PART_TYPE_COMPREHENSIVE_EXAM => 'آزمون جامع',
            self::PART_TYPE_EXAM_ANALYSIS => 'تحلیل آزمون',
            default => 'نامشخص',
        };
    }

    // نمایش نوع درس فارسی
    public function getLessonTypeLabelAttribute(): string
    {
        return match ($this->lesson_type) {
            self::LESSON_TYPE_GENERAL => 'عمومی',
            self::LESSON_TYPE_SPECIALIZED => 'تخصصی',
            default => 'نامشخص',
        };
    }

    public function getSourceTypeLabelAttribute(): string
    {
        return match ($this->source_type) {
            self::SOURCE_NORMAL => 'عادی',
            self::SOURCE_CLASS_QA => 'پرسش و پاسخ کلاسی',
            self::SOURCE_EXAM => 'امتحانات',
            self::SOURCE_HOMEWORK => 'تکالیف',
            self::SOURCE_DAILY_READING => 'روزخوانی',
            self::SOURCE_PRE_READING => 'پیش‌خوانی',
            self::SOURCE_CLASSIFICATION => 'طبقه‌بندی',
            self::SOURCE_COMPREHENSIVE_EXAM => 'آزمون جامع',
            default => 'عادی',
        };
    }

    // رنگ منبع پارت
    public function getSourceTypeColorAttribute(): string
    {
        return match ($this->source_type) {
            self::SOURCE_NORMAL => 'primary',
            self::SOURCE_CLASS_QA => 'info',
            self::SOURCE_EXAM => 'warning',
            self::SOURCE_HOMEWORK => 'danger',
            self::SOURCE_DAILY_READING => 'success',
            self::SOURCE_PRE_READING => 'info',
            self::SOURCE_CLASSIFICATION => 'secondary',
            self::SOURCE_COMPREHENSIVE_EXAM => 'dark',
            default => 'primary',
        };
    }

    // رنگ منبع پارت (Tailwind)
    public function getSourceTypeTwClassAttribute(): string
    {
        return match ($this->source_type) {
            self::SOURCE_NORMAL => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300',
            self::SOURCE_CLASS_QA => 'bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300',
            self::SOURCE_EXAM => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300',
            self::SOURCE_HOMEWORK => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300',
            self::SOURCE_DAILY_READING => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300',
            self::SOURCE_PRE_READING => 'bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300',
            self::SOURCE_CLASSIFICATION => 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300',
            self::SOURCE_COMPREHENSIVE_EXAM => 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
            default => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300',
        };
    }

    // نمایش پایه فارسی
    public function getGradeLabelAttribute(): string
    {
        return match ($this->grade) {
            '10' => 'دهم',
            '11' => 'یازدهم',
            '12' => 'دوازدهم',
            default => '-',
        };
    }

    // نمایش روز هفته فارسی
    public function getDayNameAttribute(): string
    {
        $dayNames = ['شنبه', '۱شنبه', '۲شنبه', '۳شنبه', '۴شنبه', '۵شنبه', 'جمعه'];
        return $dayNames[$this->day_of_week] ?? '-';
    }

    // تبدیل دقیقه به ساعت
    public function getDurationHoursAttribute(): float
    {
        return round($this->duration_minutes / 60, 1);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function ccGrade(): BelongsTo
    {
        return $this->belongsTo(CcGrade::class);
    }

    public function ccField(): BelongsTo
    {
        return $this->belongsTo(CcField::class);
    }

    public function ccSubject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class);
    }

    public function ccChapter(): BelongsTo
    {
        return $this->belongsTo(CcChapter::class);
    }

    public function ccTopic(): BelongsTo
    {
        return $this->belongsTo(CcTopic::class);
    }
}
