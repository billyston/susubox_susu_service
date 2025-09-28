<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\DailySusu\DailySusuApprovalController;
use App\Http\Controllers\V1\Susu\DailySusu\DailySusuCreateController;
use App\Http\Controllers\V1\Susu\DailySusu\DailySusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create daily susu request route
    Route::post(
        uri: 'daily-susus',
        action: DailySusuCreateController::class,
    )->name(
        name: 'daily-susus'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Daily susu approval route
    Route::post(
        uri: '{account}/daily-susus/approval',
        action: DailySusuApprovalController::class
    )->name(
        name: 'daily-susus.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Get daily (single) susu route
    Route::get(
        uri: '{account}/daily-susus',
        action: DailySusuShowController::class
    )->name(
        name: 'daily-susus.show'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );
});
