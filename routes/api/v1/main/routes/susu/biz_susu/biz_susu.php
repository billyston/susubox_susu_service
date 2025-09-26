<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\BizSusu\BizSusuCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create biz susu request route
    Route::post(
        uri: 'biz-susus',
        action: BizSusuCreateController::class,
    )->name(
        name: 'biz-susus'
    );
});
