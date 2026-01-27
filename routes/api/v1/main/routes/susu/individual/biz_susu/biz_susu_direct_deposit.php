<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\DirectDeposit\BizSusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\DirectDeposit\BizSusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\DirectDeposit\BizSusuDirectDepositCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/biz-susus/{biz_susu}/direct-deposits',
    'as' => 'customers.customer.biz-susus.biz_susu.direct-deposits.',
], function (): void {
    // Create direct deposit request route
    Route::post(
        uri: '',
        action: BizSusuDirectDepositCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );

    // Cancel direct deposit request route
    Route::post(
        uri: '/{payment_instruction}/cancel',
        action: BizSusuDirectDepositCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'payment_instruction',
        ]
    );

    // Approve direct deposit request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: BizSusuDirectDepositApprovalController::class,
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
