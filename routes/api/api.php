<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware([
    'api',
    'ip_whitelist',
])->group(function (): void {
    // Common routes (mainly for pinging)
    Route::prefix('')->group(function (): void {
        Route::as('')
            ->group(base_path('routes/api/common/routes.php'));
    });

    // V1 routes (for all version 1 routes)
    Route::prefix('v1')->group(function (): void {
        Route::as('v1:')
            ->group(base_path('routes/api/v1/routes.php'));
    });
});
