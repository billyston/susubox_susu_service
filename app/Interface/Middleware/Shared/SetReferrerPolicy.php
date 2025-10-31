<?php

declare(strict_types=1);

namespace App\Interface\Middleware\Shared;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetReferrerPolicy
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        $response->headers->set(
            'Referrer-Policy',
            config('headers.referrer-policy'),
        );

        return $response;
    }
}
