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
     * هفته‌های آزمایشی که این ادمین به عنوان «پشتیبان جذب» به آن‌ها اختصاص داده شده است.
     */
    public function acquisitionTrialWeeks()
    {
        return $this->hasMany(TrialWeek::class, 'acquisition_supporter_id');
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
     * مدارسی که این ادمین به‌عنوان «پشتیبان مدرسه» به آن‌ها تخصیص داده شده است.
     */
    public function supportedSchools()
    {
        return $this->belongsToMany(School::class, 'school_admin')->withTimestamps();
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

