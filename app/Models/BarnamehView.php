<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarnamehView extends Model
{
    protected $fillable = ['barnameh_id', 'student_id'];

    public function barnameh()
    {
        return $this->belongsTo(Barnameh::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}
