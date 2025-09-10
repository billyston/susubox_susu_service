<?php

declare(strict_types=1);

namespace App\Exceptions\Common;

use App\Common\Helpers\ApiResponseBuilder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SystemFailureExec extends Exception
{
    public function report(
    ): JsonResponse {
        return ApiResponseBuilder::error(
            code: Response::HTTP_SERVICE_UNAVAILABLE,
            message: 'Request unavailable.',
            description: 'Service is unavailable, please retry again later.'
        );
    }
}
