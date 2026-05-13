<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'paid_at'               => 'datetime',
        'supporter_assigned_at' => 'datetime',
    ];

    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function ccGrade()
    {
        return $this->belongsTo(CcGrade::class);
    }

    public function gradePricing()
    {
        return $this->belongsTo(GradePricing::class);
    }

    public function supporter()
    {
        return $this->belongsTo(Admin::class, 'supporter_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupons::class);
    }

    public function payments()
    {
        return $this->hasMany(EnrollmentPayment::class);
    }
}
