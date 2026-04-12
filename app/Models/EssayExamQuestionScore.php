<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayExamQuestionScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'essay_exam_question_id',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function attempt()
    {
        return $this->belongsTo(EssayExamAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(EssayExamQuestion::class, 'essay_exam_question_id');
    }
}

