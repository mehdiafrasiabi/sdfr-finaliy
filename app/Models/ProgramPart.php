<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProgramPart extends Model

{

    protected $guarded = [];


    protected $casts = [

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


    // رنگ بر اساس درس (برای UI)

    public function getColorClassAttribute(): string

    {

        $colors = [

            'شیمی' => 'from-green-100 to-green-200 text-green-800 border-green-300',

            'فیزیک' => 'from-blue-100 to-blue-200 text-blue-800 border-blue-300',

            'ریاضی' => 'from-purple-100 to-purple-200 text-purple-800 border-purple-300',

            'هندسه' => 'from-orange-100 to-orange-200 text-orange-800 border-orange-300',

            'زیست' => 'from-teal-100 to-teal-200 text-teal-800 border-teal-300',

            'ادبیات' => 'from-red-100 to-red-200 text-red-800 border-red-300',

            'عربی' => 'from-yellow-100 to-yellow-200 text-yellow-800 border-yellow-300',

            'دین' => 'from-indigo-100 to-indigo-200 text-indigo-800 border-indigo-300',

            'زبان' => 'from-pink-100 to-pink-200 text-pink-800 border-pink-300',

        ];


        foreach ($colors as $key => $class) {

            if (str_contains($this->lesson_name, $key)) {

                return $class;

            }

        }


        return 'from-gray-100 to-gray-200 text-gray-800 border-gray-300';

    }

}
