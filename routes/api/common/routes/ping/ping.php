<?php

declare(strict_types=1);

use App\Http\Controllers\Common\PingController;
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
