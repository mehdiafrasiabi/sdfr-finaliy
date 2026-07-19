<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MelipayamakOtpChannel
{
    public function send($notifiable, Notification $notification): void
    {
        // The toCustomSms method should return an array with a 'mobile' key.
        $payload = $notification->toCustomSms($notifiable);
        $to = $payload['mobile'];

        $endpoint = config('services.melipayamak.otp_endpoint');

        if (!$endpoint) {
            Log::error('Melipayamak OTP endpoint is not configured.');
            return;
        }

        try {
            $response = Http::asJson()
                ->post($endpoint, [
                    'to' => $to,
                    // The new API does not seem to take 'code' or 'text' in the body.
                    // The template with the code must be configured in the Melipayamak panel.
                ]);

            if ($response->successful()) {
                Log::info('OTP SMS sent successfully via Melipayamak (OTP API).', [
                    'to' => $to,
                    'response_body' => $response->json(),
                ]);
            } else {
                Log::error('Failed to send OTP SMS via Melipayamak (OTP API).', [
                    'to' => $to,
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::critical('Exception while sending OTP SMS via Melipayamak (OTP API).', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
