<?php

namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExamProgramEndedSms extends Notification
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
            'text' => "{$this->studentName} عزیز خسته نباشی! \nتجربه برنامه درسی بازه امتحانات SDFR به پایان رسید. ⏱️ \nهوشمند درس خوندن رو تجربه کردی و امیدواریم از این تجربه لذت برده باشی. \nبرای ادامه پرقدرت در مسیر پیشرفت تحصیلی هوشمند و به نتیجه رسوندن تلاش یک هفته ای، میتونی عضو خانواده بزرگ ما باشی!\n جزئیات بیشتر رو اینجا ببین: {$this->dashboardUrl} \n\nدپارتمان هوشمند مشاوره تحصیلی SDFR",
            'context' => 'exam_program_ended',
        ];
    }
}
