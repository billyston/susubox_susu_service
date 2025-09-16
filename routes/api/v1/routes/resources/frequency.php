<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Resources\FrequenciesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'resources',
    'as' => 'resources.',
], function (): void {
    // Get all frequencies request route
    Route::get(
        uri: 'frequencies',
        action: FrequenciesController::class,
    )->name(
        name: 'frequencies'
    );
});
