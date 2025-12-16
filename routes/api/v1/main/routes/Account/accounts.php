<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Account\AccountBalanceController;
use App\Interface\Controllers\V1\Account\AccountIndexController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Account index request route
    Route::get(
        uri: '',
        action: AccountIndexController::class,
    )->name(
        name: 'index'
    );

    // Account balance request route
    Route::post(
        uri: '{account}/balances',
        action: AccountBalanceController::class,
    )->name(
        name: 'account.balances'
    )->whereUuid(
        parameters: [
            'account',
        ]
    );
});
