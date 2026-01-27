<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Lock\BizSusuLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Lock\BizSusuLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Lock\BizSusuLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/biz-susus/{biz_susu}/account-locks',
    'as' => 'customers.customer.biz_susus.biz_susu.account_locks.',
], function (): void {
    // Create account lock request route
    Route::post(
        uri: '',
        action: BizSusuLockCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );

    // Cancel account lock request route
    Route::post(
        uri: '/{account_lock}/cancel',
        action: BizSusuLockCancelController::class,
    )->name(
        name: 'account_lock.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'account_lock',
        ]
    );

    // Approve account lock request route
    Route::post(
        uri: '/{account_lock}/approval',
        action: BizSusuLockApprovalController::class,
    )->name(
        name: 'account_lock.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'account_lock',
        ]
    );
});
