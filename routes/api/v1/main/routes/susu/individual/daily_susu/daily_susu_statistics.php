<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Statistics\DailySusuStatisticsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/statistics',
    'as' => 'customers.customer.daily_susus.daily_susu.statistics.',
], function (): void {
    // Get daily susu statistics request route
    Route::get(
        uri: 'cycles',
        action: DailySusuStatisticsController::class,
    )->name(
        name: 'cycles.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );
});
