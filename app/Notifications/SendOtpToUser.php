<?php
namespace App\Notifications;

use App\Notifications\Channels\CustomSmsChannel;
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
        return [CustomSmsChannel::class];
    }

    public function toCustomSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            'code' => $this->code,
        ];
    }
}
