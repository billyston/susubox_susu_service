<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuWithdrawalApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuWithdrawalCancelController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuWithdrawalCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/goal-getter-susus/{goal_getter_susu}/withdrawals',
    'as' => 'customers.customer.goal-getter-susus.goal_getter_susu.withdrawals.',
], function (): void {
    // Create withdrawal request route
    Route::post(
        uri: '',
        action: GoalGetterSusuWithdrawalCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
        ]
    );

    // Cancel withdrawal request route
    Route::post(
        uri: '/{payment_instruction}/cancel',
        action: GoalGetterSusuWithdrawalCancelController::class,
    )->name(
        name: 'payment_instruction.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'payment_instruction',
        ]
    );

    // Approve withdrawal request route
    Route::post(
        uri: '/{payment_instruction}/approval',
        action: GoalGetterSusuWithdrawalApprovalController::class,
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
