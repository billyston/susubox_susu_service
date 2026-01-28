<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Statistics\DailySusuCycleStatisticsController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Statistics\DailySusuSettlementStatisticsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/statistics',
    'as' => 'customers.customer.daily_susus.daily_susu.statistics.',
], function (): void {
    // Get daily susu cycle statistics request route
    Route::get(
        uri: 'cycles',
        action: DailySusuCycleStatisticsController::class,
    )->name(
        name: 'cycles.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );

    // Get daily susu settlement statistics request route
    Route::get(
        uri: 'settlements',
        action: DailySusuSettlementStatisticsController::class,
    )->name(
        name: 'settlements.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );
});
