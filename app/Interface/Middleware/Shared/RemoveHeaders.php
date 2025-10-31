<?php

declare(strict_types=1);

namespace App\Interface\Middleware\Shared;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RemoveHeaders
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        foreach ((array) config('headers.remove') as $header) {
            $response->headers->remove(
                key: $header,
            );
        }

        return $response;
    }
}
