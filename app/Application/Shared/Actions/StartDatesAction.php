<?php

declare(strict_types=1);

namespace App\Application\Shared\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\StartDatesService;
use App\Interface\Http\Resources\V1\Shared\StartDateResource;
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
     * @throws SystemFailureException
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
