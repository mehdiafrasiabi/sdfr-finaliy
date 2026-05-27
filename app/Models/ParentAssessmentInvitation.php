<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParentAssessmentInvitation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expires_at'        => 'datetime',
        'sent_at'           => 'datetime',
        'first_accessed_at' => 'datetime',
        'completed_at'      => 'datetime',
    ];

    const ROLE_FATHER = 'father';
    const ROLE_MOTHER = 'mother';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trialWeek(): BelongsTo
    {
        return $this->belongsTo(TrialWeek::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ParentAssessmentAttempt::class, 'invitation_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function getParentRoleLabelAttribute(): string
    {
        return match ($this->parent_role) {
            self::ROLE_FATHER => 'پدر',
            self::ROLE_MOTHER => 'مادر',
            default           => $this->parent_role,
        };
    }
}
