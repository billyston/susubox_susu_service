<?php

declare(strict_types=1);

namespace App\Domain\Customer\Exceptions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class LinkedWalletNotFoundException extends Exception
{
    public function __construct(
        $message = 'Wallet not found'
    ) {
        parent::__construct($message, 404);
    }

    public function report(
    ): JsonResponse {
        return ApiResponseBuilder::error(
            code: Response::HTTP_NOT_FOUND,
            message: 'Resource not found',
            description: 'The linked wallet was not found.'
        );
    }
}
