<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalLockApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalLockCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalLockCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/biz-susus/{biz_susu}/withdrawal-locks',
    'as' => 'customers.customer.biz-susus.biz_susu.withdrawal-locks.',
], function (): void {
    // Create account lock request route
    Route::post(
        uri: '',
        action: BizSusuWithdrawalLockCreateController::class,
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
        action: BizSusuWithdrawalLockCancelController::class,
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
        action: BizSusuWithdrawalLockApprovalController::class,
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
