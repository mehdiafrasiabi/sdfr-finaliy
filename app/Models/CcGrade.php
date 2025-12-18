<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CcGrade extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    // Field type constants
    const FIELD_TYPE_NONE = 'A';       // بدون رشته
    const FIELD_TYPE_MATH = 'B';       // ریاضی
    const FIELD_TYPE_EXPERIMENTAL = 'C'; // تجربی
    const FIELD_TYPE_HUMAN = 'D';      // انسانی

    public static function getFieldTypes(): array
    {
        return [
            self::FIELD_TYPE_NONE => 'بدون رشته',

            self::FIELD_TYPE_MATH => 'ریاضی',

            self::FIELD_TYPE_EXPERIMENTAL => 'تجربی',

            self::FIELD_TYPE_HUMAN => 'انسانی',
        ];
    }

    public function getFieldTypeLabelAttribute(): string

    {

        return self::getFieldTypes()[$this->field_type] ?? 'نامشخص';

    }


    public function educationLevel()

    {

        return $this->belongsTo(EducationLevel::class);

    }

    public function field()

    {
        return $this->belongsTo(CcField::class, 'cc_field_id');
    }
    public function hasField(): bool
    {
        return $this->cc_field_id !== null;

    }

    public function subjects()

    {

        return $this->hasMany(CcSubject::class);

    }


    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }


    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }


    public function getFullNameAttribute()

    {

        return $this->educationLevel->name . ' - ' . $this->name;

    }
}
