<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Transaction\TransactionCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '{account}/transactions',
    'as' => 'account.transactions.',
], function (): void {
    // Create transaction request route
    Route::post(
        uri: '',
        action: TransactionCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'account',
        ]
    );
});
