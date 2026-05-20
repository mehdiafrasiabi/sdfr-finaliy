<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolStudentGrade extends Model
{
    protected $guarded = [];

    protected $casts = [
        'score'       => 'decimal:2',
        'recorded_at' => 'date',
    ];

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
