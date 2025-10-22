<?php

declare(strict_types=1);

use App\Interface\Http\Controllers\V1\Shared\DurationsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '',
    'as' => '',
], function (): void {
    // Get all durations request route
    Route::get(
        uri: 'durations',
        action: DurationsController::class,
    )->name(
        name: 'durations'
    );
});
