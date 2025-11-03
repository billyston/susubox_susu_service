<?php

declare(strict_types=1);

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
        uri: '/{direct_deposit}',
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
});
