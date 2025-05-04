<?php

declare(strict_types=1);

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

    'susubox' => [
        'ssb_auth' => [
            'base_url' => env('SSB_AUTH_URL'),
            'app_id' => env('SSB_AUTH_APP_ID'),
            'app_key' => env('SSB_AUTH_APP_KEY'),
        ],
        'ssb_ussd' => [
            'base_url' => env('SSB_USSD_URL'),
            'app_id' => env('SSB_USSD_APP_ID'),
            'app_key' => env('SSB_USSD_APP_KEY'),
        ],
        'ssb_customer' => [
            'base_url' => env('SSB_CUSTOMER_URL'),
            'app_id' => env('SSB_CUSTOMER_APP_ID'),
            'app_key' => env('SSB_CUSTOMER_APP_KEY'),
        ],
        'ssb_susu' => [
            'base_url' => env('SSB_SUSU_URL'),
            'app_id' => env('SSB_SUSU_APP_ID'),
            'app_key' => env('SSB_SUSU_APP_KEY'),
        ],
        'ssb_loan' => [
            'base_url' => env('SSB_LOAN_URL'),
            'app_id' => env('SSB_LOAN_APP_ID'),
            'app_key' => env('SSB_LOAN_APP_KEY'),
        ],
        'ssb_investment' => [
            'base_url' => env('SSB_INVESTMENT_URL'),
            'app_id' => env('SSB_INVESTMENT_APP_ID'),
            'app_key' => env('SSB_INVESTMENT_APP_KEY'),
        ],
        'ssb_insurance' => [
            'base_url' => env('SSB_INSURANCE_URL'),
            'app_id' => env('SSB_INSURANCE_APP_ID'),
            'app_key' => env('SSB_INSURANCE_APP_KEY'),
        ],
        'ssb_notification' => [
            'base_url' => env('SSB_NOTIFICATION_URL'),
            'app_id' => env('SSB_NOTIFICATION_APP_ID'),
            'app_key' => env('SSB_NOTIFICATION_APP_KEY'),
        ],
        'ssb_external' => [
            'base_url' => env('SSB_EXTERNAL_URL'),
            'app_id' => env('SSB_EXTERNAL_APP_ID'),
            'app_key' => env('SSB_EXTERNAL_APP_KEY'),
        ],
    ],
];
