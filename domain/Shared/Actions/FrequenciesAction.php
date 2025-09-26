<?php

declare(strict_types=1);

namespace Domain\Shared\Actions;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Shared\Data\FrequencyResource;
use Domain\Shared\Services\Frequency\FrequenciesService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FrequenciesAction
{
    private FrequenciesService $frequenciesService;

    public function __construct(
        FrequenciesService $frequenciesService
    ) {
        $this->frequenciesService = $frequenciesService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        $linked_wallets = $this->frequenciesService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: FrequencyResource::collection(
                resource: $linked_wallets,
            ),
        );
    }
}
