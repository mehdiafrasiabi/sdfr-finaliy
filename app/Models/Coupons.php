<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
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
