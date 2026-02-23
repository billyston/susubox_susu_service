<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AutoSettlement\DailySusuAutoSettlementController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/auto-settlement',
    'as' => 'customers.customer.daily_susus.daily_susu.auto_settlement.',
], function (): void {
    // Auto settlement (toggle) request route
    Route::post(
        uri: '',
        action: DailySusuAutoSettlementController::class,
    )->name(
        name: 'initiate'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
        ]
    );
});
