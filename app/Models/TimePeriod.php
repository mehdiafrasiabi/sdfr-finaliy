<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class TimePeriod extends Model

{

    protected $guarded = [];


    protected $casts = [

        'is_active' => 'boolean',

        'start_date' => 'date',

        'end_date' => 'date',

    ];


    /**
     * لیبل نیمسال
     */

    public function getSemesterLabelAttribute(): string

    {

        return match ($this->semester) {

            'first' => 'نیمسال اول',

            'second' => 'نیمسال دوم',

            default => 'نامشخص',

        };

    }


    /**
     * نام کامل با سال تحصیلی
     */

    public function getFullNameAttribute(): string

    {

        return $this->name;

    }


    /**
     * فقط دوره‌های فعال
     */

    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }


    /**
     * مرتب‌سازی
     */

    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }


    /**
     * دوره جاری (بر اساس تاریخ)
     */

    public function scopeCurrent($query)

    {

        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());

    }

}
