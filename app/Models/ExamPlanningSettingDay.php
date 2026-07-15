<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamPlanningSettingDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(ExamPlanningSetting::class, 'exam_planning_setting_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }
}
