<?php

declare(strict_types=1);

use App\Interface\Http\Controllers\V1\Customer\CustomerLinkedWalletsController;
use App\Interface\Http\Controllers\V1\Customer\CustomerLinkedWalletValidationController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers',
    'as' => 'customers.',
], function (): void {
    // Linked wallet validation request route
    Route::post(
        uri: '/{customer}/linked-wallets/validations',
        action: CustomerLinkedWalletValidationController::class,
    )->name(
        name: 'customer.linked-wallets.validations'
    )->whereUuid(
        parameters: 'customer'
    );

    // Get all linked wallet request route
    Route::get(
        uri: '/{customer}/linked-wallets',
        action: CustomerLinkedWalletsController::class,
    )->name(
        name: 'customer.linked-wallets'
    )->whereUuid(
        parameters: 'customer'
    );
});
