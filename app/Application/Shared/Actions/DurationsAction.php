<?php

declare(strict_types=1);

namespace App\Application\Shared\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\DurationsService;
use App\Interface\Resources\V1\Shared\DurationResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DurationsAction
{
    private DurationsService $durationsService;

    /**
     * @param DurationsService $durationsService
     */
    public function __construct(
        DurationsService $durationsService
    ) {
        $this->durationsService = $durationsService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        // Execute the DurationsService and return the Collection
        $durations = $this->durationsService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: DurationResource::collection(
                resource: $durations,
            ),
        );
    }
}
