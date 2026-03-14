<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];

    protected $casts = [
        'closed_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
    public function unreadMessagesForAdmin()
    {
        return $this->messages()->where('admin_id', null)->where('is_read', false);
    }

    public function unreadMessagesForUser()
    {
        return $this->messages()->where('user_id', null)->where('is_read', false);
    }

    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            'low' => 'کم',
            'medium' => 'متوسط',
            'high' => 'زیاد',
            'urgent' => 'فوری',
            default => '-',
        };
    }
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'waiting' => 'در انتظار پاسخ',
            'answered' => 'پاسخ داده شده',
            'closed' => 'بسته شده',
            default => '-',
        };
    }

}
