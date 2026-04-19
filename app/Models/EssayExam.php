<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * آزمون تشریحی (مبحثی)
 */
class EssayExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'cc_topic_id',
        'title',
        'question_pdf_path',
        'answer_pdf_path',
        'total_score',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function topic()
    {
        return $this->belongsTo(CcTopic::class, 'cc_topic_id');
    }

    public function questions()
    {
        return $this->hasMany(EssayExamQuestion::class)->orderBy('question_number');
    }

    public function assignments()
    {
        return $this->hasMany(EssayExamAssignment::class);
    }

    public function questionPdfUrl(): ?string
    {
        return $this->question_pdf_path
            ? rtrim(config('app.url'), '/') . '/' . ltrim($this->question_pdf_path, '/')
            : null;
    }

    public function answerPdfUrl(): ?string
    {
        return $this->answer_pdf_path
            ? rtrim(config('app.url'), '/') . '/' . ltrim($this->answer_pdf_path, '/')
            : null;
    }
}

