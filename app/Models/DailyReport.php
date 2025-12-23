<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;


class DailyReport extends Model

{

    protected $guarded = [];


    protected $casts = [

        'report_date' => 'date',

        'advisor_commented_at' => 'datetime',

        'student_replied_at' => 'datetime',

        'is_compensatory' => 'boolean',

    ];


    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';


    const RATINGS = [

        5 => 'عالی',

        4 => 'خیلی خوب',

        3 => 'خوب',

        2 => 'قابل قبول',

        1 => 'نیاز به تلاش بیشتری دارم',

    ];


    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }


    public function admin(): BelongsTo

    {

        return $this->belongsTo(Admin::class);

    }


    public function advisingSession(): BelongsTo

    {

        return $this->belongsTo(AdvisingSession::class, 'session_id');

    }


    public function weeklyProgram(): BelongsTo

    {

        return $this->belongsTo(WeeklyProgram::class);

    }


    public function reportParts(): HasMany

    {

        return $this->hasMany(DailyReportPart::class);

    }


    public function getStatusLabelAttribute(): string

    {

        return match ($this->status) {

            self::STATUS_PENDING => 'در انتظار',

            self::STATUS_APPROVED => 'تایید شده',

            self::STATUS_REJECTED => 'رد شده',

            default => 'نامشخص',

        };

    }


    public function getRatingLabelAttribute(): string

    {

        return self::RATINGS[$this->rating] ?? 'نامشخص';

    }


    public function getDayNameAttribute(): string

    {

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        return $dayNames[$this->day_of_week] ?? '-';

    }


    public function getTotalPartsAttribute(): int

    {

        return $this->reportParts()->count();

    }


    public function getReadPartsCountAttribute(): int

    {

        return $this->reportParts()->where('is_read', true)->count();

    }


    public function getUnreadPartsCountAttribute(): int

    {

        return $this->reportParts()->where('is_read', false)->count();

    }


    public function getTotalTestsAttribute(): int

    {

        return $this->reportParts()->sum('tests_done');

    }

}
