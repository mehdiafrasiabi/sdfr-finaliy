<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportStudentStudy extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function receiver()
    {
        return $this->belongsTo(Admin::class, 'receiver_id');
    }
    public function sender()
    {
        return $this->belongsTo(Admin::class, 'sender_id');
    }
}
