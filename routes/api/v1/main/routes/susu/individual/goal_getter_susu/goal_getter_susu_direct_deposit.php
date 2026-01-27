<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\DirectDeposit\GoalGetterSusuDirectDepositApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\DirectDeposit\GoalGetterSusuDirectDepositCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\DirectDeposit\GoalGetterSusuDirectDepositCreateController;
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
        uri: '/{payment_instruction}/cancel',
        action: GoalGetterSusuDirectDepositCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'payment_instruction',
        ]
    );

    // Approval direct deposit request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: GoalGetterSusuDirectDepositApprovalController::class,
    )->name(
        name: 'payment_instruction.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'payment_instruction',
        ]
    );
});
