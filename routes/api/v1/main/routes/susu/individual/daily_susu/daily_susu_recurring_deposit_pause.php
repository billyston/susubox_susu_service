<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDepositPause\DailySusuRecurringDepositPauseCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/pauses',
    'as' => 'customers.customer.daily_susus.daily_susu.pauses.',
], function (): void {
    // Create pause request route
    Route::post(
        uri: '',
        action: DailySusuRecurringDepositPauseCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Cancel pause request route
    Route::post(
        uri: '/{recurring_deposit_pause}/cancel',
        action: DailySusuRecurringDepositPauseCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'recurring_deposit_pause',
        ]
    );

    // Approve pause request route
    Route::post(
        uri: '/{recurring_deposit_pause}/approval',
        action: DailySusuRecurringDepositPauseApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'recurring_deposit_pause',
        ]
    );
});
