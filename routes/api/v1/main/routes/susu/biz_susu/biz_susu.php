<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Susu\BizSusu\BizSusuApprovalController;
use App\Http\Controllers\V1\Susu\BizSusu\BizSusuCancelController;
use App\Http\Controllers\V1\Susu\BizSusu\BizSusuCreateController;
use App\Http\Controllers\V1\Susu\BizSusu\BizSusuIndexController;
use App\Http\Controllers\V1\Susu\BizSusu\BizSusuShowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'customers/{customer}/accounts/',
    'as' => 'customers.customer.accounts.',
], function (): void {
    // Create biz susu request route
    Route::post(
        uri: 'biz-susus',
        action: BizSusuCreateController::class,
    )->name(
        name: 'biz-susus'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Biz susu cancel route
    Route::post(
        uri: '{account}/biz-susus/cancel',
        action: BizSusuCancelController::class
    )->name(
        name: 'biz-susus.cancel'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Biz susu approval route
    Route::post(
        uri: '{account}/biz-susus/approval',
        action: BizSusuApprovalController::class
    )->name(
        name: 'biz-susus.approval'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );

    // Get biz (all) susu route
    Route::get(
        uri: 'biz-susus',
        action: BizSusuIndexController::class
    )->name(
        name: 'biz-susus.index'
    )->whereUuid(
        parameters: [
            'customer',
        ]
    );

    // Get biz (single) susu route
    Route::get(
        uri: '{account}/biz-susus',
        action: BizSusuShowController::class,
    )->name(
        name: 'biz-susus.show'
    )->whereUuid(
        parameters: [
            'customer',
            'account',
        ]
    );
});
