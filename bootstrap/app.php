<?php

declare(strict_types=1);

use App\Console\Commands\CustomerRedisStreamConsumer;
use App\Exceptions\Common\ApiExceptionHandler;
use App\Http\Middleware\Common\CertificateTransparencyPolicy;
use App\Http\Middleware\Common\ContentTypes;
use App\Http\Middleware\Common\IPWhiteListMiddleware;
use App\Http\Middleware\Common\PermissionsPolicy;
use App\Http\Middleware\Common\RateLimiterMiddleware;
use App\Http\Middleware\Common\RemoveHeaders;
use App\Http\Middleware\Common\SetReferrerPolicy;
use App\Http\Middleware\Common\StrictTransportSecurity;
use App\Http\Middleware\Common\XFrameOptionsMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api/api.php',
        commands: __DIR__ . '/../routes/console/console.php',
        health: '/up'
    )->withMiddleware(
        function (
            Middleware $middleware
        ): void {
            // Define middleware groups
            $middleware->api([
                RemoveHeaders::class,
                StrictTransportSecurity::class,
                SetReferrerPolicy::class,
                PermissionsPolicy::class,
                CertificateTransparencyPolicy::class,
                ContentTypes::class,
                XFrameOptionsMiddleware::class,
            ]);

            // Define middleware aliases
            $middleware->alias([
                'ip_whitelist' => IPWhiteListMiddleware::class,
                'rate_limiter' => RateLimiterMiddleware::class,
            ]);
        }
    )->withCommands([
        CustomerRedisStreamConsumer::class
    ])->withSchedule(
        function (
            Schedule $schedule
        ): void {
            //..
        }
    )->withExceptions(
        using: function (
            Exceptions $exceptions
        ): void {
            $exceptions->render(
                using: function (Throwable $throwable, Request $request): ?JsonResponse {
                    if ($request->expectsJson()) {
                        return app(ApiExceptionHandler::class)
                            ->render($request, $throwable);
                    }
                    return null;
                },
            );
        })->create();
