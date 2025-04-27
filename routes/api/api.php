<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Common routes (mainly for pinging)
Route::prefix('')->group(function () {
    Route::as('')
        ->group(base_path('routes/api/common/routes.php'));
});

// V1 routes (for all version 1 routes)
Route::prefix('v1')->group(function () {
    Route::as('v1:')
        ->group(base_path('routes/api/v1/routes.php'));
});
