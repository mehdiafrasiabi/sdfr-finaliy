<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
class AdvisingSession extends Model
{
    protected $guarded = [];
    public function preSession(): HasOne
    {
        return $this->hasOne(AdvisingPreSession::class);
    }

    // (اختیاری ولی توصیه شده) روابط دیگر را هم اضافه کنید
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
