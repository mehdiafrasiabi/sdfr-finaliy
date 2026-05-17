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

    public function supporters()
    {
        return $this->belongsToMany(Admin::class, 'school_admin')->withTimestamps();
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
