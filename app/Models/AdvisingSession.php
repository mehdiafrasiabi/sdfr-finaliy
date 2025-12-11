<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Carbon\Carbon;


class AdvisingSession extends Model

{

    protected $guarded = [];


    protected $casts = [

        'activation_date' => 'date',

        'session_time' => 'datetime:H:i',

        'is_active' => 'boolean',

    ];


    // وضعیت‌های جلسه

    const STATUS_INACTIVE = 'inactive';

    const STATUS_ACTIVE = 'active';

    const STATUS_COMPLETED = 'completed';


    // محل برگزاری

    const LOCATION_IN_PERSON = 'in_person';

    const LOCATION_ONLINE = 'online';


    // نتیجه جلسه

    const RESULT_HELD = 'held';

    const RESULT_ADVISOR_ABSENT = 'advisor_absent';

    const RESULT_STUDENT_ABSENT = 'student_absent';

    public function preSession(): HasOne
    {
        return $this->hasOne(AdvisingPreSession::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);

    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function advisor()
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }

    public function weeklyProgram(): HasOne
    {
        return $this->hasOne(WeeklyProgram::class);
    }

    // محاسبه فعال بودن خودکار
    public function shouldBeActive(): bool
    {
        $sessionDateTime = Carbon::parse($this->activation_date)
            ->setTimeFromTimeString($this->session_time ?? '00:00:00');
        return Carbon::now()->gte($sessionDateTime) && $this->status !== self::STATUS_COMPLETED;
    }

    // متد برای فعال‌سازی خودکار
    public function activateIfNeeded(): void
    {
        if ($this->shouldBeActive() && !$this->is_active) {
            $this->update([

                'is_active' => true,
                'status' => self::STATUS_ACTIVE
            ]);
        }

    }


    // نمایش وضعیت فارسی

    public function getStatusLabelAttribute(): string

    {

        return match ($this->status) {

            self::STATUS_INACTIVE => 'مانده به برگزاری',

            self::STATUS_ACTIVE => 'در حال برگزاری',

            self::STATUS_COMPLETED => 'برگزار شده',

            default => 'نامشخص',

        };

    }

    // نمایش محل برگزاری فارسی
    public function getLocationLabelAttribute(): string
    {
        return match ($this->location_type) {
            self::LOCATION_IN_PERSON => 'حضوری',
            self::LOCATION_ONLINE => 'آنلاین',
            default => 'نامشخص',
        };
    }

    // نمایش نتیجه جلسه فارسی
    public function getResultLabelAttribute(): string
    {
        return match ($this->result_status) {
            self::RESULT_HELD => 'جلسه مشاوره برگزار شد',
            self::RESULT_ADVISOR_ABSENT => 'جلسه مشاوره توسط مشاور برگزار نشد',
            self::RESULT_STUDENT_ABSENT => 'دانش‌آموز غیبت داشت',
            default => 'ثبت نشده',
        };
    }

    // بررسی امکان پر کردن پیش‌جلسه
    public function canFillPreSession(): bool
    {
        $sessionDateTime = Carbon::parse($this->activation_date)
            ->setTimeFromTimeString($this->session_time ?? '00:00:00');

        return Carbon::now()->lt($sessionDateTime);
    }
}
