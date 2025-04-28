<?php

declare(strict_types=1);

namespace App\Common\Actions\Ping;

use App\Common\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PingAction
{
    public function __construct(
    ) {
        //..
    }

    public function execute(
        Request $request,
    ): JsonResponse {
        // Build and return the JsonResponse
        return ResponseBuilder::success(
            status: true,
            code: Response::HTTP_OK,
            message: 'Service is online'
        );
    }
}
