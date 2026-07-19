<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'http://127.0.0.1:8000/auth/gmail/callback',
    ],'zibal' =>[
        'merchant'=>env('ZIBAL_MERCHANT_ID'),
    ],'ghasedak' => [
    'key' => env('GHASEDAKAPI_KEY'),
],

    'melipayamak' => [
        'simple_endpoint' => env('MELIPAYAMAK_SIMPLE_ENDPOINT', 'https://console.melipayamak.com/api/send/simple/2533874113094413bd33384f343bb7b6'),
        'otp_endpoint' => env('MELIPAYAMAK_OTP_ENDPOINT', 'https://console.melipayamak.com/api/send/otp/2533874113094413bd33384f343bb7b6'),
        'from_simple' => env('MELIPAYAMAK_FROM_SIMPLE', '9982005935'),

        'username' => env('MELIPAYAMAK_USERNAME', '9020029757'),
        'password' => env('MELIPAYAMAK_PASSWORD', '66a372b1-fbfd-41b7-8f42-edbb38eb783d'),
        'endpoint' => env('MELIPAYAMAK_ENDPOINT', 'https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber'),
        'smart_endpoint' => env('MELIPAYAMAK_SMART_ENDPOINT', 'https://rest.payamak-panel.com/api/SmartSMS/Send'),
        'from' => env('MELIPAYAMAK_FROM'),
        'from_support_one' => env('MELIPAYAMAK_FROM_SUPPORT_ONE'),
        'from_support_two' => env('MELIPAYAMAK_FROM_SUPPORT_TWO'),

        'otp_body_id' => env('MELIPAYAMAK_OTP_BODY_ID', 480452),
        'registration_link_body_id' => env('MELIPAYAMAK_REGISTRATION_LINK_BODY_ID', 480452),
        'student_plan_body_id' => env('MELIPAYAMAK_STUDENT_PLAN_BODY_ID', 480452),
        'parent_invite_body_id' => env('MELIPAYAMAK_PARENT_INVITE_BODY_ID', 480452),
        'public_url' => env('SDFR_PUBLIC_URL', 'https://sdfr.me'),
        'dashboard_url' => env('SDFR_DASHBOARD_URL', 'https://sdfr.me/profile/dashboard'),
    ],

];
