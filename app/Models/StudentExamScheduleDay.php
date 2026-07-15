<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExamScheduleDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudentExamSchedule::class, 'student_exam_schedule_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }
}
