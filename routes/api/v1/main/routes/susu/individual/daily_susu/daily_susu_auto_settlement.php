<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountAutoSettlement\DailySusuAccountAutoSettlementController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/auto-settlement',
    'as' => 'customers.customer.daily_susus.daily_susu.auto_settlement.',
], function (): void {
    // Create account lock request route
    Route::post(
        uri: '',
        action: DailySusuAccountAutoSettlementController::class,
    )->name(
        name: 'initiate'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );
});
