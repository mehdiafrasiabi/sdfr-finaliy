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
        ];
    }

    public function usedCoupons()
    {
        return $this->hasMany(CouponUsage::class);
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

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function supportedStudents()
    {
        // این رابطه همه دانش‌آموزانی را که این کاربر (با role 'admin' یا 'supporter') پشتیبان آن‌هاست، برمی‌گرداند.
        return $this->hasMany(Student::class, 'supporter_id');
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



    public function getWalletBalanceAttribute()

    {

        return $this->wallet?->balance ?? 0;

    }
}
