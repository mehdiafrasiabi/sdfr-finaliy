<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id');
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id');
    }

    public const PURPOSE_COURSE_FULL        = 'course_full';
    public const PURPOSE_INSTALLMENT_INITIAL = 'installment_initial';
    public const PURPOSE_INSTALLMENT        = 'installment';
}
