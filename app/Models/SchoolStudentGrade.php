<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolStudentGrade extends Model
{
    protected $guarded = [];

    protected $casts = [
        'score'          => 'decimal:2',
        'class_activity' => 'decimal:2',
        'exam'           => 'decimal:2',
        'recorded_at'    => 'date',
    ];

    /**
     * معدل درس بر اساس فرمول توافق‌شده:
     * (فعالیت کلاسی × ۱ + امتحان × ۳) ÷ ۴ — هر دو از ۲۰.
     * اگر یکی خالی باشد، فقط از همان موجود استفاده می‌شود.
     */
    public function getSubjectAverageAttribute(): ?float
    {
        $activity = $this->class_activity !== null ? (float) $this->class_activity : null;
        $exam     = $this->exam !== null ? (float) $this->exam : null;

        if ($activity === null && $exam === null) {
            return null;
        }
        if ($activity === null) {
            return round($exam, 2);
        }
        if ($exam === null) {
            return round($activity, 2);
        }

        return round(($activity * 1 + $exam * 3) / 4, 2);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }

    public function chapter()
    {
        return $this->belongsTo(CcChapter::class, 'cc_chapter_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(Admin::class, 'recorded_by_admin_id');
    }
}
