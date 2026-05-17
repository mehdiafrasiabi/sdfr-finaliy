<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SchoolManager extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
