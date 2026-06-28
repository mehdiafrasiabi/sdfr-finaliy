<?php

namespace App\Services;

use App\Models\PhoneLead;
use App\Models\PhoneRegistrationLink;
use App\Notifications\Channels\CustomSmsChannel;
use App\Notifications\SendRegistrationLink;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * ساخت و ارسال لینک یکتای ثبت‌نام برای شمارهٔ جذب تلفنی.
 */
class PhoneRegistrationService
{
    /**
     * یک لینک یکتا برای شماره می‌سازد و (در صورت تنظیم bodyId) پیامک می‌کند.
     */
    public function createAndSend(PhoneLead $lead, int $adminId): PhoneRegistrationLink
    {
        $link = PhoneRegistrationLink::create([
            'token'         => $this->uniqueToken(),
            'phone_lead_id' => $lead->id,
            'admin_id'      => $adminId,
            'mobile'        => $lead->mobile,
        ]);

        if (SendRegistrationLink::SMS_BODY_ID) {
            try {
                (new AnonymousNotifiable())
                    ->route(CustomSmsChannel::class, $lead->mobile)
                    ->notify(new SendRegistrationLink(
                        mobile: $lead->mobile,
                        name:   $lead->full_name ?: 'دانش‌آموز',
                        link:   $link->url,
                    ));

                $link->update(['sent_at' => now()]);
            } catch (\Throwable $e) {
                Log::error('Registration link SMS failed', [
                    'lead_id' => $lead->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        } else {
            // هنوز bodyId الگو تنظیم نشده؛ لینک ساخته می‌شود ولی پیامک ارسال نمی‌شود.
            Log::info('Registration link created (SMS skipped — SMS_BODY_ID not set).', [
                'token' => $link->token,
                'url'   => $link->url,
            ]);
        }

        return $link;
    }

    protected function uniqueToken(): string
    {
        do {
            $token = Str::lower(Str::random(12));
        } while (PhoneRegistrationLink::where('token', $token)->exists());

        return $token;
    }
}
