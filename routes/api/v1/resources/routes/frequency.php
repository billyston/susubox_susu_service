<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Shared\FrequenciesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '',
    'as' => '',
], function (): void {
    // Get all frequencies request route
    Route::get(
        uri: 'frequencies',
        action: FrequenciesController::class,
    )->name(
        name: 'frequencies'
    );
});
