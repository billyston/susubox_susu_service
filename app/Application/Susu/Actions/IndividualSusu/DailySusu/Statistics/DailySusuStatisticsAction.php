<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Statistics;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Statistics\DailySusuStatisticsResponseDTO;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Statistics\DailySusuCycleStatisticsService;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuStatisticsAction
{
    private DailySusuCycleStatisticsService $susuCycleStatisticsService;

    /**
     * @param DailySusuCycleStatisticsService $susuCycleStatisticsService
     */
    public function __construct(
        DailySusuCycleStatisticsService $susuCycleStatisticsService
    ) {
        $this->susuCycleStatisticsService = $susuCycleStatisticsService;
    }

    /**
     * @param DailySusu $dailySusu
     * @param array $request
     * @return JsonResponse
     */
    public function execute(
        DailySusu $dailySusu,
        array $request
    ): JsonResponse {
        // Execute the DailySusuCycleStatisticsService and return array
        $statistics = $this->susuCycleStatisticsService->execute(
            account: $dailySusu->account,
            from: isset($request['from_date']) ? Carbon::parse($request['from_date']) : null,
            to: isset($request['to_date']) ? Carbon::parse($request['to_date']) : null,
        );

        // Build the DailySusuStatisticsResponseDTO
        $responseDTO = DailySusuStatisticsResponseDTO::fromDomain(
            statistics: $statistics,
            request: $request,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::toArray(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: $responseDTO->toArray(),
        );
    }
}
