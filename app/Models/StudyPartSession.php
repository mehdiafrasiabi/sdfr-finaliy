<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyPartSession extends Model
{
    use HasFactory;

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
    ];

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

}
