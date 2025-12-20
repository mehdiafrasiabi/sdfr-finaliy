<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProgramPart extends Model

{
    use SoftDeletes;


    protected $guarded = [];


    protected $casts = [
        'grade' => 'string',
        'part_date' => 'date',

    ];


    const PART_TYPE_TEST = 'test';

    const PART_TYPE_DESCRIPTIVE = 'descriptive';

    const PART_TYPE_VIDEO = 'video';


    const LESSON_TYPE_GENERAL = 'general';

    const LESSON_TYPE_SPECIALIZED = 'specialized';


    public function weeklyProgram(): BelongsTo

    {

        return $this->belongsTo(WeeklyProgram::class);

    }


    public function lesson(): BelongsTo

    {

        return $this->belongsTo(Lesson::class);

    }


    // نمایش نوع پارت فارسی

    public function getPartTypeLabelAttribute(): string

    {

        return match ($this->part_type) {

            self::PART_TYPE_TEST => 'تستی',

            self::PART_TYPE_DESCRIPTIVE => 'تشریحی',

            self::PART_TYPE_VIDEO => 'ویدئو',

            default => 'نامشخص',

        };

    }


    // نمایش نوع درس فارسی

    public function getLessonTypeLabelAttribute(): string

    {

        return match ($this->lesson_type) {

            self::LESSON_TYPE_GENERAL => 'عمومی',

            self::LESSON_TYPE_SPECIALIZED => 'تخصصی',

            default => 'نامشخص',

        };

    }


    // نمایش پایه فارسی

    public function getGradeLabelAttribute(): string

    {

        return match ($this->grade) {

            '10' => 'دهم',

            '11' => 'یازدهم',

            '12' => 'دوازدهم',

            default => '-',

        };

    }


    // نمایش روز هفته فارسی

    public function getDayNameAttribute(): string

    {

        $dayNames = ['شنبه', '۱شنبه', '۲شنبه', '۳شنبه', '۴شنبه', '۵شنبه', 'جمعه'];

        return $dayNames[$this->day_of_week] ?? '-';

    }


    // تبدیل دقیقه به ساعت

    public function getDurationHoursAttribute(): float

    {

        return round($this->duration_minutes / 60, 1);

    }


    public function educationLevel(): BelongsTo


    {


        return $this->belongsTo(EducationLevel::class);


    }


    public function ccGrade(): BelongsTo


    {


        return $this->belongsTo(CcGrade::class);


    }


    public function ccField(): BelongsTo


    {


        return $this->belongsTo(CcField::class);


    }


    public function ccSubject(): BelongsTo


    {


        return $this->belongsTo(CcSubject::class);


    }


    public function ccChapter(): BelongsTo


    {


        return $this->belongsTo(CcChapter::class);


    }


    public function ccTopic(): BelongsTo


    {


        return $this->belongsTo(CcTopic::class);


    }
}
