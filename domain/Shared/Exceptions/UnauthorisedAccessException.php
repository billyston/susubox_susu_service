<?php

declare(strict_types=1);

namespace Domain\Shared\Exceptions;

use App\Common\Helpers\ApiResponseBuilder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UnauthorisedAccessException extends Exception
{
    public function __construct(
        $message = 'You are forbidden to perform this action.'
    ) {
        parent::__construct($message, 404);
    }

    public function report(
    ): JsonResponse {
        return ApiResponseBuilder::error(
            code: Response::HTTP_NOT_FOUND,
            message: 'Request forbidden.',
            description: 'You are forbidden to perform this action.'
        );
    }
}
