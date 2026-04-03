<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCountdownSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function events()
    {
        return $this->hasMany(ExamCountdownEvent::class)->orderBy('sort_order');
    }
}
