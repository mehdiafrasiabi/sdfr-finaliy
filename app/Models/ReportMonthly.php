<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ReportMonthly extends Model
{
    use HasFactory,softDeletes;
    protected $guarded = [];
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
}
