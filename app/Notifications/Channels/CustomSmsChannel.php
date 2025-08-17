<?php

namespace App\Notifications\Channels;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CustomSmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toCustomSms($notifiable);

        $postData = [
            'username' => '989940682693',
            'password' => '4BYMC',
            'text' => $data['code'],
            'to' => $data['mobile'],
            'bodyId' => 254707,
        ];

        $post_data = http_build_query($postData);
        $handle = curl_init('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber');
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['content-type' => 'application/x-www-form-urlencoded']);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        $response = curl_exec($handle);

        if (curl_errno($handle)) {
            Log::error('cURL Error in CustomSmsChannel', ['error' => curl_error($handle)]);
            throw new \Exception('cURL Error: ' . curl_error($handle));
        }

        curl_close($handle);

        Log::info('Melipayamak Response in CustomSmsChannel', ['response' => $response]);
        return $response;
    }
}
