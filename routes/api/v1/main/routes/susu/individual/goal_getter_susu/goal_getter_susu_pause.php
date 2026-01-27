<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuAccountPauseCreateController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseApprovalController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Pause\GoalGetterSusuPauseCancelController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/goal-getter-susus/{goal_getter_susu}/account-pause',
    'as' => 'customers.customer.goal_getter_susus.goal_getter_susu.account_pause.',
], function (): void {
    // Create account pause request route
    Route::post(
        uri: '',
        action: GoalGetterSusuAccountPauseCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
        ]
    );

    // Cancel account pause request route
    Route::post(
        uri: '/{account_pause}/cancel',
        action: GoalGetterSusuPauseCancelController::class,
    )->name(
        name: 'cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'account_pause',
        ]
    );

    // Approval account pause request route
    Route::post(
        uri: '/{account_pause}/approval',
        action: GoalGetterSusuPauseApprovalController::class,
    )->name(
        name: 'approval'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
            'account_pause',
        ]
    );
});
