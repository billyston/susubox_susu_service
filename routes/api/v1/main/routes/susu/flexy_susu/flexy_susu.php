<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuCreateController;
use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuGetController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create flexy susu request route
    Route::post(
        uri: 'flexy-susus',
        action: FlexySusuCreateController::class,
    )->name(
        name: 'flexy-susus'
    );

    // Get flexy (single) susu route
    Route::get(
        uri: '{account}/flexy-susus',
        action: FlexySusuGetController::class,
    )->name(
        name: 'flexy-susus.get'
    )->whereUuid(
        parameters: ['account']
    );
});
