<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupons extends Model
{
    use HasFactory;

    protected $guarded =[];
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
