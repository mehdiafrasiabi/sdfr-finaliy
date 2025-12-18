<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPeriod extends Model
{
    use HasFactory;


    protected $guarded = [];


    protected $casts = [

        'is_active' => 'boolean',

    ];


    /**
     * دوره‌های فعال
     */

    public function scopeActive($query)

    {

        return $query->where('is_active', true);

    }


    /**
     * ترتیب بر اساس order
     */

    public function scopeOrdered($query)

    {

        return $query->orderBy('order');

    }
}
