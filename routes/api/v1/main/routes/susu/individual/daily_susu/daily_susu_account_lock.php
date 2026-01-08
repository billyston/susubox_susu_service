<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/account-locks',
    'as' => 'customers.customer.daily_susus.daily_susu.account_locks.',
], function (): void {
    // Create account lock request route
    Route::post(
        uri: '',
        action: DailySusuAccountLockCreateController::class,
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
        action: DailySusuAccountLockCancelController::class,
    )->name(
        name: 'cancel'
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
        action: DailySusuAccountLockApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_lock',
        ]
    );
});
