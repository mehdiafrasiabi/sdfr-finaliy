<?php

namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrialStartedSms extends Notification
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
            'text' => "سلام رفیق!\n به سیستم مشاوره تحصیلی هوشمند SDFR خوش اومدی. 🚀\n با انتخاب این سیستم خفن بودنت به ما ثابت شد!\nطرح آزمایشی ۷ روزه تو از همین الان فعال شد. \nآماده یه شروع قدرتمند برای راحت درس خوندن و سریع نتیجه گرفتن هستی؟ \nپس همین حالا شروع کن !\n\nsdfr.me",
            'context' => 'trial_started',
        ];
    }
}
