<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuDirectDepositCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/goal-getter-susus/{goal_getter_susu}/direct-deposits',
    'as' => 'customers.customer.goal-getter-susus.goal_getter_susu.direct-deposits.',
], function (): void {
    // Create direct deposit request route
    Route::post(
        uri: '',
        action: GoalGetterSusuDirectDepositCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
        ]
    );

    // Cancel direct deposit request route
    Route::post(
        uri: '/{direct_deposit}/cancel',
        action: GoalGetterSusuDirectDepositCancelController::class,
    )->name(
        name: 'direct_deposit.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'direct_deposit',
        ]
    );

    // Approval direct deposit request route
    Route::post(
        uri: '/{direct_deposit}/approval',
        action: GoalGetterSusuDirectDepositApprovalController::class,
    )->name(
        name: 'direct_deposit.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'direct_deposit',
        ]
    );
});
