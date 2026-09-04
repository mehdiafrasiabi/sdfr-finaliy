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

    protected $casts = [
        'is_trial'       => 'boolean',
        'access_ends_at' => 'datetime',
    ];

    /**
     * آیا دسترسی پرداختی فعال است؟ = پرداخت موفق دارد و دسترسی منقضی نشده.
     * نکته: اگر access_ends_at تهی باشد (دانش‌آموزان قدیمی، پیش از این قابلیت)
     * دسترسی نامحدود تلقی می‌شود تا قفل نشوند؛ انقضا فقط وقتی اعمال می‌شود که
     * تاریخ پایان صریحاً ثبت شده و گذشته باشد.
     */
    public function hasActivePaidAccess(): bool
    {
        $hasPaid = \App\Models\Payment::where('user_id', $this->user_id)
            ->where('status', 'completed')
            ->exists();

        return $hasPaid && ! $this->accessExpired();
    }

    public function accessExpired(): bool
    {
        return $this->access_ends_at !== null && $this->access_ends_at->isPast();
    }

    /** A historical TrialWeek row must not make a paid student trial-only again. */
    public function hasActiveTrialAccess(): bool
    {
        $trial = $this->trialWeek;

        return (bool) ($this->is_trial
            && $trial
            && (! $trial->expires_at || $trial->expires_at->isFuture()));
    }

    public function isAdvisorChatLocked(): bool
    {
        $trial = $this->trialWeek;

        return (bool) ($this->is_trial && $trial && ! $trial->hasFullAccess());
    }

    public function installmentPlans(): HasMany
    {
        return $this->hasMany(InstallmentPlan::class);
    }

    /** طرح اقساطیِ فعالِ جاری (آخرین طرحِ غیرتکمیل‌شده). */
    public function activeInstallmentPlan(): ?InstallmentPlan
    {
        return $this->installmentPlans()
            ->whereIn('status', [InstallmentPlan::STATUS_ACTIVE, InstallmentPlan::STATUS_PENDING])
            ->latest('id')
            ->first();
    }

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

    /** انتخاب‌های مشاور توسط این دانش‌آموز (تاریخچه: pending/approved/rejected). */
    public function advisorSelections(): HasMany
    {
        return $this->hasMany(AdvisorSelection::class);
    }

    public function advisorChangeRequests(): HasMany
    {
        return $this->hasMany(AdvisorChangeRequest::class);
    }

    public function advisorOnboarding()
    {
        return $this->hasOne(AdvisorOnboarding::class);
    }

    /** انتخابِ معلقِ جاری (رزروِ در انتظارِ تاییدِ مدیر آموزشی). */
    public function pendingAdvisorSelection()
    {
        return $this->hasOne(AdvisorSelection::class)
            ->where('status', AdvisorSelection::STATUS_PENDING)
            ->latest('id');
    }

    /**
     * آیا این دانش‌آموز باید مشاور انتخاب کند؟
     * فقط دانش‌آموزِ خریدکرده‌ی غیرآزمایشی که هنوز مشاورِ تاییدشده ندارد.
     */
    public function needsAdvisorSelection(): bool
    {
        return $this->advisor_id === null
            && ! $this->is_trial
            && $this->hasActivePaidAccess();
    }

    /** مکالمه‌ی مستقیم این دانش‌آموز با مشاورش. */
    public function conversation()
    {
        return $this->hasOne(Conversation::class);
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

    public function trialWeek()
    {
        return $this->hasOne(\App\Models\TrialWeek::class);
    }

    public function examSchedules(): HasMany
    {
        return $this->hasMany(StudentExamSchedule::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
