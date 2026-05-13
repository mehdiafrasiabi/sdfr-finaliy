<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypedExamAttempt extends Model

{

    use HasFactory;

    protected $guarded = [];


    protected $casts = [

        'started_at' => 'datetime',

        'submitted_at' => 'datetime',

        'is_finished' => 'boolean',

        'score' => 'decimal:2',

        'analysis_status' => 'string',

    ];


    /**
     * اختصاص مرتبط
     */

    public function assignment(): BelongsTo

    {

        return $this->belongsTo(TypedExamAssignment::class, 'assignment_id');

    }


    /**
     * دانش‌آموز
     */

    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }


    /**
     * پاسخ‌ها
     */

    public function answers(): HasMany

    {

        return $this->hasMany(TypedExamAttemptAnswer::class, 'attempt_id');

    }



    /**
     * ترتیب سوالات و گزینه‌ها برای این دانش‌آموز
     */

    public function studentOrders(): HasMany

    {

        return $this->hasMany(TypedExamStudentOrder::class, 'attempt_id');

    }


    /**
     * آزمون مرتبط (از طریق assignment)
     */

    public function getTypedExamAttribute(): ?TypedExam

    {

        return $this->assignment?->typedExam;

    }


    /**
     * محاسبه نمره
     */

    public function calculateScore(): float

    {

        $totalQuestions = $this->answers()->count();

        if ($totalQuestions === 0) {

            return 0;

        }


        $correctAnswers = $this->answers()->where('is_correct', true)->count();

        return round(($correctAnswers / $totalQuestions) * 100, 2);

    }


    /**
     * آپدیت نمره
     */

    public function updateScore(): void

    {

        $this->update(['score' => $this->calculateScore()]);

    }


    /**
     * تعداد پاسخ‌های صحیح
     */

    public function getCorrectCountAttribute(): int

    {

        return $this->answers()->where('is_correct', true)->count();

    }


    /**
     * تعداد پاسخ‌های غلط
     */

    public function getWrongCountAttribute(): int

    {

        return $this->answers()->where('is_correct', false)->whereNotNull('selected_option')->count();

    }


    /**
     * تعداد بدون پاسخ
     */


    public function getUnansweredCountAttribute(): int

    {

        return $this->answers()->whereNull('selected_option')->count();

    }


    /**
     * مدت زمان صرف شده (ثانیه)
     */

    public function getDurationInSecondsAttribute(): ?int

    {

        if (!$this->started_at || !$this->submitted_at) {

            return null;

        }


        return $this->submitted_at->diffInSeconds($this->started_at);

    }

    public function analysisUploads(): HasMany

    {

        return $this->hasMany(TypedExamAnalysisUpload::class, 'attempt_id');

    }


    /**
     * مدت زمان فرمت شده
     */

    public function getFormattedDurationAttribute(): string

    {

        $seconds = $this->duration_in_seconds;

        if (!$seconds) {

            return '-';

        }


        $hours = floor($seconds / 3600);

        $minutes = floor(($seconds % 3600) / 60);

        $secs = $seconds % 60;


        if ($hours > 0) {

            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);

        }


        return sprintf('%02d:%02d', $minutes, $secs);

    }

    public function isAnalysisPending(): bool

    {

        return $this->analysis_status === 'pending';

    }


    /**
     * آیا تحلیل تایید شده
     */

    public function isAnalysisApproved(): bool

    {

        return $this->analysis_status === 'approved';

    }


    /**
     * آیا تحلیل رد شده
     */

    public function isAnalysisRejected(): bool

    {

        return $this->analysis_status === 'rejected';

    }


    /**
     * آیا امکان آپلود تحلیل وجود دارد
     */

    public function canUploadAnalysis(): bool

    {

        return $this->is_finished && ($this->analysis_status === null || $this->analysis_status === 'rejected');

    }


    /**
     * لیبل وضعیت تحلیل
     */

    public function getAnalysisStatusLabelAttribute(): string

    {

        return match ($this->analysis_status) {

            'pending' => 'در انتظار تایید',

            'approved' => 'تایید شده',

            'rejected' => 'رد شده',

            default => 'بدون تحلیل',

        };

    }


    /**
     * رنگ وضعیت تحلیل
     */

    public function getAnalysisStatusColorAttribute(): string

    {

        return match ($this->analysis_status) {

            'pending' => 'yellow',

            'approved' => 'green',

            'rejected' => 'red',

            default => 'gray',

        };

    }
}
