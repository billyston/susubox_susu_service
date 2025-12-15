<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\DailySusuDirectDepositCreateController;
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
        uri: '/{payment_instruction}/cancel',
        action: DailySusuDirectDepositCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'payment_instruction',
        ]
    );

    // Approve direct deposit request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: DailySusuDirectDepositApprovalController::class,
    )->name(
        name: 'payment_instruction.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'payment_instruction',
        ]
    );
});
