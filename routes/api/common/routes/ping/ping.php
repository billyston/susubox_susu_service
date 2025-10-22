<?php

declare(strict_types=1);

use App\Interface\Http\Controllers\V1\Shared\PingController;
use Illuminate\Support\Facades\Route;

// The ping route
Route::group([
    'prefix' => 'ping',
], function (): void {
    Route::get(
        uri: '',
        action: PingController::class
    )
        ->name(name: 'ping')
        ->middleware(middleware: ['rate_limiter:60,60,ping']);
});
