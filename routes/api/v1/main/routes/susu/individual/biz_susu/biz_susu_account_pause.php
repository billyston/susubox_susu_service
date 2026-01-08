<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountPauseApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountPauseCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountPauseCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/biz-susus/{biz_susu}/account-pause',
    'as' => 'customers.customer.biz_susus.biz_susu.account_pause.',
], function (): void {
    // Create account pause request route
    Route::post(
        uri: '',
        action: BizSusuAccountPauseCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );

    // Cancel account pause request route
    Route::post(
        uri: '/{account_pause}/cancel',
        action: BizSusuAccountPauseCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'account_pause',
        ]
    );

    // Approve account pause request route
    Route::post(
        uri: '/{account_pause}/approval',
        action: BizSusuAccountPauseApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
            'account_pause',
        ]
    );
});
