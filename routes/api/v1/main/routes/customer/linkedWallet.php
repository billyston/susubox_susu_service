<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Customer\CustomerLinkedWalletCreateController;
use App\Interface\Controllers\V1\Customer\CustomerLinkedWalletIndexController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}',
    'as' => 'customers.customer.',
], function (): void {
    // Linked wallet validation request route
    Route::post(
        uri: 'linked-wallets',
        action: CustomerLinkedWalletCreateController::class,
    )->name(
        name: 'customer.linked-wallets.create'
    )->whereUuid(
        parameters: 'customer'
    );

    // Get all linked wallet request route
    Route::get(
        uri: 'linked-wallets',
        action: CustomerLinkedWalletIndexController::class,
    )->name(
        name: 'customer.linked-wallets.index'
    )->whereUuid(
        parameters: 'customer'
    );
});
