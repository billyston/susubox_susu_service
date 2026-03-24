<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\RecurringDeposit\DailySusuRecurringDepositShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/recurring-deposits',
    'as' => 'customers.customer.daily_susus.daily_susu.recurring_deposits.',
], function (): void {
    // Get daily susu (single) route
    Route::get(
        uri: '',
        action: DailySusuRecurringDepositShowController::class
    )->name(
        name: 'recurring_deposit.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ],
    );
});
