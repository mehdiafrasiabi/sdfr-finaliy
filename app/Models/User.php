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

    public function orders()
    {
        return $this->hasMany(Order::class);

    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function supportedStudents()
    {
        // این رابطه همه دانش‌آموزانی را که این کاربر (با role 'admin' یا 'supporter') پشتیبان آن‌هاست، برمی‌گرداند.
        return $this->hasMany(Student::class, 'supporter_id');
    }

    public function advisor()
    {
        return $this->belongsTo(Admin::class, 'advisor_id');
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
}
