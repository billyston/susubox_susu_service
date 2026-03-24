<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\CycleDefinition\DailySusuCycleDefinitionShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/account-cycle-definitions',
    'as' => 'customers.customer.daily_susus.daily_susu.account_cycle_definitions',
], function (): void {
    // Get daily susu (single) route
    Route::get(
        uri: '',
        action: DailySusuCycleDefinitionShowController::class
    )->name(
        name: 'account_cycle_definitions.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ],
    );
});
