<?php

declare(strict_types=1);

namespace App\Http\Middleware\Common;

use App\Common\Helpers\ResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IPWhiteListMiddleware
{
    private array $ips = [
        '172.18.0.1',
        '172.21.0.1',
        '192.168.48.1',
    ];

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        // Return the error response (if ip does not exist)
        if (! in_array($request->ip(), $this->ips)) {
            return ResponseBuilder::error(
                status: false,
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
