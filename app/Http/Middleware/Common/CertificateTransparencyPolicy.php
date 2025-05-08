<?php

declare(strict_types=1);

namespace App\Http\Middleware\Common;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CertificateTransparencyPolicy
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        $response->headers->set(
            'Expect-CT',
            config('headers.certificate-transparency'),
        );

        return $response;
    }
}
