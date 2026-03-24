<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/locks',
    'as' => 'customers.customer.daily_susus.daily_susu.locks.',
], function (): void {
    // Create lock request route
    Route::post(
        uri: '',
        action: DailySusuSettlementLockCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Cancel lock request route
    Route::post(
        uri: '/{account_payout_lock}/cancel',
        action: DailySusuSettlementLockCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_payout_lock',
        ]
    );

    // Approve lock request route
    Route::post(
        uri: '/{account_payout_lock}/approval',
        action: DailySusuSettlementLockApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_payout_lock',
        ]
    );
});
