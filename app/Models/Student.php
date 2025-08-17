<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];
    public function payment()
    {
        return $this->belongsTo(Payment::class)->where('status', '=','completed');
    }
    public function examAttempts()
    {
        return $this->hasMany(ExamAttemp::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInformation()
    {
        return $this->user->personalInformation(); // غیر مستقیم
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }
    public function barnamehs()
    {
        return $this->hasMany(\App\Models\Barnameh::class);
    }
    public function reportMonthlies()
    {
        return $this->hasMany(\App\Models\ReportMonthly::class);
    }
    public function reportdaily()
    {
        return $this->hasMany(\App\Models\Report::class);
    }
    public function exams()
    {
        return $this->belongsToMany(Exam::class);
    }

    public function studySessions()
    {
        return $this->hasMany(StudySession::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function supporterStudent()
    {
        return $this->belongsTo(Admin::class, 'supporter_id');
    }

    public function advisor()
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
    }
}
