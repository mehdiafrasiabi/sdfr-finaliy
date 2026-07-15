<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'picture',
        'password',
        'panel_closed',
        'panel_closed_message',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'panel_closed' => 'boolean',
        ];
    }

    public function usedCoupons()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);

    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * اعلان‌های دریافتی این کاربر
     */

    public function notificationRecipients()

    {

        return $this->hasMany(NotificationRecipient::class);

    }


    /**
     * اعلان‌های این کاربر
     */

    public function notifications()

    {

        return $this->belongsToMany(Notification::class, 'notification_recipients')
            ->withPivot(['is_read', 'read_at'])
            ->withTimestamps();

    }


    /**
     * تعداد اعلان‌های خوانده‌نشده
     */

    public function getUnreadNotificationsCountAttribute(): int

    {

        return $this->notificationRecipients()->where('is_read', false)->count();

    }


    /**
     * تعداد اعلان‌های خوانده‌نشده بر اساس دسته‌بندی
     */

    public function getUnreadNotificationsCountByCategory(string $category): int

    {

        return $this->notificationRecipients()
            ->whereHas('notification', function ($query) use ($category) {

                $query->where('category', $category);

            })
            ->where('is_read', false)
            ->count();

    }


    /**
     * آیا کاربر دانش‌آموز است؟
     */
    public function isStudent(): bool
    {
        return $this->student !== null;
    }

    /**
     * آیا این کاربر دانش‌آموز ثبت‌شده توسط یک مدرسه است؟
     */
    public function isSchoolStudent(): bool
    {
        return $this->student && $this->student->school_id !== null;
    }
    public function productComments()
    {
        return $this->hasMany(ProductComment::class);
    }
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }
    public function wallet()

    {

        return $this->hasOne(Wallet::class);

    }



    public function walletTransactions()

    {

        return $this->hasMany(WalletTransaction::class);

    }



    public function giftCodeUsages()

    {

        return $this->hasMany(GiftCodeUsage::class);

    }



    public function getOrCreateWallet()

    {

        return $this->wallet ?? $this->wallet()->create(['balance' => 0]);

    }

    public function trialWeek()
    {
        return $this->hasOne(TrialWeek::class);
    }

    public function examSchedules()
    {
        return $this->hasMany(StudentExamSchedule::class);
    }

    public function assessmentAttempts()
    {
        return $this->hasMany(StudentAssessmentAttempt::class);
    }

    public function hasCompletedAllAssessments(): bool
    {
        $required = Assessment::active()->forStudent()->count();
        if ($required === 0) {
            return true;
        }
        $completed = $this->assessmentAttempts()
            ->whereHas('assessment', function ($q) {
                $q->where('is_active', true)->where('audience', Assessment::AUDIENCE_STUDENT);
            })
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->count();
        return $completed >= $required;
    }

    public function parentAssessmentInvitations()
    {
        return $this->hasMany(ParentAssessmentInvitation::class);
    }



    public function getWalletBalanceAttribute()

    {

        return $this->wallet?->balance ?? 0;

    }
}
