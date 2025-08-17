<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $guarded=[];
    public $timestamps = true;

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
