<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'essay_exam_id',
        'question_number',
        'score',
        'row_height',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'row_height' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(EssayExam::class, 'essay_exam_id');
    }
}

