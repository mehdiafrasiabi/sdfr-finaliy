<?php

namespace App\Notifications;

use App\Notifications\Channels\ParentInviteSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ParentAssessmentInvite extends Notification
{
    use Queueable;

    public function __construct(
        protected string $mobile,
        protected string $studentName,
        protected string $link,
        protected string $parentRole,
    ) {}

    public function via($notifiable): array
    {
        return [ParentInviteSmsChannel::class];
    }

    public function toCustomSms($notifiable): array
    {
        return [
            'mobile'      => $this->mobile,
            'studentName' => $this->studentName,
            'link'        => $this->link,
            'parentRole'  => $this->parentRole,
        ];
    }
}
