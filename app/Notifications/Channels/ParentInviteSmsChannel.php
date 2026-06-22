<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * ارسال SMS لینک تست والدینی از طریق Melipayamak.
 * payload از notification: ['mobile', 'studentName', 'link', 'parentRole'].
 *
 * نکته: bodyId مربوط به template ای است که در پنل Melipayamak باید
 * با دو پارامتر (studentName, link) ثبت شود. تا زمان ثبت template واقعی،
 * مقدار آن از config('services.melipayamak.parent_invite_body_id') خوانده
 * می‌شود و به فرمت body دلخواه قابل تغییر است.
 */
class ParentInviteSmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toCustomSms($notifiable);

        $postData = [
            'username' => config('services.melipayamak.username', '9020029757'),
            'password' => config('services.melipayamak.password', '7b1b0fdb-dddd-4c93-b02d-a069edf44693'),
            // فرمت template: "نام دانش‌آموز;لینک"
            'text'     => ($data['studentName'] ?? '') . ';' . ($data['link'] ?? ''),
            'to'       => $data['mobile'],
            'bodyId'   => config('services.melipayamak.parent_invite_body_id', 479744),
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
            Log::error('cURL Error in ParentInviteSmsChannel', ['error' => curl_error($handle)]);
            throw new \Exception('cURL Error: ' . curl_error($handle));
        }

        curl_close($handle);

        Log::info('Melipayamak Response in ParentInviteSmsChannel', [
            'response' => $response,
            'mobile'   => $data['mobile'],
        ]);

        return $response;
    }
}
