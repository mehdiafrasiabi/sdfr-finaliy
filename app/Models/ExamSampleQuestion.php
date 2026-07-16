<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSampleQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'duration_minutes' => 'integer',
        'is_main' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(ExamPlanningSetting::class, 'exam_planning_setting_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(CcSubject::class, 'cc_subject_id');
    }

    public function getDownloadUrlAttribute(): string
    {
        return FileHelper::publicUrl($this->pdf_path);
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}
