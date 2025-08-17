<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barnameh extends Model
{
    use HasFactory,softDeletes;
    protected $guarded = [];

    public function reportMonthly()
    {
        return $this->belongsTo(ReportMonthly::class);
    }
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class);
    }
    public function views()
    {
        return $this->hasMany(BarnamehView::class);
    }

}
