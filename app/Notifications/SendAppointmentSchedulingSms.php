<?php

namespace App\Notifications;

use App\Notifications\Channels\StudentSmsPlanChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * پیامک تبریک و ارسال لینک تعیین وقت جلسات مشاوره بعد از ثبت‌نام.
 * از همان کانال StudentSmsPlanChannel استفاده می‌کند (bodyId=384664 "studentName;link").
 */
class SendAppointmentSchedulingSms extends Notification
{
    use Queueable;

    protected string $mobile;
    protected string $studentName;
    protected string $link;

    public function __construct(string $mobile, string $studentName, string $link)
    {
        $this->mobile      = $mobile;
        $this->studentName = $studentName;
        $this->link        = $link;
    }

    public function via($notifiable): array
    {
        return [StudentSmsPlanChannel::class];
    }

    public function toCustomSms($notifiable): array
    {
        return [
            'mobile'      => $this->mobile,
            'link'        => $this->link,
            'studentName' => $this->studentName,
        ];
    }
}

