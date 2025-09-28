<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\Account\AccountIndexController;
use App\Http\Controllers\V1\Susu\BizSusu\BizSusuGetController;
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

    // Get biz (single) susu route
    Route::get(
        uri: '{account}/biz-susus',
        action: BizSusuGetController::class,
    )->name(
        name: 'biz-susus.get'
    )->whereUuid(
        parameters: ['account']
    );
});
