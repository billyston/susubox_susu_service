<?php

declare(strict_types=1);

namespace App\Interface\Http\Middleware;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AllowMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$methods
    ): Response {
        $response = $next($request);

        if (! in_array($request->method(), $methods)) {
            return ApiResponseBuilder::error(
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Unauthorised access.',
                description: 'This method not allowed on this endpoint.'
            );
        }

        return $response;
    }
}
