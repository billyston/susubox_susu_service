<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuApprovalController;
use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuCancelController;
use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuCreateController;
use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuIndexController;
use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/',
    'as' => 'customers.customer.daily-susus.',
], function (): void {
    // Create daily susu request route
    Route::post(
        uri: '',
        action: DailySusuCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Daily susu cancel route
    Route::post(
        uri: '{daily_susu}/cancel',
        action: DailySusuCancelController::class
    )->name(
        name: 'daily_susu.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Daily susu approval route
    Route::post(
        uri: '{daily_susu}/approval',
        action: DailySusuApprovalController::class
    )->name(
        name: 'daily_susu.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Get daily (all) susu route
    Route::get(
        uri: '',
        action: DailySusuIndexController::class
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Get daily (single) susu route
    Route::get(
        uri: '{daily_susu}',
        action: DailySusuShowController::class
    )->name(
        name: 'daily_susu.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );
});
