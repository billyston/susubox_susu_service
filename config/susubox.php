<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | SusuBox Services
    |--------------------------------------------------------------------------
    |
    | This define and stores the credentials for SusuBox services such
    | as Gateway, authentication, customer and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'authentication' => [
        'name' => env('SSB_AUTH_NAME'),
        'base_url' => env('SSB_AUTH_URL'),
        'app_id' => env('SSB_AUTH_APP_ID'),
        'app_key' => env('SSB_AUTH_APP_KEY'),
    ],
    'ussd' => [
        'name' => env('SSB_USSD_NAME'),
        'base_url' => env('SSB_USSD_URL'),
        'app_id' => env('SSB_USSD_APP_ID'),
        'app_key' => env('SSB_USSD_APP_KEY'),
    ],
    'customer' => [
        'name' => env('SSB_CUSTOMER_NAME'),
        'base_url' => env('SSB_CUSTOMER_URL'),
        'app_id' => env('SSB_CUSTOMER_APP_ID'),
        'app_key' => env('SSB_CUSTOMER_APP_KEY'),
    ],
    'susu' => [
        'name' => env('SSB_SUSU_NAME'),
        'base_url' => env('SSB_SUSU_URL'),
        'app_id' => env('SSB_SUSU_APP_ID'),
        'app_key' => env('SSB_SUSU_APP_KEY'),
    ],
    'loan' => [
        'name' => env('SSB_LOAN_NAME'),
        'base_url' => env('SSB_LOAN_URL'),
        'app_id' => env('SSB_LOAN_APP_ID'),
        'app_key' => env('SSB_LOAN_APP_KEY'),
    ],
    'investment' => [
        'name' => env('SSB_INVESTMENT_NAME'),
        'base_url' => env('SSB_INVESTMENT_URL'),
        'app_id' => env('SSB_INVESTMENT_APP_ID'),
        'app_key' => env('SSB_INVESTMENT_APP_KEY'),
    ],
    'insurance' => [
        'name' => env('SSB_INSURANCE_NAME'),
        'base_url' => env('SSB_INSURANCE_URL'),
        'app_id' => env('SSB_INSURANCE_APP_ID'),
        'app_key' => env('SSB_INSURANCE_APP_KEY'),
    ],
    'pension' => [
        'name' => env('SSB_PENSION_NAME'),
        'base_url' => env('SSB_PENSION_URL'),
        'app_id' => env('SSB_PENSION_APP_ID'),
        'app_key' => env('SSB_PENSION_APP_KEY'),
    ],
    'notification' => [
        'name' => env('SSB_NOTIFICATION_NAME'),
        'base_url' => env('SSB_NOTIFICATION_URL'),
        'app_id' => env('SSB_NOTIFICATION_APP_ID'),
        'app_key' => env('SSB_NOTIFICATION_APP_KEY'),
    ],
    'payment' => [
        'name' => env('SSB_PAYMENT_NAME'),
        'base_url' => env('SSB_PAYMENT_URL'),
        'app_id' => env('SSB_PAYMENT_APP_ID'),
        'app_key' => env('SSB_PAYMENT_APP_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Susu Schemes
    |--------------------------------------------------------------------------
    */
    'susu_schemes' => [
        'daily_susu_code' => env('DAILY_SUSU_CODE'),
        'biz_susu_code' => env('BIZ_SUSU_CODE'),
        'goal_getter_susu_code' => env('GOAL_GETTER_SUSU_CODE'),
        'flexy_susu_code' => env('FLEXY_SUSU_CODE'),
        'bills_susu_code' => env('BILLS_SUSU_CODE'),
        'drive2own_susu_code' => env('DRIVE2OWN_SUSU_CODE'),
        'nkabom_group_susu_code' => env('NKABOM_SUSU_CODE'),
        'dwadieboa_group_susu_code' => env('DWADIEBOA_SUSU_CODE'),
    ],
];
