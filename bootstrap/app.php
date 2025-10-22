<?php

declare(strict_types=1);

use App\Application\Customer\Commands\CustomerRedisStreamConsumer;
use App\Domain\Shared\Exceptions\ApiExceptionHandler;
use App\Interface\Http\Middleware\CertificateTransparencyPolicy;
use App\Interface\Http\Middleware\ContentTypes;
use App\Interface\Http\Middleware\IPWhiteListMiddleware;
use App\Interface\Http\Middleware\PermissionsPolicy;
use App\Interface\Http\Middleware\RateLimiterMiddleware;
use App\Interface\Http\Middleware\RemoveHeaders;
use App\Interface\Http\Middleware\SetReferrerPolicy;
use App\Interface\Http\Middleware\StrictTransportSecurity;
use App\Interface\Http\Middleware\XFrameOptionsMiddleware;
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
