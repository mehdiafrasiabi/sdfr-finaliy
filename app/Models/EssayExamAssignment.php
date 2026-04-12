<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EssayExamAssignment extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_GRADED = 'graded';

    protected $fillable = [
        'essay_exam_id',
        'student_id',
        'admin_id',
        'status',
    ];

    public function essayExam()
    {
        return $this->belongsTo(EssayExam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function time()
    {
        return $this->hasOne(EssayExamAssignmentTime::class, 'assignment_id');
    }

    public function attempts()
    {
        return $this->hasMany(EssayExamAttempt::class, 'assignment_id');
    }

    public function latestAttempt()
    {
        return $this->hasOne(EssayExamAttempt::class, 'assignment_id')->latestOfMany();
    }

    public function isWithinTimeWindow(): bool
    {
        if (!$this->time) {
            return false;
        }
        $now = now();
        return $now->between($this->time->start_at, $this->time->end_at);
    }

    public function isExpired(): bool
    {
        return $this->time && now()->gt($this->time->end_at);
    }
}

