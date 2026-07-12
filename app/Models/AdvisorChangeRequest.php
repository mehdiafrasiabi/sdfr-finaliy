<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisorChangeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public const SUBJECT_SCHEDULE_MISMATCH = 'schedule_mismatch';
    public const SUBJECT_FOLLOW_UP_DISSATISFACTION = 'follow_up_dissatisfaction';
    public const SUBJECT_OTHER = 'other';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED_BY_STUDENT = 'cancelled_by_student';

    public static function subjects(): array
    {
        return [
            self::SUBJECT_SCHEDULE_MISMATCH => 'عدم تطبیق زمانی با مشاور',
            self::SUBJECT_FOLLOW_UP_DISSATISFACTION => 'عدم رضایت از پیگیری',
            self::SUBJECT_OTHER => 'سایر',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function oldAdvisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'old_advisor_id');
    }

    public function newAdvisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'new_advisor_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getSubjectLabelAttribute(): string
    {
        return $this->subject === self::SUBJECT_OTHER && $this->subject_other
            ? $this->subject_other
            : (self::subjects()[$this->subject] ?? 'نامشخص');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'در حال پیگیری توسط کارشناس',
            self::STATUS_APPROVED => 'موافقت شد',
            self::STATUS_REJECTED => 'رد شد',
            self::STATUS_CANCELLED_BY_STUDENT => 'لغو به درخواست دانش‌آموز',
            default => 'نامشخص',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CANCELLED_BY_STUDENT => 'secondary',
            default => 'light',
        };
    }
}
