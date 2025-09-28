<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\DailySusu\DailySusuCreateController;
use App\Http\Controllers\V1\Susu\DailySusu\DailySusuGetController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create daily susu request route
    Route::post(
        uri: 'daily-susus',
        action: DailySusuCreateController::class,
    )->name(
        name: 'daily-susus'
    );

    // Get daily (single) susu route
    Route::get(
        uri: '{account}/daily-susus',
        action: DailySusuGetController::class
    )->name(
        name: 'daily-susus.get'
    )->whereUuid(
        parameters: ['account']
    );
});
