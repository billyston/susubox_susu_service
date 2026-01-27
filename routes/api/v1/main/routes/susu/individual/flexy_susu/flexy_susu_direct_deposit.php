<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\DirectDeposit\FlexySusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\DirectDeposit\FlexySusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\DirectDeposit\FlexySusuDirectDepositCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/flexy-susus/{flexy_susu}/direct-deposits',
    'as' => 'customers.customer.flexy-susus.flexy_susu.direct-deposits.',
], function (): void {
    // Create direct deposit request route
    Route::post(
        uri: '',
        action: FlexySusuDirectDepositCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
        ]
    );

    // Cancel direct deposit request route
    Route::post(
        uri: '/{payment_instruction}/cancel',
        action: FlexySusuDirectDepositCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'payment_instruction',
        ]
    );

    // Approval direct deposit request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: FlexySusuDirectDepositApprovalController::class,
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
