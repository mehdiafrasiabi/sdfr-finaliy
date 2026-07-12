<?php

namespace App\Notifications;

use App\Notifications\Channels\CustomSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * پیامک ارسال «لینک یکتای ثبت‌نام» به شمارهٔ جذب تلفنی.
 * هم‌خانوادهٔ SendOtpToUser و از همان CustomSmsChannel استفاده می‌کند.
 */
class SendRegistrationLink extends Notification
{
    use Queueable;

    /**
     * شناسهٔ الگوی پیامک (bodyId) در پنل ملی‌پیامک.
     * TODO: پس از ساخت و تایید الگوی «لینک ثبت‌نام»، مقدار آن را اینجا قرار بده.
     * تا وقتی null است، سرویس ارسال پیامک را انجام نمی‌دهد (لینک ساخته می‌شود ولی پیامک نمی‌رود).
     */
    const SMS_BODY_ID = 480452;

    public function __construct(
        protected string $mobile,
        protected string $name,
        protected string $link,
    ) {}

    public function via($notifiable): array
    {
        return [CustomSmsChannel::class];
    }

    public function toCustomSms($notifiable): array
    {
        return [
            'mobile' => $this->mobile,
            // متغیرهای الگو با ; از هم جدا می‌شوند: {name};{link}
            'text'   => $this->name . ';' . $this->link,
            'bodyId' => config('services.melipayamak.registration_link_body_id', self::SMS_BODY_ID),
        ];
    }
}
