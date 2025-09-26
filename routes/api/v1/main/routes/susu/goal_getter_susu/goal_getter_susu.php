<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create goal getter susu request route
    Route::post(
        uri: 'goal-getter-susus',
        action: GoalGetterSusuCreateController::class,
    )->name(
        name: 'goal-getter-susus'
    );
});
