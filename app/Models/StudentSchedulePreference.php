<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentSchedulePreference extends Model
{
    protected $fillable = [
        'student_id',
        'assigned_advisor_id',
        'status',
        'year_period',
        'change_index',
        'student_notes',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REPLACED = 'replaced';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedAdvisor(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_advisor_id');
    }

    public function times(): HasMany
    {
        return $this->hasMany(StudentSchedulePreferenceTime::class);
    }

    /**
     * آیا این ترجیح، اسلات روز/ساعت داده شده را پوشش می‌دهد؟
     */
    public function coversSlot(int $dayOfWeek, string $time): bool
    {
        return $this->times->contains(function (StudentSchedulePreferenceTime $t) use ($dayOfWeek, $time) {
            return $t->day_of_week === $dayOfWeek
                && $time >= substr($t->start_time, 0, 5)
                && $time <  substr($t->end_time, 0, 5);
        });
    }
}

