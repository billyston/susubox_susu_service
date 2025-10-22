<?php

declare(strict_types=1);

use App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalController;
use App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuCancelController;
use App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateController;
use App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuIndexController;
use App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/goal-getter-susus/',
    'as' => 'customers.customer.goal-getter-susus.',
], function (): void {
    // Create goal getter susu request route
    Route::post(
        uri: '',
        action: GoalGetterSusuCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
        ],
    );

    // Goal getter susu cancel route
    Route::post(
        uri: '{goal_getter_susu}/cancel',
        action: GoalGetterSusuCancelController::class
    )->name(
        name: 'goal_getter_susu.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
        ]
    );

    // Goal getter susu approval route
    Route::post(
        uri: '{goal_getter_susu}/approval',
        action: GoalGetterSusuApprovalController::class
    )->name(
        name: 'goal_getter_susu.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
        ]
    );

    // Get goal getter (all) susu route
    Route::get(
        uri: '',
        action: GoalGetterSusuIndexController::class
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Get goal getter (single) susu route
    Route::get(
        uri: '{goal_getter_susu}',
        action: GoalGetterSusuShowController::class,
    )->name(
        name: 'goal_getter_susu.show'
    )->whereUuid(
        parameters: [
            'customer',
            'goal_getter_susu',
        ]
    );
});
