<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CcSubject extends Model
{
    use HasFactory;



    protected $guarded = [];



    public function grade()

    {

        return $this->belongsTo(CcGrade::class, 'cc_grade_id');

    }



    public function field()

    {

        return $this->belongsTo(CcField::class, 'cc_field_id');

    }  public function ccField()

    {

        return $this->belongsTo(CcField::class, 'cc_field_id');

    }



    public function chapters()

    {

        return $this->hasMany(CcChapter::class);

    }



    public function topics()

    {

        return $this->hasManyThrough(CcTopic::class, CcChapter::class);

    }



    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }



    public function scopeGeneral($query)

    {

        return $query->where('type', 'general');

    }



    public function scopeSpecialized($query)

    {

        return $query->where('type', 'specialized');

    }



    public function getTypeNameAttribute()

    {

        return $this->type === 'general' ? 'عمومی' : 'تخصصی';

    }



    public function getFullNameAttribute()

    {

        $fieldName = $this->field ? $this->field->name : 'همه رشته‌ها';

        return $this->grade->name . ' - ' . $this->name . ' (' . $this->type_name . ')';

    }
}
