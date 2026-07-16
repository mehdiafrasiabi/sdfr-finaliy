<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentExamSchedule extends Model
{
    use HasFactory;

    public const SOURCE_MANAGER = 'manager';
    public const SOURCE_STUDENT = 'student';

    protected $guarded = [];

    protected $casts = [
        'exam_starts_at' => 'date',
        'exam_ends_at' => 'date',
        'submitted_at' => 'datetime',
        'program_built_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'exam_program_started_sms_sent_at' => 'datetime',
        'exam_program_ended_sms_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(ExamPlanningSetting::class, 'exam_planning_setting_id');
    }

    public function weeklyProgram(): BelongsTo
    {
        return $this->belongsTo(WeeklyProgram::class);
    }

    public function days(): HasMany
    {
        return $this->hasMany(StudentExamScheduleDay::class)->orderBy('exam_date')->orderBy('cc_subject_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StudentExamStudyAllocation::class)->latest('id');
    }

    public function hasCalendar(): bool
    {
        return $this->days()->exists() && $this->exam_starts_at && $this->exam_ends_at;
    }
}
