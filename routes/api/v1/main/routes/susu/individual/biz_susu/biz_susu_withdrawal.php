<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/biz-susus/{biz_susu}/withdrawals',
    'as' => 'customers.customer.biz-susus.biz_susu.withdrawals.',
], function (): void {
    // Create withdrawal request route
    Route::post(
        uri: '',
        action: BizSusuWithdrawalCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );

    // Cancel withdrawal request route
    Route::post(
        uri: '/{payment_instruction}/cancel',
        action: BizSusuWithdrawalCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'payment_instruction',
        ]
    );

    // Approve withdrawal request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: BizSusuWithdrawalApprovalController::class,
    )->name(
        name: 'payment_instruction.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'payment_instruction',
        ]
    );
});
