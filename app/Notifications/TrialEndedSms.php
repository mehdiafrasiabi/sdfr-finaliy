<?php

namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrialEndedSms extends Notification
{
    use Queueable;

    public function __construct(
        protected string $mobile,
        protected string $studentName,
        protected string $dashboardUrl,
    ) {}

    public function via($notifiable): array
    {
        return [MelipayamakDirectSmsChannel::class];
    }

    public function toMelipayamakDirectSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            'text' => "{$this->studentName} عزیزم خسته نباشی!\nیک هفته آزمایشی تو در SDFR به پایان رسید. ⏱️ \nامیدواریم از این تجربه لذت برده باشی. \nبرای ادامه پرقدرت این مسیر و اینکه به طور دائمی جزوی از ما بشی،\n جزئیات بیشتر رو اینجا ببین:\n {$this->dashboardUrl}\n\nsdfr.me",
            'context' => 'trial_ended',
        ];
    }
}
