<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\FailedDebitRollover\DailySusuFailedDebitRolloverController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/deposit-rollover',
    'as' => 'customers.customer.daily_susus.daily_susu.deposit_rollover.',
], function (): void {
    // Failed deposit rollover (toggle) request route
    Route::post(
        uri: '',
        action: DailySusuFailedDebitRolloverController::class,
    )->name(
        name: 'initiate'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );
});
