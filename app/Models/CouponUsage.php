<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = ['coupon_id', 'user_id', 'used_at'];

    public function coupon()
    {
        return $this->belongsTo(Coupons::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
