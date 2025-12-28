<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/settlement-locks',
    'as' => 'customers.customer.daily-susus.daily_susu.settlement-locks.',
], function (): void {
    // Create account lock request route
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

    // Cancel account lock request route
    Route::post(
        uri: '/{account_lock}/cancel',
        action: DailySusuSettlementLockCancelController::class,
    )->name(
        name: 'account_lock.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_lock',
        ]
    );

    // Approve account lock request route
    Route::post(
        uri: '/{account_lock}/approval',
        action: DailySusuSettlementLockApprovalController::class,
    )->name(
        name: 'account_lock.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_lock',
        ]
    );
});
