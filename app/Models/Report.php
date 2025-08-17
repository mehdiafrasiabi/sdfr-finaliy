<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded=[];
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
}
