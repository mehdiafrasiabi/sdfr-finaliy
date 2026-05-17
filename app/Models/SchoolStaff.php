<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolStaff extends Model
{
    protected $table = 'school_staff';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
