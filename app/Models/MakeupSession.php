<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakeupSession extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const PART_TYPE_TEST = 'test';
    const PART_TYPE_DESCRIPTIVE = 'descriptive';
    const PART_TYPE_VIDEO = 'video';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function ccTopic(): BelongsTo
    {
        return $this->belongsTo(CcTopic::class);
    }
    public function ccChapter(): BelongsTo
    {
        return $this->belongsTo(CcChapter::class);
    }
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(SessionFeedback::class, 'id', 'makeup_session_id');
    }

    public function isEditable(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'در انتظار تایید',
            self::STATUS_APPROVED => 'تایید شده',
            self::STATUS_REJECTED => 'رد شده',
            default => 'نامشخص',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'emerald',
            self::STATUS_REJECTED => 'red',
            default => 'slate',
        };
    }

    public function getPartTypeLabelAttribute(): string
    {
        return match ($this->part_type) {
            self::PART_TYPE_TEST => 'تستی',
            self::PART_TYPE_DESCRIPTIVE => 'تشریحی',
            self::PART_TYPE_VIDEO => 'ویدیویی',
            default => 'نامشخص',
        };
    }

    public function getPartTypeColorAttribute(): string
    {
        return match ($this->part_type) {
            self::PART_TYPE_TEST => 'blue',
            self::PART_TYPE_DESCRIPTIVE => 'purple',
            self::PART_TYPE_VIDEO => 'orange',
            default => 'gray',
        };
    }
}
