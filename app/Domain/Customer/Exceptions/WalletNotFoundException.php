<?php

declare(strict_types=1);

namespace App\Domain\Customer\Exceptions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class WalletNotFoundException extends Exception
{
    /**
     * @param $message
     */
    public function __construct(
        $message = 'Wallet not found'
    ) {
        parent::__construct($message, 404);
    }

    /**
     * @return JsonResponse
     */
    public function report(
    ): JsonResponse {
        return ApiResponseBuilder::error(
            code: Response::HTTP_NOT_FOUND,
            message: 'Resource not found',
            description: 'The wallet was not found.'
        );
    }
}
