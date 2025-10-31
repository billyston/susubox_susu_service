<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuApprovalController;
use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuCancelController;
use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuCreateController;
use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuIndexController;
use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/flexy-susus/',
    'as' => 'customers.customer.flexy-susus.',
], function (): void {
    // Create flexy susu request route
    Route::post(
        uri: '',
        action: FlexySusuCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Flexy susu cancel route
    Route::post(
        uri: '{flexy_susu}/cancel',
        action: FlexySusuCancelController::class
    )->name(
        name: 'flexy_susu.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
        ]
    );

    // Flexy susu approval route
    Route::post(
        uri: '{flexy_susu}/approval',
        action: FlexySusuApprovalController::class
    )->name(
        name: 'flexy_susu.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
        ]
    );

    // Get flexy (all) susu route
    Route::get(
        uri: '',
        action: FlexySusuIndexController::class,
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ],
    );

    // Get flexy (single) susu route
    Route::get(
        uri: '{flexy_susu}',
        action: FlexySusuShowController::class,
    )->name(
        name: 'flexy_susu.show'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
        ],
    );
});
