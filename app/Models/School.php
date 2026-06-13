<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function staff()
    {
        return $this->hasMany(SchoolStaff::class);
    }

    public function manager()
    {
        return $this->hasOne(SchoolStaff::class)->where('role', 'manager');
    }

    public function deputy()
    {
        return $this->hasOne(SchoolStaff::class)->where('role', 'deputy');
    }

    /**
     * مشاوران تحصیلی فعال‌شده برای این مدرسه.
     */
    public function advisors()
    {
        return $this->belongsToMany(Admin::class, 'school_advisor')->withTimestamps();
    }

    /**
     * اکانت ادمینِ «مدیر مدرسه» (نقش school-manager) متعلق به این مدرسه.
     */
    public function schoolManagerAdmin()
    {
        return $this->hasOne(Admin::class, 'school_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
