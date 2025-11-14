<?php
namespace App\Notifications;

use App\Notifications\Channels\StudentSmsPlanChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SendStudentPlanSms extends Notification
{
    use Queueable;

    protected $mobile;
    protected $studentName;
    protected $link;

    public function __construct($mobile, $studentName, $link)
    {
        $this->mobile = $mobile;
        $this->link = $link;
        $this->studentName = $studentName;
    }

    public function via($notifiable): array
    {
        return [StudentSmsPlanChannel::class];
    }

    public function toCustomSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            'link' => $this->link,
            'studentName' => $this->studentName,
        ];
    }
}
