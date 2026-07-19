<?php
namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendOtpToUser extends Notification
{
    use Queueable;

    protected $mobile;
    protected $code;

    public function __construct($mobile, $code)
    {
        $this->mobile = $mobile;
        $this->code = $code;
    }

    public function via($notifiable): array
    {
        return [MelipayamakDirectSmsChannel::class];
    }

    public function toMelipayamakDirectSms($notifiable): array
    {
        $text = "SDFR\n\n" .
            "کاربر عزیز،\n" .
            "کد اعتبارسنجی شما\n\n" .
            "CODE:{$this->code}\n\n" .
            "سامانه هوشمند مشاوره تحصیلی SDFR\n" .
            "@sdfr.me #{$this->code}";

        return [
            'mobile' => $this->mobile,
            'text' => $text,
            'context' => 'otp',
        ];
    }
}
