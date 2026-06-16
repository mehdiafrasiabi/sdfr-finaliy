<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyCall extends Model
{
    protected $guarded = [];

    protected $casts = [
        'called_at'   => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_RESOLVED = 'resolved';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function resolver()
    {
        return $this->belongsTo(Admin::class, 'resolved_by');
    }
}
