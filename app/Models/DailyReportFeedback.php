<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReportFeedback extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'daily_report_feedbacks'; // مهم: نام جدول صحیح

    protected $casts = [
        'advisor_commented_at' => 'datetime',
        'student_replied_at' => 'datetime',
    ];

    public function dailyReport(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class);
    }
}
