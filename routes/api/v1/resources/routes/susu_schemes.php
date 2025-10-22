<?php

declare(strict_types=1);

use App\Interface\Http\Controllers\V1\Shared\SusuSchemesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '',
    'as' => '',
], function (): void {
    // Get all frequencies request route
    Route::get(
        uri: 'susu-schemes',
        action: SusuSchemesController::class,
    )->name(
        name: 'start-dates'
    );
});
