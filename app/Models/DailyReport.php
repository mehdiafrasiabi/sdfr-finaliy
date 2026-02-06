<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DailyReport extends Model

{
    protected $guarded = [];
    protected $casts = [
        'report_date' => 'date',
        'is_compensatory' => 'boolean',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const RATINGS = [
        4 => 'عالی',
        3 => 'خوب',
        2 => 'قابل قبول',
        1 => 'نیاز به تلاش بیشتر',
    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
    public function advisingSession(): BelongsTo
    {
        return $this->belongsTo(AdvisingSession::class, 'session_id');
    }

    public function weeklyProgram(): BelongsTo
    {
        return $this->belongsTo(WeeklyProgram::class);
    }

    public function reportParts(): HasMany
    {
        return $this->hasMany(DailyReportPart::class);
    }
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'در انتظار',
            self::STATUS_APPROVED => 'تایید شده',
            self::STATUS_REJECTED => 'رد شده',
            default => 'نامشخص',
        };
    }
    public function getRatingLabelAttribute(): string
    {
        return self::RATINGS[$this->rating] ?? 'نامشخص';
    }
    public function getDayNameAttribute(): string
    {
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        return $dayNames[$this->day_of_week] ?? '-';
    }
    public function getTotalPartsAttribute(): int
    {
        return $this->reportParts()->count();
    }
    public function detail(): HasOne
    {
        return $this->hasOne(DailyReportDetail::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(DailyReportFeedback::class);
    }

    // --- Accessors for backward compatibility ---

    public function getPhoneHoursAttribute()
    {
        return $this->detail?->phone_hours ?? 0;
    }

    public function getRatingAttribute()
    {
        return $this->detail?->rating ?? 3;
    }

    public function getStatusAttribute()
    {
        return $this->detail?->status ?? self::STATUS_PENDING;
    }
    public function getAdvisorCommentAttribute()
    {
        return $this->feedback?->advisor_comment;
    }

    public function getAdvisorCommentedAtAttribute()
    {
        return $this->feedback?->advisor_commented_at;
    }

    public function getStudentReplyAttribute()
    {
        return $this->feedback?->student_reply;
    }

    public function getStudentRepliedAtAttribute()
    {
        return $this->feedback?->student_replied_at;
    }
    public function getReadPartsCountAttribute(): int
    {
        return $this->reportParts()->where('is_read', true)->count();
    }
    public function getUnreadPartsCountAttribute(): int
    {
        return $this->reportParts()->where('is_read', false)->count();
    }
    public function getTotalTestsAttribute(): int
    {
        return $this->reportParts()->sum('tests_done');
    }
    /**
     * Calculate average part rating and round to get daily rating (1-4).
     */
    public function getCalculatedRatingAttribute(): int
    {
        $avg = $this->reportParts()->whereNotNull('part_rating')->avg('part_rating');
        return $avg ? (int) round($avg) : 3;
    }
}
