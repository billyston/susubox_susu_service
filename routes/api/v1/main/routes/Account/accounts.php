<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Account\AccountBalanceController;
use App\Interface\Controllers\V1\Account\AccountIndexController;
use App\Interface\Controllers\V1\Account\AccountShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}',
    'as' => 'customers.customer.',
], function (): void {
    // Account index request route
    Route::get(
        uri: 'individuals/accounts/',
        action: AccountIndexController::class,
    )->name(
        name: 'individuals.accounts.index'
    );

    // Account show request route
    Route::get(
        uri: 'individuals/accounts/{account}',
        action: AccountShowController::class,
    )->name(
        name: 'individuals.accounts.account.show'
    )->whereUuid(
        parameters: [
            'account',
        ]
    );

    // Account balance request route
    Route::post(
        uri: 'accounts/{account}/balances',
        action: AccountBalanceController::class,
    )->name(
        name: 'account.balances'
    )->whereUuid(
        parameters: [
            'account',
        ]
    );
});
