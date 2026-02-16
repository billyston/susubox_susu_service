<?php

declare(strict_types=1);

use App\Interface\Controllers\V1\Account\Pause\AccountPauseController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'accounts',
    'as' => 'accounts.',
], function (): void {
    // Account pause request route
    Route::post(
        uri: 'pauses/{account_pause}',
        action: AccountPauseController::class,
    )->name(
        name: 'pauses.account_pause'
    );
});
