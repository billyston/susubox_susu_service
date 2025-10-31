<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Customer\CustomerCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers',
    'as' => 'customers.',
], function (): void {
    // Create new customer request route
    Route::post(
        uri: '',
        action: CustomerCreateController::class,
    )->name(
        name: ''
    );
});
