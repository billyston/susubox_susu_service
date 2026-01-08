<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountPauseApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountPauseCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountPauseCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/account-pauses',
    'as' => 'customers.customer.daily_susus.daily_susu.account_pauses.',
], function (): void {
    // Create account pause request route
    Route::post(
        uri: '',
        action: DailySusuAccountPauseCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Cancel account pause request route
    Route::post(
        uri: '/{account_pause}/cancel',
        action: DailySusuAccountPauseCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_pause',
        ]
    );

    // Approve account pause request route
    Route::post(
        uri: '/{account_pause}/approval',
        action: DailySusuAccountPauseApprovalController::class,
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
