<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuCreateController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuIndexController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuReactivationController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuShowController;
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

    // Daily susu activation route (after initial deposit failed)
    Route::post(
        uri: '{daily_susu}/reactivation',
        action: DailySusuReactivationController::class
    )->name(
        name: 'daily_susu.activation'
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
