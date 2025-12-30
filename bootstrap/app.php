<?php

declare(strict_types=1);

use App\Application\Account\Schedulers\AccountUnLockScheduler;
use App\Application\Customer\Commands\CustomerRedisStreamConsumer;
use App\Domain\Shared\Exceptions\ApiExceptionHandler;
use App\Interface\Middleware\Shared\CertificateTransparencyPolicy;
use App\Interface\Middleware\Shared\ContentTypes;
use App\Interface\Middleware\Shared\IPWhiteListMiddleware;
use App\Interface\Middleware\Shared\PermissionsPolicy;
use App\Interface\Middleware\Shared\RateLimiterMiddleware;
use App\Interface\Middleware\Shared\RemoveHeaders;
use App\Interface\Middleware\Shared\SetReferrerPolicy;
use App\Interface\Middleware\Shared\StrictTransportSecurity;
use App\Interface\Middleware\Shared\XFrameOptionsMiddleware;
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
            // Schedule the AccountUnLockScheduler
            $schedule
                ->job(job: AccountUnLockScheduler::class)
                ->everyMinute()
                ->withoutOverlapping()
                ->onOneServer();
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
