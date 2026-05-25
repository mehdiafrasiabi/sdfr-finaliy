<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyPartSession extends Model
{
    use HasFactory;

    const CHEAT_STATUS_PENDING  = 'pending';
    const CHEAT_STATUS_APPROVED = 'approved';
    const CHEAT_STATUS_REJECTED = 'rejected';
    const CHEAT_GRACE_SECONDS   = 1800;

    protected $guarded = [];
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'is_early_finish' => 'boolean',
        'extra_seconds' => 'integer',
        'extra_target_seconds' => 'integer',
        'extra_started_at' => 'datetime',
        'extra_ended_at' => 'datetime',
        'is_cheating' => 'boolean',
        'cheat_minutes' => 'integer',
        'cheat_decided_at' => 'datetime',
    ];

    public function getIsExtraEarlyFinishAttribute(): bool
    {
        return (int)($this->extra_seconds ?? 0) > 0
            && (int)($this->extra_target_seconds ?? 0) > 0
            && (int)$this->extra_seconds < (int)$this->extra_target_seconds;
    }

    public function getCheatStatusLabelAttribute(): string
    {
        return match ($this->cheat_status) {
            self::CHEAT_STATUS_PENDING  => 'در انتظار تایید',
            self::CHEAT_STATUS_APPROVED => 'تایید شده',
            self::CHEAT_STATUS_REJECTED => 'رد شده',
            default => '—',
        };
    }

    public function getCheatStatusColorAttribute(): string
    {
        return match ($this->cheat_status) {
            self::CHEAT_STATUS_PENDING  => 'warning',
            self::CHEAT_STATUS_APPROVED => 'info',
            self::CHEAT_STATUS_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    public function getHasExtraTimeAttribute(): bool
    {
        return (int)($this->extra_seconds ?? 0) > 0;
    }

    public function getExtraMinutesAttribute(): int
    {
        return (int) round(((int)($this->extra_seconds ?? 0)) / 60);
    }

    public function getExtraTimeLabelAttribute(): string
    {
        $s = (int)($this->extra_seconds ?? 0);
        if ($s <= 0) return '';
        $h = intdiv($s, 3600);
        $m = intdiv($s % 3600, 60);
        return $h > 0 ? sprintf('+%d:%02d', $h, $m) : sprintf('+0:%02d', $m);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function programPart(): BelongsTo
    {
        return $this->belongsTo(ProgramPart::class);
    }
    public function weeklyProgram(): BelongsTo
    {
        return $this->belongsTo(WeeklyProgram::class);
    }
    public function timing(): HasOne
    {
        return $this->hasOne(SpsTiming::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(SessionFeedback::class, 'sps_id');
    }

    public function cheatDecider(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'cheat_decided_by');
    }

}
