<?php

declare(strict_types=1);

namespace App\Interface\Http\Middleware;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Services\RateLimiterService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RateLimiterMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        $maxAttempts = 60,
        $decaySeconds = 60,
        $group = 'default',
    ): Response {
        $limiter = new RateLimiterService(
            maxAttempts: (int) $maxAttempts,
            decaySeconds: (int) $decaySeconds,
            group: $group,
            ipBased: config('app.ip_based', true),
            userBased: config('app.user_based', false),
            apiKeyBased: config('app.api_key_based', false)
        );

        $limiter->forRequest($request);

        if (! $limiter->attempt()) {
            return ApiResponseBuilder::error(
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Unauthorised access',
                description: 'Too many attempts. Please try again later'
            );
        }

        $response = $next($request);

        foreach ($limiter->getHeaders() as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
