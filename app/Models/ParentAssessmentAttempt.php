<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentAssessmentAttempt extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'started_at'      => 'datetime',
        'completed_at'    => 'datetime',
        'computed_result' => 'array',
    ];

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED   = 'completed';

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(ParentAssessmentInvitation::class, 'invitation_id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ParentAssessmentAnswer::class, 'attempt_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getProgressPercentAttribute(): int
    {
        $total = $this->assessment?->questions()->where('is_active', true)->count() ?? 0;
        if ($total === 0) {
            return 0;
        }
        return (int) min(100, round(($this->answered_count / $total) * 100));
    }
}
