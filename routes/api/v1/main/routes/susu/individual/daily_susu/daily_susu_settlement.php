<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCreateController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementIndexController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/settlements',
    'as' => 'customers.customer.daily_susus.daily_susu.settlements.',
], function (): void {
    // Create settlement request route
    Route::post(
        uri: '',
        action: DailySusuSettlementCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Cancel settlement request route
    Route::post(
        uri: '/{account_settlement}/cancel',
        action: DailySusuSettlementCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_settlement',
        ]
    );

    // Approve settlement request route
    Route::post(
        uri: '/{account_settlement}/approval',
        action: DailySusuSettlementApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_settlement',
        ]
    );

    // Get all settlement request route
    Route::get(
        uri: '',
        action: DailySusuSettlementIndexController::class,
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Get single settlement request route
    Route::get(
        uri: '{account_settlement}',
        action: DailySusuSettlementShowController::class,
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_settlement',
        ]
    );
});
