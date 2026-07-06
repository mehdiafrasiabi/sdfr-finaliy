<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * درخواستِ مرخصیِ مشاور برای یک روز.
 */
class AdvisorLeave extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'leave_date'  => 'date',
        'reviewed_at' => 'datetime',
    ];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const STATUS_LABELS = [
        self::STATUS_PENDING  => 'در انتظار تایید',
        self::STATUS_APPROVED => 'تایید شده',
        self::STATUS_REJECTED => 'رد شده',
    ];

    /** حداقل فاصله‌ی مجاز برای ثبتِ مرخصی (ساعت). */
    public const MIN_LEAD_HOURS = 72;

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
