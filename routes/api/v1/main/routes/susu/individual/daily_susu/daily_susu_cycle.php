<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Cycle\DailySusuCycleIndexController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Cycle\DailySusuCycleShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/cycles',
    'as' => 'customers.customer.daily_susus.daily_susu.cycles',
], function (): void {
    // Get daily susu (all) route
    Route::get(
        uri: '',
        action: DailySusuCycleIndexController::class
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    )->middleware(
        middleware: [
            'rate_limiter:1,30',
        ],
    );

    // Get daily susu (single) route
    Route::get(
        uri: '{account_cycle}',
        action: DailySusuCycleShowController::class
    )->name(
        name: 'account_cycle.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_cycle',
        ],
    );
});
