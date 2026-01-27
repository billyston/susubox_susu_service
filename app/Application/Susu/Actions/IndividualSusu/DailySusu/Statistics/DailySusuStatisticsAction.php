<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Statistics;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\AccountStats\DailySusuAccountStatsResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuStatisticsAction
{
    public function __construct(
    ) {
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
        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new DailySusuAccountStatsResource(
                $dailySusu
            )
        );
    }
}
