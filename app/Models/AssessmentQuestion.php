<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'scoring_meta' => 'array',
        'is_active'    => 'boolean',
    ];

    const TYPE_LIKERT5      = 'likert5';
    const TYPE_YES_NO       = 'yes_no';
    const TYPE_MBTI_BINARY  = 'mbti_binary';
    const TYPE_VARK_MULTI   = 'vark_multi';

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(AssessmentQuestionOption::class, 'question_id')->orderBy('order');
    }

    public function isMultiSelect(): bool
    {
        return $this->type === self::TYPE_VARK_MULTI;
    }
}
