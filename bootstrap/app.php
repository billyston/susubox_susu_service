<?php

declare(strict_types=1);

use App\Exceptions\Common\ApiExceptionRenderer;
use App\Http\Middleware\Common\IPWhiteListMiddleware;
use App\Http\Middleware\Common\RateLimiterMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api/api.php',
        commands: __DIR__ . '/../routes/console/console.php',
        health: '/up'
    )->withMiddleware(
        function (
            Middleware $middleware
        ): void {
            $middleware->alias([
                'ip_whitelist' => IPWhiteListMiddleware::class,
                'rate_limiter' => RateLimiterMiddleware::class,
            ]);
        })->withExceptions(
        using: function (
            Exceptions $exceptions
        ): void {
            $exceptions->render(
                using: function (
                    Throwable $throwable,
                    Request $request
                ): ?\Illuminate\Http\JsonResponse {
                    if ($request->expectsJson()) {
                        return (new ApiExceptionRenderer(
                            exception: $throwable,
                            request: $request,
                        ))->render();
                    }

                    return null;
                },
            );
        })->create();
