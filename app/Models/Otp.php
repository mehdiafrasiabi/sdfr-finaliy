<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    protected $guarded=[];
    public $timestamps = true;

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
