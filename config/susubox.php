<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | SusuBox Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for SusuBox services such
    | as Gate, authentication, customer and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'authentication' => [
        'base_url' => env('SSB_AUTH_URL'),
        'app_id' => env('SSB_AUTH_APP_ID'),
        'app_key' => env('SSB_AUTH_APP_KEY'),
    ],
    'ussd' => [
        'base_url' => env('SSB_USSD_URL'),
        'app_id' => env('SSB_USSD_APP_ID'),
        'app_key' => env('SSB_USSD_APP_KEY'),
    ],
    'customer' => [
        'base_url' => env('SSB_CUSTOMER_URL'),
        'app_id' => env('SSB_CUSTOMER_APP_ID'),
        'app_key' => env('SSB_CUSTOMER_APP_KEY'),
    ],
    'susu' => [
        'base_url' => env('SSB_SUSU_URL'),
        'app_id' => env('SSB_SUSU_APP_ID'),
        'app_key' => env('SSB_SUSU_APP_KEY'),
    ],
    'loan' => [
        'base_url' => env('SSB_LOAN_URL'),
        'app_id' => env('SSB_LOAN_APP_ID'),
        'app_key' => env('SSB_LOAN_APP_KEY'),
    ],
    'investment' => [
        'base_url' => env('SSB_INVESTMENT_URL'),
        'app_id' => env('SSB_INVESTMENT_APP_ID'),
        'app_key' => env('SSB_INVESTMENT_APP_KEY'),
    ],
    'insurance' => [
        'base_url' => env('SSB_INSURANCE_URL'),
        'app_id' => env('SSB_INSURANCE_APP_ID'),
        'app_key' => env('SSB_INSURANCE_APP_KEY'),
    ],
    'notification' => [
        'base_url' => env('SSB_NOTIFICATION_URL'),
        'app_id' => env('SSB_NOTIFICATION_APP_ID'),
        'app_key' => env('SSB_NOTIFICATION_APP_KEY'),
    ],
    'payment' => [
        'base_url' => env('SSB_PAYMENT_URL'),
        'app_id' => env('SSB_PAYMENT_APP_ID'),
        'app_key' => env('SSB_PAYMENT_APP_KEY'),
    ],
];
