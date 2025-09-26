<?php

declare(strict_types=1);

namespace Domain\Shared\Exceptions;

use App\Common\Helpers\ApiResponseBuilder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SusuSchemeNotFoundException extends Exception
{
    public function __construct(
        $message = 'Susu scheme not found'
    ) {
        parent::__construct($message, 404);
    }

    public function report(
    ): JsonResponse {
        return ApiResponseBuilder::error(
            code: Response::HTTP_NOT_FOUND,
            message: 'Resource not found',
            description: 'The susu scheme was not found.'
        );
    }
}
