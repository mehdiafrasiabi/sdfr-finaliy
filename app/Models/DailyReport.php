<?php


namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
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
    /**
     * Get all program parts scheduled for this report's day from the weekly program.
     */
    public function getProgramPartsForDay(): Collection
    {
        $weeklyProgram = $this->weeklyProgram;
        if (!$weeklyProgram) return collect();

        $startDate = Carbon::parse($weeklyProgram->start_date);
        $dayIndex = $startDate->diffInDays(Carbon::parse($this->report_date));

        if ($weeklyProgram->relationLoaded('parts')) {
            return $weeklyProgram->parts->where('day_of_week', $dayIndex)->sortBy('part_order')->values();
        }

        return $weeklyProgram->parts()->where('day_of_week', $dayIndex)->orderBy('part_order')->get();
    }

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
        $allParts = $this->getProgramPartsForDay();
        return $allParts->isNotEmpty() ? $allParts->count() : $this->reportParts()->count();
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
        return $this->total_parts - $this->read_parts_count;
    }
    public function getTotalTestsAttribute(): int
    {
        $allParts = $this->getProgramPartsForDay();
        if ($allParts->isNotEmpty()) {
            return (int) $allParts->sum('test_count');
        }
        return (int) $this->reportParts()->sum('tests_done');
    }
    /**
     * Calculate average rating from session feedbacks for all program parts of this day.
     * * Parts without feedback count as 0.
     * * Example: 3 parts, 1 has rating 8 → (8+0+0)/3 = 2.67
 */
    public function getCalculatedRatingAttribute(): float
    {
        $allParts = $this->getProgramPartsForDay();
        if ($allParts->isEmpty()) return 0;

        $partIds = $allParts->pluck('id')->filter()->toArray();
        $totalParts = $allParts->count();

        $studySessions = StudyPartSession::where('student_id', $this->student_id)
            ->whereIn('program_part_id', $partIds)
            ->where('is_completed', true)
            ->with('feedback')
            ->get()
            ->groupBy('program_part_id')
            ->map(fn($sessions) => $sessions->sortByDesc('started_at')->first());

        $totalRating = 0;
        foreach ($allParts as $part) {
            $session = $studySessions->get($part->id);
            $totalRating += $session?->feedback?->rating ?? 0;
        }

        return $totalParts > 0 ? round($totalRating / $totalParts, 1) : 0;
    }
}
