<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\FlexySusu\FlexySusuDirectDepositCreateController;
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
        uri: '/{direct_deposit}/cancel',
        action: FlexySusuDirectDepositCancelController::class,
    )->name(
        name: 'direct_deposit.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'direct_deposit',
        ]
    );

    // Approval direct deposit request route
    Route::post(
        uri: '/{direct_deposit}/approval',
        action: FlexySusuDirectDepositApprovalController::class,
    )->name(
        name: 'direct_deposit.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'direct_deposit',
        ]
    );
});
