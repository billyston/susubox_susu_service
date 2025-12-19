<?php

declare(strict_types=1);

namespace App\Application\Shared\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequenciesService;
use App\Interface\Resources\V1\Shared\FrequencyResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FrequenciesAction
{
    private FrequenciesService $frequenciesService;

    /**
     * @param FrequenciesService $frequenciesService
     */
    public function __construct(
        FrequenciesService $frequenciesService
    ) {
        $this->frequenciesService = $frequenciesService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        // Execute the FrequenciesService and return the Collection
        $frequencies = $this->frequenciesService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: FrequencyResource::collection(
                resource: $frequencies,
            ),
        );
    }
}
