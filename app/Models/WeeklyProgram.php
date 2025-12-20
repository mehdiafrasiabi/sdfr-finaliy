<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class WeeklyProgram extends Model
{

    use SoftDeletes;
    protected $guarded = [];


    protected $casts = [

        'start_date' => 'date',

        'end_date' => 'date',

        'is_active' => 'boolean',

    ];


    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }


    public function advisor(): BelongsTo

    {

        return $this->belongsTo(Admin::class, 'advisor_id');

    }


    public function advisingSession(): BelongsTo

    {

        return $this->belongsTo(AdvisingSession::class);

    }


    public function parts(): HasMany

    {

        return $this->hasMany(ProgramPart::class);

    }


    // پارت‌های یک روز خاص

    public function partsForDay($dayOfWeek): HasMany

    {

        return $this->parts()->where('day_of_week', $dayOfWeek)->orderBy('part_order');

    }


    // پارت‌های یک تاریخ خاص

    public function partsForDate($date): HasMany

    {

        return $this->parts()->whereDate('part_date', $date)->orderBy('part_order');

    }


    // محاسبه کل ساعت مطالعه هفته

    public function getTotalHoursAttribute(): float

    {

        $totalMinutes = $this->parts()->sum('duration_minutes');

        return round($totalMinutes / 60, 1);

    }


    // محاسبه کل تعداد تست هفته

    public function getTotalTestsAttribute(): int

    {

        return $this->parts()->sum('test_count') ?? 0;

    }


    // محاسبه تعداد پارت‌ها

    public function getTotalPartsAttribute(): int

    {

        return $this->parts()->count();

    }


    // محاسبه تعداد پلان‌های درسی

    public function getTotalPlansAttribute(): int

    {

        return $this->parts()->count();

    }


    // تعداد پارت‌های تستی

    public function getTestPartsCountAttribute(): int

    {

        return $this->parts()->where('part_type', 'test')->count();

    }


    // تعداد پارت‌های تشریحی

    public function getDescriptivePartsCountAttribute(): int

    {

        return $this->parts()->where('part_type', 'descriptive')->count();

    }


    // تعداد پارت‌های ویدئویی

    public function getVideoPartsCountAttribute(): int

    {

        return $this->parts()->where('part_type', 'video')->count();

    }


    // تعداد پارت‌های عمومی

    public function getGeneralPartsCountAttribute(): int

    {

        return $this->parts()->where('lesson_type', 'general')->count();

    }


    // تعداد پارت‌های تخصصی

    public function getSpecializedPartsCountAttribute(): int

    {

        return $this->parts()->where('lesson_type', 'specialized')->count();

    }


    // تعداد پارت‌های هر پایه

    public function getGrade10PartsCountAttribute(): int

    {

        return $this->parts()->where('grade', '10')->count();

    }


    public function getGrade11PartsCountAttribute(): int

    {

        return $this->parts()->where('grade', '11')->count();

    }


    public function getGrade12PartsCountAttribute(): int

    {

        return $this->parts()->where('grade', '12')->count();

    }


    // ساعت مطالعه روزانه

    public function getDailyHours($dayOfWeek): float

    {

        $totalMinutes = $this->parts()->where('day_of_week', $dayOfWeek)->sum('duration_minutes');

        return round($totalMinutes / 60, 1);

    }


    // تعداد تست روزانه

    public function getDailyTests($dayOfWeek): int

    {

        return $this->parts()->where('day_of_week', $dayOfWeek)->sum('test_count') ?? 0;

    }


    // آرایه روزهای هفته با تاریخ شمسی

    public function getWeekDays(): array

    {

        $days = [];

        $dayNames = ['شنبه', '۱شنبه', '۲شنبه', '۳شنبه', '۴شنبه', '۵شنبه', 'جمعه'];


        for ($i = 0; $i < 7; $i++) {

            $date = Carbon::parse($this->start_date)->addDays($i);

            $days[] = [

                'day_of_week' => $i,

                'name' => $dayNames[$i],

                'date' => $date,

                'jalali_date' => jdate($date)->format('m/d'),

                'parts' => $this->parts()->where('day_of_week', $i)->orderBy('part_order')->get(),

                'total_hours' => $this->getDailyHours($i),

                'total_tests' => $this->getDailyTests($i),

            ];

        }


        return $days;

    }


    // آمار نمودار نوع پارت (تستی/تشریحی)

    public function getPartTypeStats(): array

    {

        $total = $this->total_parts;

        if ($total === 0) return ['test' => 0, 'descriptive' => 0, 'video' => 0];


        return [

            'test' => round(($this->test_parts_count / $total) * 100),

            'descriptive' => round(($this->descriptive_parts_count / $total) * 100),

            'video' => round(($this->video_parts_count / $total) * 100),

        ];

    }


    // آمار نمودار نوع درس (عمومی/تخصصی)

    public function getLessonTypeStats(): array

    {

        $total = $this->total_parts;

        if ($total === 0) return ['general' => 0, 'specialized' => 0];


        return [

            'general' => round(($this->general_parts_count / $total) * 100),

            'specialized' => round(($this->specialized_parts_count / $total) * 100),

        ];

    }


    // آمار نمودار پایه

    public function getGradeStats(): array

    {

        $total = $this->total_parts;

        if ($total === 0) return ['10' => 0, '11' => 0, '12' => 0];


        return [

            '10' => round(($this->grade_10_parts_count / $total) * 100),

            '11' => round(($this->grade_11_parts_count / $total) * 100),

            '12' => round(($this->grade_12_parts_count / $total) * 100),

        ];

    }

}
