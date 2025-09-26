<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Resources\StartDatesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '',
    'as' => '',
], function (): void {
    // Get all frequencies request route
    Route::get(
        uri: 'start-dates',
        action: StartDatesController::class,
    )->name(
        name: 'start-dates'
    );
});
