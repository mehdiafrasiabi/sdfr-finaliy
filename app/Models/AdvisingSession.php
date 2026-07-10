<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;


class AdvisingSession extends Model

{

    use SoftDeletes;

    use HasFactory;
    protected $guarded = [];


    protected $casts = [

        'activation_date' => 'date',

        'session_time' => 'datetime:H:i',

        'is_active' => 'boolean',

        'finalized' => 'boolean',

        'is_makeup' => 'boolean',

    ];

    // علتِ جلسه‌ی جبرانی
    const MAKEUP_STUDENT_RESCHEDULE = 'student_reschedule';
    const MAKEUP_ADVISOR_LEAVE      = 'advisor_leave';
    const MAKEUP_STUDENT_ABSENCE    = 'student_absence';


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
        // جلسه‌ی جبرانیِ بدونِ تاریخ (در انتظارِ تعیینِ روز توسط مشاور) هرگز فعال نمی‌شود.
        if (! $this->activation_date) {
            return false;
        }
        // session_time is cast to datetime, so we need to format it properly
        $timeString = $this->session_time ? $this->session_time->format('H:i:s') : '00:00:00';
        $sessionDateTime = Carbon::parse($this->activation_date)->setTimeFromTimeString($timeString);
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

    /** جلسه‌ی منبعِ این جلسه‌ی جبرانی (جلسه‌ای که جابجا/لغو شده). */
    public function sourceSession()
    {
        return $this->belongsTo(AdvisingSession::class, 'source_session_id');
    }

    public function getMakeupReasonLabelAttribute(): string
    {
        return match ($this->makeup_reason) {
            self::MAKEUP_STUDENT_RESCHEDULE => 'جابجایی توسط دانش‌آموز',
            self::MAKEUP_ADVISOR_LEAVE      => 'مرخصی مشاور',
            self::MAKEUP_STUDENT_ABSENCE    => 'غیبت دانش‌آموز',
            default                          => '',
        };
    }

    // بررسی امکان پر کردن پیش‌جلسه
    public function canFillPreSession(): bool
    {
        if (! $this->activation_date) {
            return false;
        }
        $timeString = $this->session_time ? $this->session_time->format('H:i:s') : '00:00:00';

        $sessionDateTime = Carbon::parse($this->activation_date)->setTimeFromTimeString($timeString);

        return Carbon::now()->lt($sessionDateTime);
    }
}
