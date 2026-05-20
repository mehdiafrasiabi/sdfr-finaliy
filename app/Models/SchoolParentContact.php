<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolParentContact extends Model
{
    protected $guarded = [];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
