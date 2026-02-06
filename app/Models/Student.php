<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $guarded = [];

    public function payment()
    {
        return $this->belongsTo(Payment::class)->where('status', '=', 'completed');
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // حذف تابع اشتباه personalInformation()
    // و ساخت Accessor امن:

    public function getPersonalInfoAttribute()
    {
        return $this->user?->personalInformation;
    }

    public function reportMonthlies()
    {
        return $this->hasMany(\App\Models\ReportMonthly::class);
    }

    public function reportdaily()
    {
        return $this->hasMany(\App\Models\Report::class);
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

    public function supporter()
    {
        return $this->belongsTo(Admin::class, 'supporter_id');
    }

    public function advisingSessions()
    {
        return $this->hasMany(AdvisingSession::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
    public function weeklyPrograms(): HasMany
    {
        return $this->hasMany(WeeklyProgram::class);
    }
    public function studyPartSessions()
    {
        return $this->hasMany(StudyPartSession::class);
    }
    public function makeupSessions()
    {
        return $this->hasMany(MakeupSession::class);
    }
    public function sessionFeedbacks()
    {
        return $this->hasMany(SessionFeedback::class);
    }
}
