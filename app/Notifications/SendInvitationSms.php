<?php

namespace App\Notifications;

use App\Notifications\Channels\MelipayamakDirectSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendInvitationSms extends Notification
{
    use Queueable;

    protected string $mobile;
    protected string $text;

    public function __construct(string $mobile, string $text)
    {
        $this->mobile = $mobile;
        $this->text = $text;
    }

    public function via($notifiable): array
    {
        return [MelipayamakDirectSmsChannel::class];
    }

    public function toMelipayamakDirectSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            'text' => $this->text,
            'context' => 'invitation',
        ];
    }
}
