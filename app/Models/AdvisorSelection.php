<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * انتخابِ مشاور توسط دانش‌آموز در انتظارِ تاییدِ مدیر آموزشی.
 */
class AdvisorSelection extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'weekly_day'  => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const MODE_MANUAL = 'manual';
    public const MODE_RANDOM = 'random';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getDayNameAttribute(): string
    {
        return AdminWorkSchedule::DAYS[$this->weekly_day] ?? '';
    }
}
