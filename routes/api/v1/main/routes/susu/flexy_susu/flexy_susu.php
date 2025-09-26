<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\FlexySusu\FlexySusuCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create Flexy susu request route
    Route::post(
        uri: 'flexy-susus',
        action: FlexySusuCreateController::class,
    )->name(
        name: 'flexy-susus'
    );
});
