<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\DailySusu\DailySusuCreateController;
use App\Http\Controllers\V1\Susu\DailySusu\DailySusuGetController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/daily-susus',
    'as' => 'customers.customer.accounts.daily-susus.',
], function (): void {
    // Create daily susu request route
    Route::post(
        uri: '',
        action: DailySusuCreateController::class,
    )->name(
        name: ''
    );

    // Get daily susu route
    Route::get(
        uri: '{account}',
        action: DailySusuGetController::class
    )->name(
        name: 'show'
    )->whereUuid(
        parameters: ['account']
    );
});
