<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Lock\DailySusuLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Lock\DailySusuLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Lock\DailySusuLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/locks',
    'as' => 'customers.customer.daily_susus.daily_susu.locks.',
], function (): void {
    // Create lock request route
    Route::post(
        uri: '',
        action: DailySusuLockCreateController::class,
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
        uri: '/{account_lock}/cancel',
        action: DailySusuLockCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_lock',
        ]
    );

    // Approve lock request route
    Route::post(
        uri: '/{account_lock}/approval',
        action: DailySusuLockApprovalController::class,
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
