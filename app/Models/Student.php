<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['is_trial' => 'boolean'];

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

    public function advisor()
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
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
    public function classSchedule()
    {
        return $this->hasOne(ClassSchedule::class)->latest();
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function schedulePreferences(): HasMany
    {
        return $this->hasMany(StudentSchedulePreference::class);
    }

    /**
     * ترجیح برنامه‌ی فعالِ جاری دانش‌آموز (تاییدشده توسط مدیر آموزشی).
     */
    public function activeSchedulePreference()
    {
        return $this->hasOne(StudentSchedulePreference::class)
            ->where('status', StudentSchedulePreference::STATUS_APPROVED)
            ->latest('approved_at');
    }

    public function rescheduleRequests(): HasMany
    {
        return $this->hasMany(SessionRescheduleRequest::class);
    }

    /**
     * تعداد تغییرات برنامه‌ی هفتگی در سال میلادی جاری.
     */
    public function scheduleChangesThisYear(): int
    {
        return $this->schedulePreferences()
            ->where('year_period', (int) now()->year)
            ->where('change_index', '>', 0)
            ->count();
    }

    public function trialWeek()
    {
        return $this->hasOne(\App\Models\TrialWeek::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolSupporter()
    {
        return $this->belongsTo(Admin::class, 'school_supporter_id');
    }
}
