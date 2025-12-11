<?php
namespace App\Notifications;

use App\Notifications\Channels\CustomSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdvisorSessionHeld extends Notification
{
    use Queueable;

    public function __construct(private string $mobile, private string $studentName, private string $date)
    {
    }

    public function via($notifiable): array
    {
        return [CustomSmsChannel::class];
    }

    public function toCustomSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            'text' => "{$this->studentName};{$this->date}",
            'bodyId' => 404819,
        ];
    }
}
