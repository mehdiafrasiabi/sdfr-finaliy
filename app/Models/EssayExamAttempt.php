<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayExamAttempt extends Model
{
    use HasFactory;

    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_SUBMITTED   = 'submitted';
    public const STATUS_GRADED      = 'graded';

    protected $fillable = [
        'assignment_id',
        'started_at',
        'submitted_at',
        'total_score',
        'status',
        'consultant_message',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'total_score'  => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(EssayExamAssignment::class, 'assignment_id');
    }

    public function uploads()
    {
        return $this->hasMany(EssayExamAnswerUpload::class, 'attempt_id')->orderBy('sort_order');
    }

    public function questionScores()
    {
        return $this->hasMany(EssayExamQuestionScore::class, 'attempt_id');
    }

    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->started_at || !$this->assignment?->time) {
            return 0;
        }
        $deadline = $this->started_at->copy()->addMinutes($this->assignment->time->duration_minutes);
        $endWindow = $this->assignment->time->end_at;
        $effectiveEnd = $deadline->lt($endWindow) ? $deadline : $endWindow;
        return max(0, now()->diffInSeconds($effectiveEnd, false));
    }
}

