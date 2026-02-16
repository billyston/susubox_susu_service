<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Transaction\TransactionCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'transactions/',
    'as' => 'transactions.',
], function (): void {
    // Create transaction request route
    Route::post(
        uri: '{payment_instruction}',
        action: TransactionCreateController::class,
    )->name(
        name: 'payment_instruction.create'
    )->whereUuid(
        parameters: [
            'payment_instruction',
        ]
    );
});
