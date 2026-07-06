<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;


    protected $guarded = [];
    protected $connection = 'mysql';
    protected $guard_name = 'admin';


    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // اگر admin → user_id دارد
    }

    /**
     * دانش‌آموزانی که این ادمین به عنوان «مشاور تحصیلی» مسئول آن‌هاست.
     */
    public function advisedStudents()
    {
        return $this->hasMany(Student::class, 'advisor_id');
    }

    /**
     * مکالمه‌های مستقیمی که این ادمین به‌عنوان مشاور در آن‌ها حضور دارد.
     */
    public function advisedConversations()
    {
        return $this->hasMany(Conversation::class, 'advisor_id');
    }

    /**
     * انتخاب‌هایی که دانش‌آموزان این مشاور را به‌عنوان مشاورِ خود برگزیده‌اند.
     */
    public function advisorSelections()
    {
        return $this->hasMany(AdvisorSelection::class, 'advisor_id');
    }

    /**
     * ظرفیتِ مؤثرِ پذیرشِ دانش‌آموز: ظرفیتِ اختصاصی در صورت وجود، وگرنه مقدارِ سراسری.
     */
    public function effectiveCapacity(): int
    {
        if ($this->student_capacity !== null) {
            return (int) $this->student_capacity;
        }

        return (int) (GeneralSetting::query()->value('advisor_default_capacity') ?? 50);
    }

    /**
     * آیا ظرفیتِ مشاور تکمیل است؟ (تعدادِ دانش‌آموزانِ تاییدشده ≥ ظرفیت)
     * در صورت بارگذاریِ withCount('advisedStudents') از همان مقدار استفاده می‌شود.
     */
    public function isAtCapacity(): bool
    {
        $count = $this->advised_students_count ?? $this->advisedStudents()->count();

        return $count >= $this->effectiveCapacity();
    }

    /**
     * نشانیِ عکسِ مشاور (در public_html/adminsFile/{id}/...) یا null.
     */
    public function getPictureUrlAttribute(): ?string
    {
        return $this->picture
            ? asset("adminsFile/{$this->id}/{$this->picture}")
            : null;
    }

    /**
     * هفته‌های آزمایشی که این ادمین به عنوان «پشتیبان جذب» به آن‌ها اختصاص داده شده است.
     */
    public function acquisitionTrialWeeks()
    {
        return $this->hasMany(TrialWeek::class, 'acquisition_supporter_id');
    }

    /**
     * شماره‌های جذب تلفنی که به این ادمین (مشاور جذب تلفنی) اختصاص یافته است.
     */
    public function phoneLeadAssignments()
    {
        return $this->hasMany(PhoneLeadAssignment::class, 'admin_id');
    }

    /**
     * تماس‌های جذب تلفنی که این ادمین ثبت کرده است.
     */
    public function phoneCalls()
    {
        return $this->hasMany(PhoneCall::class, 'admin_id');
    }

    /**
     * هدف‌گذاری‌های ثبت‌نام جذب تلفنی مخصوص این ادمین.
     */
    public function registrationGoals()
    {
        return $this->hasMany(RegistrationGoal::class, 'admin_id');
    }

    /**
     * رسیدهای شارژ ارسال‌شده توسط این ادمین.
     */
    public function chargeReceipts()
    {
        return $this->hasMany(ChargeReceipt::class, 'admin_id');
    }

    /**
     * شماره‌های جذب تلفنی که این ادمین (مدیر آموزشی) ثبت کرده است.
     */
    public function createdPhoneLeads()
    {
        return $this->hasMany(PhoneLead::class, 'created_by');
    }

    public function commentReplies()
    {
        return $this->hasMany(CommentReply::class);
    }

    public function workSchedules()
    {
        return $this->hasMany(AdminWorkSchedule::class);
    }

    /**
     * مدرسه‌ای که این ادمین به‌عنوان «مدیر مدرسه» (نقش school-manager) آن را مدیریت می‌کند.
     */
    public function managedSchool()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * مدارسی که این ادمین به‌عنوان «مشاور تحصیلی» در آن‌ها فعال شده است.
     */
    public function advisorSchools()
    {
        return $this->belongsToMany(School::class, 'school_advisor')->withTimestamps();
    }

    /**
     * آیا ادمین در یک روز و ساعت مشخص در ساعت کاری است؟
     *
     * @param  int    $dayOfWeek 0=شنبه .. 6=جمعه
     * @param  string $time       HH:MM
     */
    public function isWorkingAt(int $dayOfWeek, string $time): bool
    {
        return $this->workSchedules
            ->contains(fn (AdminWorkSchedule $s) => $s->coversTime($dayOfWeek, $time));
    }
}

