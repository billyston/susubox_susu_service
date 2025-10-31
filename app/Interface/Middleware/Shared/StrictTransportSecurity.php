<?php

declare(strict_types=1);

namespace App\Interface\Middleware\Shared;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class StrictTransportSecurity
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        $response->headers->set(
            'Strict-Transport-Security',
            config('headers.strict-transport-security'),
        );

        return $response;
    }
}
