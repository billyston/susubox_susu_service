<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exceptions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SystemFailureException extends Exception
{
    /**
     * @param string $message
     */
    public function __construct(
        string $message = 'Service is unavailable, please retry again later.'
    ) {
        parent::__construct($message, 401);
    }

    /**
     * @return JsonResponse
     */
    public function report(
    ): JsonResponse {
        return ApiResponseBuilder::error(
            code: Response::HTTP_SERVICE_UNAVAILABLE,
            message: 'Request unavailable.',
            description: 'Service is unavailable, please retry again later.'
        );
    }
}
