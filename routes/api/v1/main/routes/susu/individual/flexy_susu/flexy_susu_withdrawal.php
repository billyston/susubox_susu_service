<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Withdrawal\FlexySusuWithdrawalCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/flexy-susus/{flexy_susu}/withdrawals',
    'as' => 'customers.customer.flexy-susus.flexy_susu.withdrawals.',
], function (): void {
    // Create withdrawal request route
    Route::post(
        uri: '',
        action: FlexySusuWithdrawalCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
        ]
    );

    // Cancel withdrawal request route
    Route::post(
        uri: '/{payment_instruction}/cancel',
        action: FlexySusuWithdrawalCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'payment_instruction',
        ]
    );

    // Approve withdrawal request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: FlexySusuWithdrawalApprovalController::class,
    )->name(
        name: 'payment_instruction.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'payment_instruction',
        ]
    );
});
