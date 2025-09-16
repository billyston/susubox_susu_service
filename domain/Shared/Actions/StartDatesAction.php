<?php

declare(strict_types=1);

namespace Domain\Shared\Actions;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureExec;
use Domain\Shared\Data\StartDateResource;
use Domain\Shared\Services\StartDatesService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StartDatesAction
{
    private StartDatesService $startDatesService;

    public function __construct(
        StartDatesService $startDatesService
    ) {
        $this->startDatesService = $startDatesService;
    }

    /**
     * @throws SystemFailureExec
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        $linked_wallets = $this->startDatesService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: StartDateResource::collection(
                resource: $linked_wallets,
            ),
        );
    }
}
