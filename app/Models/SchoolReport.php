<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolReport extends Model
{
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $guarded = [];

    protected $casts = [
        'report_date'          => 'date',
        'advisor_commented_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parts()
    {
        return $this->hasMany(SchoolReportPart::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING  => 'در انتظار',
            self::STATUS_APPROVED => 'تأیید شده',
            self::STATUS_REJECTED => 'رد شده',
            default               => '—',
        };
    }
}
