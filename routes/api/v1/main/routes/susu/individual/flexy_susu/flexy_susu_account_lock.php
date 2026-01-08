<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\FlexySusuAccountLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\FlexySusuAccountLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\FlexySusuAccountLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/flexy-susus/{flexy_susu}/account-locks',
    'as' => 'customers.customer.flexy_susus.flexy_susu.account_locks.',
], function (): void {
    // Create account lock request route
    Route::post(
        uri: '',
        action: FlexySusuAccountLockCreateController::class,
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
        action: FlexySusuAccountLockCancelController::class,
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
        action: FlexySusuAccountLockApprovalController::class,
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
