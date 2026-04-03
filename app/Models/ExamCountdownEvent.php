<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCountdownEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setting()
    {
        return $this->belongsTo(ExamCountdownSetting::class, 'exam_countdown_setting_id');
    }
}
