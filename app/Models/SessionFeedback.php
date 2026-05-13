<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionFeedback extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'session_feedbacks';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studyPartSession(): BelongsTo
    {
        return $this->belongsTo(StudyPartSession::class, 'sps_id');
    }

    public function getRatingLabelAttribute(): string
    {
        return match (true) {
            $this->rating >= 9 => 'عالی',
            $this->rating >= 7 => 'خوب',
            $this->rating >= 5 => 'متوسط',
            $this->rating >= 3 => 'ضعیف',
            default => 'خیلی ضعیف',
        };
    }

    public function getRatingColorAttribute(): string
    {
        return match (true) {
            $this->rating >= 9 => 'emerald',
            $this->rating >= 7 => 'blue',
            $this->rating >= 5 => 'yellow',
            $this->rating >= 3 => 'orange',
            default => 'red',
        };
    }
}
