<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/flexy-susus/{flexy_susu}/withdrawal-locks',
    'as' => 'customers.customer.flexy-susus.flexy_susu.withdrawal-locks.',
], function (): void {
    // Create account lock request route
    Route::post(
        uri: '',
        action: FlexySusuWithdrawalLockCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
        ]
    );

    // Cancel account lock request route
    Route::post(
        uri: '/{account_lock}/cancel',
        action: FlexySusuWithdrawalLockCancelController::class,
    )->name(
        name: 'account_lock.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'account_lock',
        ]
    );

    // Approve account lock request route
    Route::post(
        uri: '/{account_lock}/approval',
        action: FlexySusuWithdrawalLockApprovalController::class,
    )->name(
        name: 'account_lock.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'flexy_susu',
            'account_lock',
        ]
    );
});
