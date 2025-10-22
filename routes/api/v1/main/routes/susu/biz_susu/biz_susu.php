<?php

declare(strict_types=1);

use App\Interface\Http\Controllers\V1\Susu\BizSusu\BizSusuApprovalController;
use App\Interface\Http\Controllers\V1\Susu\BizSusu\BizSusuCancelController;
use App\Interface\Http\Controllers\V1\Susu\BizSusu\BizSusuCreateController;
use App\Interface\Http\Controllers\V1\Susu\BizSusu\BizSusuIndexController;
use App\Interface\Http\Controllers\V1\Susu\BizSusu\BizSusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/biz-susus/',
    'as' => 'customers.customer.biz-susus.',
], function (): void {
    // Create biz susu request route
    Route::post(
        uri: '',
        action: BizSusuCreateController::class,
    )->name(
        name: 'create'
    )->whereUuid(
        parameters: [
            'customer',
        ],
    );

    // Biz susu cancel route
    Route::post(
        uri: '{biz_susu}/cancel',
        action: BizSusuCancelController::class
    )->name(
        name: 'biz_susu.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );

    // Biz susu approval route
    Route::post(
        uri: '{biz_susu}/approval',
        action: BizSusuApprovalController::class
    )->name(
        name: 'biz_susu.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );

    // Get biz (all) susu route
    Route::get(
        uri: '',
        action: BizSusuIndexController::class
    )->name(
        name: 'index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Get biz (single) susu route
    Route::get(
        uri: '{biz_susu}',
        action: BizSusuShowController::class,
    )->name(
        name: 'biz_susu.show'
    )->whereUuid(
        parameters: [
            'customer',
            'biz_susu',
        ]
    );
});
