<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuDirectDepositCreateController;
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
        uri: '/{direct_deposit}/cancel',
        action: BizSusuDirectDepositCancelController::class,
    )->name(
        name: 'direct_deposit.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'direct_deposit',
        ]
    );

    // Approve direct deposit request route
    Route::post(
        uri: '/{direct_deposit}/approval',
        action: BizSusuDirectDepositApprovalController::class,
    )->name(
        name: 'direct_deposit.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'direct_deposit',
        ]
    );
});
