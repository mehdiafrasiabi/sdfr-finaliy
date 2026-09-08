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
        $totalQuestions = $this->studentOrders()->count();

        if ($totalQuestions === 0) {
            $totalQuestions = $this->answers()->count();
        }

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
     * محاسبه‌ی کامل و یکسانِ آمار نتیجه (صحیح/غلط/بدون‌پاسخ/درصد/نمره منفی) این تلاش.
     *
     * این متد منبعِ واحدِ محاسبه است: هم صفحه‌ی «کارنامه»ی خودِ دانش‌آموز و هم بخش
     * «آزمون‌های قبلاً شرکت‌شده»ی پنل مشاور باید از همینجا نتیجه بگیرند، تا این دو صفحه
     * هیچ‌وقت عدد متفاوت یا نادرست (مثلاً منفی، یا با تعداد کل سوالات اشتباه) نشان ندهند.
     *
     * تعداد کل سوالات همیشه بر اساس studentOrders محاسبه می‌شود (دقیقاً همان چیدمانی که در
     * لحظه‌ی شروعِ آزمون برای این دانش‌آموز ثبت شده)؛ فقط اگر این رکورد وجود نداشته باشد
     * (تلاش‌های خیلی قدیمی، قبل از قابلیتِ درهم‌ریزی گزینه‌ها) از answers استفاده می‌شود.
     */
    public function computeResultStats(): array
    {
        $orders = $this->relationLoaded('studentOrders') ? $this->studentOrders : $this->studentOrders()->get();
        $answers = ($this->relationLoaded('answers') ? $this->answers : $this->answers()->get())
            ->keyBy('question_id');

        if ($orders->isNotEmpty()) {
            $totalQuestions = $orders->count();
            $correctCount = 0;
            $wrongCount = 0;
            $unansweredCount = 0;

            foreach ($orders as $order) {
                $answer = $answers->get($order->question_id);

                if (!$answer || $answer->selected_option === null) {
                    $unansweredCount++;
                    continue;
                }

                if ($answer->is_correct) {
                    $correctCount++;
                } else {
                    $wrongCount++;
                }
            }
        } else {
            $totalQuestions = $answers->count();
            $correctCount = $answers->where('is_correct', true)->count();
            $wrongCount = $answers->filter(
                fn (TypedExamAttemptAnswer $a) => $a->is_correct === false && $a->selected_option !== null
            )->count();
            $unansweredCount = max($totalQuestions - $correctCount - $wrongCount, 0);
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
        $negativePenaltyCount = intdiv($wrongCount, 3);
        $negativeCorrectCount = max($correctCount - $negativePenaltyCount, 0);
        $negativeScore = $totalQuestions > 0 ? round(($negativeCorrectCount / $totalQuestions) * 100, 2) : 0;

        return [
            'total'                  => $totalQuestions,
            'correct'                => $correctCount,
            'wrong'                  => $wrongCount,
            'unanswered'             => $unansweredCount,
            'score'                  => $score,
            'negative_penalty_count' => $negativePenaltyCount,
            'negative_correct'       => $negativeCorrectCount,
            'negative_score'         => $negativeScore,
        ];
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
        $totalQuestions = $this->studentOrders()->count();

        if ($totalQuestions === 0) {
            $totalQuestions = $this->answers()->count();
        }

        $answeredCount = $this->answers()->whereNotNull('selected_option')->count();

        return max($totalQuestions - $answeredCount, 0);

    }


    /**
     * مدت زمان صرف شده (ثانیه)
     */

    public function getDurationInSecondsAttribute(): ?int

    {
        if (!$this->started_at || !$this->submitted_at) {

            return null;

        }

        return max($this->submitted_at->getTimestamp() - $this->started_at->getTimestamp(), 0);

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
