<?php

declare(strict_types=1);

use App\Exceptions\ApiExceptionRenderer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api/api.php',
        commands: __DIR__ . '/../routes/console/console.php',
        health: '/up',
    )->withMiddleware(
        function (
            Middleware $middleware
        ): void {
            //
        })->withExceptions(
        using: function (
            Exceptions $exceptions
        ): void {
            $exceptions->render(
                using: function (
                    Throwable $throwable, Request $request
                ) {
                    if ($request->expectsJson()) return (new ApiExceptionRenderer(
                        exception: $throwable,
                        request: $request,
                    ))->render();

                    return null;
                },
            );
        })->create();
