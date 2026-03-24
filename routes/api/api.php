<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware([
    'api',
    'ip_whitelist',
])->group(function (): void {
    // Common main (mainly for pinging)
    Route::prefix('')->group(function (): void {
        Route::as('')
            ->group(base_path('routes/api/common/common.php'));
    });

    // V1 resources
    Route::prefix('v1/resources')->group(function (): void {
        Route::as('v1:resources.')
            ->group(base_path('routes/api/v1/resources/resources.php'));
    });

    // V1 main (for all version 1 main)
    Route::prefix('v1/')->group(function (): void {
        Route::as('v1:')
            ->group(base_path('routes/api/v1/main/main.php'));
    });
});
