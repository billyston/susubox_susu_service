<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/account-settlements',
    'as' => 'customers.customer.daily_susus.daily_susu.account_settlements.',
], function (): void {
    // Create account settlement request route
    Route::post(
        uri: '',
        action: DailySusuAccountSettlementCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Cancel account settlement request route
    Route::post(
        uri: '/{account_settlement}/cancel',
        action: DailySusuAccountSettlementCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_settlement',
        ]
    );

    // Approve account settlement request route
    Route::post(
        uri: '/{account_settlement}/approval',
        action: DailySusuAccountSettlementApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_settlement',
        ]
    );
});
