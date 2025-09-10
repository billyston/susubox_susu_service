<?php

declare(strict_types=1);

namespace App\Common\Actions\Ping;

use App\Common\Helpers\ApiResponseBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PingAction
{
    public function __construct(
    ) {
        //..
    }

    public function execute(): JsonResponse
    {
        // Build and return the JsonResponse
        return ApiResponseBuilder::ping(
            status: true,
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: 'This service is online'
        );
    }
}
