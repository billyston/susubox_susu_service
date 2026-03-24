<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\PaymentInstruction\RecurringDeposit\RecurringDepositPauseController;
use App\Interface\Controllers\V1\PaymentInstruction\RecurringDeposit\RecurringDepositResumeController;
use App\Interface\Controllers\V1\PaymentInstruction\RecurringDeposit\RecurringDepositRolloverController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'accounts',
    'as' => 'accounts.',
], function (): void {
    // Recurring deposit paused request route
    Route::post(
        uri: 'recurring-deposit-pause/{recurring_deposit_pause}/pause',
        action: RecurringDepositPauseController::class,
    )->name(
        name: 'recurring_deposit_pause.recurring_deposit_pause.pause'
    );

    // Recurring deposit paused request route
    Route::post(
        uri: 'recurring-deposit-pause/{recurring_deposit_pause}/resume',
        action: RecurringDepositResumeController::class,
    )->name(
        name: 'recurring_deposit_pause.recurring_deposit_pause.resume'
    );

    // Recurring deposit rollover request route
    Route::post(
        uri: 'recurring-deposits/{recurring_deposit}/rollover',
        action: RecurringDepositRolloverController::class,
    )->name(
        name: 'recurring_deposits.recurring_deposit.rollover'
    );
});
