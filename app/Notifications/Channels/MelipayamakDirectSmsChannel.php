<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MelipayamakDirectSmsChannel
{
    public function send($notifiable, Notification $notification): array
    {
        $data = $notification->toMelipayamakDirectSms($notifiable);

        $from = (string) config('services.melipayamak.from', '');
        if ($from === '') {
            throw new RuntimeException('Melipayamak direct sender number is missing.');
        }

        $postData = [
            'username' => config('services.melipayamak.username', '9020029757'),
            'password' => config('services.melipayamak.password', '66a372b1-fbfd-41b7-8f42-edbb38eb783d'),
            'to' => $data['mobile'],
            'from' => $from,
            'text' => $data['text'],
        ];

        if ($supportOne = config('services.melipayamak.from_support_one')) {
            $postData['fromSupportOne'] = $supportOne;
        }
        if ($supportTwo = config('services.melipayamak.from_support_two')) {
            $postData['fromSupportTwo'] = $supportTwo;
        }

        $handle = curl_init(config('services.melipayamak.smart_endpoint', 'https://rest.payamak-panel.com/api/SmartSMS/Send'));
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['content-type' => 'application/x-www-form-urlencoded']);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($postData));

        $response = curl_exec($handle);
        $curlError = curl_error($handle);
        curl_close($handle);

        if ($response === false) {
            Log::error('cURL Error in MelipayamakDirectSmsChannel', [
                'error' => $curlError,
                'mobile' => $data['mobile'],
                'context' => $data['context'] ?? null,
            ]);

            throw new RuntimeException('Melipayamak cURL error: ' . $curlError);
        }

        $result = $this->parseResponse($response);
        if ((int) ($result['RetStatus'] ?? 0) !== 1) {
            Log::error('Melipayamak direct SMS rejected', [
                'response' => $result,
                'mobile' => $data['mobile'],
                'context' => $data['context'] ?? null,
            ]);

            throw new RuntimeException('Melipayamak rejected direct SMS: ' . ($result['StrRetStatus'] ?? $result['Value'] ?? 'Unknown error'));
        }

        Log::info('Melipayamak direct SMS sent', [
            'response' => $result,
            'mobile' => $data['mobile'],
            'context' => $data['context'] ?? null,
        ]);

        return $result;
    }

    protected function parseResponse(string $response): array
    {
        $decoded = json_decode($response, true);
        if (! is_array($decoded)) {
            Log::error('Invalid Melipayamak direct response', ['response' => $response]);
            throw new RuntimeException('Invalid Melipayamak direct response.');
        }

        return $decoded;
    }
}
