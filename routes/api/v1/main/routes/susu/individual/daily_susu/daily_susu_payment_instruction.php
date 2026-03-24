<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\PaymentInstruction\DailySusuPaymentInstructionIndexController;
use App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\PaymentInstruction\DailySusuPaymentInstructionShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/daily-susus/{daily_susu}/payment-instructions',
    'as' => 'customers.customer.daily_susus.daily_susu.payment_instructions',
], function (): void {
    // Get daily susu all payment instructions route
    Route::get(
        uri: '',
        action: DailySusuPaymentInstructionIndexController::class
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Get daily susu single payment instructions route
    Route::get(
        uri: '{payment_instruction}',
        action: DailySusuPaymentInstructionShowController::class
    )->name(
        name: 'payment_instruction.show'
    )->whereUuid(
        parameters: [
            'customer',
            'daily_susu',
            'payment_instruction',
        ],
    );
});
