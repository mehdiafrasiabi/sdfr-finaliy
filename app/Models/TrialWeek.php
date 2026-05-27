<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialWeek extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expires_at'               => 'datetime',
        'assessments_completed_at' => 'datetime',
        'supporter_assigned_at'    => 'datetime',
        'classification_locked_at' => 'datetime',
        'pre_session_completed_at' => 'datetime',
        'program_built_at'         => 'datetime',
    ];

    const STATUS_PENDING               = 'pending';
    const STATUS_SUPPORTER_ASSIGNED    = 'supporter_assigned';
    const STATUS_CLASSIFICATION_DONE   = 'classification_done';
    const STATUS_PRE_SESSION_DONE      = 'pre_session_done';
    const STATUS_PROGRAM_BUILT         = 'program_built';

    const GRADE_LABELS = [
        9  => 'نهم',
        10 => 'دهم',
        11 => 'یازدهم',
        12 => 'دوازدهم',
    ];

    const FIELD_LABELS = [
        'math'         => 'ریاضی',
        'experimental' => 'تجربی',
        'human'        => 'انسانی',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * «پشتیبان جذب» اختصاص داده‌شده به این هفتهٔ آزمایشی.
     */
    public function acquisitionSupporter(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'acquisition_supporter_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function acquisitionContacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AcquisitionContact::class)->orderBy('contacted_at');
    }

    public function advisingSession(): BelongsTo
    {
        return $this->belongsTo(AdvisingSession::class);
    }

    public function getGradeLabelAttribute(): string
    {
        return self::GRADE_LABELS[$this->grade] ?? "پایه {$this->grade}";
    }

    public function getFieldLabelAttribute(): string
    {
        if ($this->grade == 9) {
            return '—';
        }
        return self::FIELD_LABELS[$this->field] ?? $this->field;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING             => 'در انتظار تایید',
            self::STATUS_SUPPORTER_ASSIGNED  => 'پشتیبان تخصیص یافت',
            self::STATUS_CLASSIFICATION_DONE => 'طبقه‌بندی تکمیل شد',
            self::STATUS_PRE_SESSION_DONE    => 'پیش‌جلسه تکمیل شد',
            self::STATUS_PROGRAM_BUILT       => 'برنامه ساخته شد',
            default                          => 'نامشخص',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING             => 'warning',
            self::STATUS_SUPPORTER_ASSIGNED  => 'info',
            self::STATUS_CLASSIFICATION_DONE => 'primary',
            self::STATUS_PRE_SESSION_DONE    => 'secondary',
            self::STATUS_PROGRAM_BUILT       => 'success',
            default                          => 'secondary',
        };
    }

    public function hasCompletedAssessments(): bool
    {
        return $this->assessments_completed_at !== null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::now()->isAfter($this->expires_at);
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->expires_at || $this->isExpired()) {
            return 0;
        }
        return (int) Carbon::now()->diffInDays($this->expires_at, false);
    }

    public function canStartClassification(): bool
    {
        return in_array($this->status, [
            self::STATUS_SUPPORTER_ASSIGNED,
        ]) && !$this->isExpired();
    }

    public function canFillPreSession(): bool
    {
        return $this->status === self::STATUS_CLASSIFICATION_DONE && !$this->isExpired();
    }

    public function canBuildProgram(): bool
    {
        return $this->status === self::STATUS_PRE_SESSION_DONE && !$this->isExpired();
    }

    public function hasFullAccess(): bool
    {
        return $this->status === self::STATUS_PROGRAM_BUILT && !$this->isExpired();
    }

    // مرحله فعلی به صورت عدد برای نمایش progress bar
    public function getStepAttribute(): int
    {
        return match ($this->status) {
            self::STATUS_PENDING             => 0,
            self::STATUS_SUPPORTER_ASSIGNED  => 1,
            self::STATUS_CLASSIFICATION_DONE => 2,
            self::STATUS_PRE_SESSION_DONE    => 3,
            self::STATUS_PROGRAM_BUILT       => 4,
            default                          => 0,
        };
    }
}
