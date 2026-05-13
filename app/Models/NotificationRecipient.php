<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class NotificationRecipient extends Model

{

    use HasFactory;

    protected $guarded = [];


    protected $casts = [

        'is_read' => 'boolean',

        'read_at' => 'datetime',

    ];


    /**
     * اعلان مربوطه
     */

    public function notification(): BelongsTo

    {

        return $this->belongsTo(Notification::class);

    }


    /**
     * کاربر گیرنده
     */

    public function user(): BelongsTo

    {

        return $this->belongsTo(User::class);

    }


    /**
     * علامت‌گذاری به عنوان خوانده شده
     */

    public function markAsRead(): bool

    {

        return $this->update([

            'is_read' => true,

            'read_at' => now(),

        ]);

    }


    /**
     * Scope: فقط خوانده‌نشده‌ها
     */

    public function scopeUnread($query)

    {

        return $query->where('is_read', false);

    }


    /**
     * Scope: فقط خوانده‌شده‌ها
     */

    public function scopeRead($query)

    {

        return $query->where('is_read', true);

    }

}
