<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentAssessmentAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'selected_options' => 'array',
        'answered_at'      => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ParentAssessmentAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(AssessmentQuestionOption::class, 'selected_option_id');
    }
}
