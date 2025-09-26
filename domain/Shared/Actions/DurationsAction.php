<?php

declare(strict_types=1);

namespace Domain\Shared\Actions;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Shared\Data\DurationResource;
use Domain\Shared\Services\Duration\DurationsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DurationsAction
{
    private DurationsService $durationsService;

    public function __construct(
        DurationsService $durationsService
    ) {
        $this->durationsService = $durationsService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        $linked_wallets = $this->durationsService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: DurationResource::collection(
                resource: $linked_wallets,
            ),
        );
    }
}
