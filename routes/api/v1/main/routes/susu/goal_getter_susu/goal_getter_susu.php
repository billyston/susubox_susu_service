<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalController;
use App\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuCancelController;
use App\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateController;
use App\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuIndexController;
use App\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create goal getter susu request route
    Route::post(
        uri: 'goal-getter-susus',
        action: GoalGetterSusuCreateController::class,
    )->name(
        name: 'goal-getter-susus.'
    )->whereUuid(
        parameters: [
            'customer',
        ],
    );

    // Goal getter susu cancel route
    Route::post(
        uri: '{account}/goal-getter-susus/cancel',
        action: GoalGetterSusuCancelController::class
    )->name(
        name: 'goal-getter-susus.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Goal getter susu approval route
    Route::post(
        uri: '{account}/goal-getter-susus/approval',
        action: GoalGetterSusuApprovalController::class
    )->name(
        name: 'goal-getter-susus.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Get goal getter (all) susu route
    Route::get(
        uri: 'goal-getter-susus',
        action: GoalGetterSusuIndexController::class
    )->name(
        name: 'goal-getter-susus.index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Get goal getter (single) susu route
    Route::get(
        uri: '{account}/goal-getter-susus',
        action: GoalGetterSusuShowController::class,
    )->name(
        name: 'goal-getter-susus.show'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );
});
