<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Pause\DailySusuPauseApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Pause\DailySusuPauseCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Pause\DailySusuPauseCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/pauses',
    'as' => 'customers.customer.daily_susus.daily_susu.pauses.',
], function (): void {
    // Create pause request route
    Route::post(
        uri: '',
        action: DailySusuPauseCreateController::class,
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
        uri: '/{account_pause}/cancel',
        action: DailySusuPauseCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_pause',
        ]
    );

    // Approve pause request route
    Route::post(
        uri: '/{account_pause}/approval',
        action: DailySusuPauseApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_pause',
        ]
    );
});
