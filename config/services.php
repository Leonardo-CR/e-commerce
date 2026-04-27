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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'conekta' => [
        'key' => env('CONEKTA_PRIVATE_KEY'),
    ],

    'envia' => [
        'token'   => env('ENVIA_MX_API_KEY'),
        'sandbox' => env('ENVIA_SANDBOX', true),
        'origin'  => [
            'name'        => env('ENVIA_ORIGIN_NAME', 'HaloSound'),
            'phone'       => env('ENVIA_ORIGIN_PHONE', '+528180000000'),
            'street'      => env('ENVIA_ORIGIN_STREET', 'Av. Benito Juarez 100'),
            'city'        => env('ENVIA_ORIGIN_CITY', 'Monterrey'),
            'state'       => env('ENVIA_ORIGIN_STATE', 'NL'),
            'postal_code' => env('ENVIA_ORIGIN_POSTAL_CODE', '64060'),
        ],
    ],

];
