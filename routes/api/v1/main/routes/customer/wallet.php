<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Customer\CustomerWalletCreateController;
use App\Interface\Controllers\V1\Customer\CustomerWalletIndexController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}',
    'as' => 'customers.customer.',
], function (): void {
    // Wallet validation request route
    Route::post(
        uri: 'wallets',
        action: CustomerWalletCreateController::class,
    )->name(
        name: 'customer.wallets.create'
    )->whereUuid(
        parameters: 'customer'
    );

    // Get all wallet request route
    Route::get(
        uri: 'wallets',
        action: CustomerWalletIndexController::class,
    )->name(
        name: 'customer.wallets.index'
    )->whereUuid(
        parameters: 'customer'
    );
});
