<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExamDayFeedback extends Model
{
    use HasFactory;

    protected $table = 'student_exam_day_feedbacks';

    public const DIFFICULTY_EASY = 'easy';
    public const DIFFICULTY_MEDIUM = 'medium';
    public const DIFFICULTY_HARD = 'hard';
    public const DIFFICULTY_FAILED = 'failed';

    public const DIFFICULTY_LABELS = [
        self::DIFFICULTY_EASY => 'آسون بود',
        self::DIFFICULTY_MEDIUM => 'متوسط بود',
        self::DIFFICULTY_HARD => 'سخت بود',
        self::DIFFICULTY_FAILED => 'خراب کردم',
    ];

    protected $guarded = [];

    protected $casts = [
        'exam_date' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudentExamSchedule::class, 'student_exam_schedule_id');
    }
}
