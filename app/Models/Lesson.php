<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;


class Lesson extends Model

{

    protected $guarded = [];


    protected $casts = [

        'is_active' => 'boolean',

    ];


    const TYPE_GENERAL = 'general';

    const TYPE_SPECIALIZED = 'specialized';


    const GRADE_10 = '10';

    const GRADE_11 = '11';

    const GRADE_12 = '12';


    const FIELD_MATH = 'math';

    const FIELD_EXPERIMENTAL = 'experimental';

    const FIELD_HUMAN = 'human';


    public function programParts(): HasMany

    {

        return $this->hasMany(ProgramPart::class);

    }


    // نمایش نوع فارسی

    public function getTypeLabelAttribute(): string

    {

        return match ($this->type) {

            self::TYPE_GENERAL => 'عمومی',

            self::TYPE_SPECIALIZED => 'تخصصی',

            default => 'نامشخص',

        };

    }


    // نمایش پایه فارسی

    public function getGradeLabelAttribute(): string

    {

        return match ($this->grade) {

            self::GRADE_10 => 'دهم',

            self::GRADE_11 => 'یازدهم',

            self::GRADE_12 => 'دوازدهم',

            default => '-',

        };

    }


    // نمایش رشته فارسی

    public function getFieldLabelAttribute(): string

    {

        return match ($this->field) {

            self::FIELD_MATH => 'ریاضی',

            self::FIELD_EXPERIMENTAL => 'تجربی',

            self::FIELD_HUMAN => 'انسانی',

            default => '-',

        };

    }


    // Scope برای فیلتر دروس فعال

    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }


    // Scope برای فیلتر بر اساس نوع

    public function scopeOfType($query, $type)

    {

        return $query->where('type', $type);

    }


    // Scope برای فیلتر بر اساس پایه

    public function scopeOfGrade($query, $grade)

    {

        return $query->where('grade', $grade);

    }


    // Scope برای فیلتر بر اساس رشته

    public function scopeOfField($query, $field)

    {

        return $query->where('field', $field);

    }

}

