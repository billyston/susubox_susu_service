<?php

declare(strict_types=1);

namespace App\Interface\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContentTypes
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        $response->headers->add([
            'Accept', 'application/vnd.api+json',
            'Content-Type', 'application/vnd.api+json',
        ]);

        return $response;
    }
}
