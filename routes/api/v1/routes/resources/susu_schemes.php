<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Resources\SusuSchemesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'resources',
    'as' => 'resources.',
], function (): void {
    // Get all frequencies request route
    Route::get(
        uri: 'susu-schemes',
        action: SusuSchemesController::class,
    )->name(
        name: 'start-dates'
    );
});
