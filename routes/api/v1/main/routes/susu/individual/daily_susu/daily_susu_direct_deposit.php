<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\DailySusu\DailySusuDirectDepositCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/direct-deposits',
    'as' => 'customers.customer.daily-susus.daily_susu.direct-deposits.',
], function (): void {
    // Create direct deposit request route
    Route::post(
        uri: '',
        action: DailySusuDirectDepositCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Cancel direct deposit request route
    Route::post(
        uri: '/{direct_deposit}/cancel',
        action: DailySusuDirectDepositCancelController::class,
    )->name(
        name: 'direct_deposit.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'direct_deposit',
        ]
    );

    // Approve direct deposit request route
    Route::post(
        uri: '/{direct_deposit}/approval',
        action: DailySusuDirectDepositApprovalController::class,
    )->name(
        name: 'direct_deposit.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'direct_deposit',
        ]
    );
});
