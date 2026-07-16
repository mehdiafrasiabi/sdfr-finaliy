<?php

namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExamProgramStartedSms extends Notification
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
            'text' => "SDFR\n\nسلام رفیق!\n به سیستم مشاوره تحصیلی هوشمند SDFR خوش اومدی. 🚀\nطرح برنامه درسی بازه امتحانات فعال شد.\n از امروز میتونی فضانوردی در منظومه ی SDFR رو تجربه کنی !\nآماده شروع قدرتمند برای راحت درس خوندن و سریع نتیجه گرفتن هستی؟ \nبه زودی مشاور متخصص باهات تماس میگیره و کامل راهنمای مسیرت خواهد بود.\n\nدپارتمان هوشمند مشاوره تحصیلی SDFR",
            'context' => 'exam_program_started',
        ];
    }
}
