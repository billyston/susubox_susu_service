<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountCycle\DailySusuAccountCycleIndexController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountCycle\DailySusuAccountCycleShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/account-cycles',
    'as' => 'customers.customer.daily_susus.daily_susu.account_cycles',
], function (): void {
    // Get daily (all) susu route
    Route::get(
        uri: '',
        action: DailySusuAccountCycleIndexController::class
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    )->middleware(middleware: ['rate_limiter:1,30']);

    // Get daily (single) susu route
    Route::get(
        uri: '{account_cycle}',
        action: DailySusuAccountCycleShowController::class
    )->name(
        name: 'account_cycle.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'account_cycle',
        ]
    );
});
