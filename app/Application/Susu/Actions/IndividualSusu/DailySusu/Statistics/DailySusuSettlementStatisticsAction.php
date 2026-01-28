<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Statistics;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Statistics\DailySusuSettlementStatisticsResponseDTO;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Statistics\DailySusuSettlementStatisticsService;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementStatisticsAction
{
    private DailySusuSettlementStatisticsService $susuSettlementStatisticsService;

    /**
     * @param DailySusuSettlementStatisticsService $susuSettlementStatisticsService
     */
    public function __construct(
        DailySusuSettlementStatisticsService $susuSettlementStatisticsService
    ) {
        $this->susuSettlementStatisticsService = $susuSettlementStatisticsService;
    }

    /**
     * @param DailySusu $dailySusu
     * @param array $request
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function execute(
        DailySusu $dailySusu,
        array $request
    ): JsonResponse {
        // Execute the DailySusuSettlementStatisticsService and return array
        $statistics = $this->susuSettlementStatisticsService->execute(
            account: $dailySusu->account,
            from: isset($request['from_date']) ? Carbon::parse($request['from_date']) : null,
            to: isset($request['to_date']) ? Carbon::parse($request['to_date']) : null,
        );

        // Build the DailySusuSettlementStatisticsResponseDTO
        $responseDTO = DailySusuSettlementStatisticsResponseDTO::fromDomain(
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
