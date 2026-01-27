<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Account\AccountTransaction\AccountTransactionIndexController;
use App\Interface\Controllers\V1\Account\AccountTransaction\AccountTransactionShowController;
use App\Interface\Controllers\V1\Account\AccountTransaction\AccountTransactionStatisticsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}',
    'as' => 'customers.customer.',
], function (): void {
    // Account transactions request route
    Route::get(
        uri: 'accounts/{account}/transactions',
        action: AccountTransactionIndexController::class,
    )->name(
        name: 'account.transactions.index'
    )->whereUuid(
        parameters: [
            'account',
        ]
    );

    // Account transactions request route
    Route::get(
        uri: 'accounts/{account}/transactions/{transaction}',
        action: AccountTransactionShowController::class,
    )->name(
        name: 'account.transactions.show'
    )->whereUuid(
        parameters: [
            'account',
            'transaction',
        ]
    );

    // Account transactions statistics request route
    Route::get(
        uri: 'accounts/{account}/transactions/statistics',
        action: AccountTransactionStatisticsController::class,
    )->name(
        name: 'account.transactions.statistics'
    )->whereUuid(
        parameters: [
            'account',
            'transaction',
        ]
    );
});
