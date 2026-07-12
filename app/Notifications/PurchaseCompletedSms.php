<?php

namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseCompletedSms extends Notification
{
    use Queueable;

    public function __construct(protected string $mobile) {}

    public function via($notifiable): array
    {
        return [MelipayamakDirectSmsChannel::class];
    }

    public function toMelipayamakDirectSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            'text' => "درود و صد سلام!\n به خانواده بزرگ SDFR خوش اومدی. 🎉\n حضور گرم تو باعث افتخار این خانواده است.\n مسیر رتبه شدن تو رسماً شروع شد.\n برای دریافت برنامه‌ها و شروع کار با مشاور همین حالا اقدام کن !\n\nsdfr.me",
            'context' => 'purchase_completed',
        ];
    }
}
