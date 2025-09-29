<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuApprovalController;
use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuCancelController;
use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuCreateController;
use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create flexy susu request route
    Route::post(
        uri: 'flexy-susus',
        action: FlexySusuCreateController::class,
    )->name(
        name: 'flexy-susus'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Flexy susu cancel route
    Route::post(
        uri: '{account}/flexy-susus/cancel',
        action: FlexySusuCancelController::class
    )->name(
        name: 'flexy-susus.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Flexy susu approval route
    Route::post(
        uri: '{account}/flexy-susus/approval',
        action: FlexySusuApprovalController::class
    )->name(
        name: 'flexy-susus.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Get flexy (single) susu route
    Route::get(
        uri: '{account}/flexy-susus',
        action: FlexySusuShowController::class,
    )->name(
        name: 'flexy-susus.show'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ],
    );
});
