<?php

declare(strict_types=1);

namespace App\Interface\Http\Middleware;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IPWhiteListMiddleware
{
    private array $ips = [
        '192.168.224.1',
        '172.20.0.1',
        '172.21.0.1',
        '172.22.0.1',
        '172.23.0.1',
        '172.24.0.1',
        '172.25.0.1',
        '172.26.0.1',
        '172.27.0.1',
        '172.28.0.1',
        '172.29.0.1',
    ];

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        // Return the error response (if ip does not exist)
        if (! in_array($request->ip(), $this->ips)) {
            return ApiResponseBuilder::error(
                code: Response::HTTP_UNAUTHORIZED,
                message: 'Unauthorised access',
                description: 'Your ip address is not whitelisted'
            );
        }

        return $next(
            $request
        );
    }
}
