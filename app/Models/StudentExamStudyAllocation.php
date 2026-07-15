<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StudentExamStudyAllocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_priority_subject' => 'boolean',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudentExamSchedule::class, 'student_exam_schedule_id');
    }

    public function ratable(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }
}
