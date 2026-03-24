<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\AutoSettlement\DailySusuAutoSettlementToggleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuAutoSettlementAction
{
    private DailySusuAutoSettlementToggleService $dailySusuAutoSettlementToggleService;

    /**
     * @param DailySusuAutoSettlementToggleService $dailySusuAutoSettlementToggleService
     */
    public function __construct(
        DailySusuAutoSettlementToggleService $dailySusuAutoSettlementToggleService
    ) {
        $this->dailySusuAutoSettlementToggleService = $dailySusuAutoSettlementToggleService;
    }

    /**
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the DailySusuAutoSettlementToggleService
        $dailySusu = $this->dailySusuAutoSettlementToggleService->execute(
            dailySusu: $dailySusu,
        );

        // Notification dispatcher goes here

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: $dailySusu->auto_payout === true ?
                'Your request is successful. Automated settlement is enabled.' :
                'Your request is successful. Automated settlement is disabled.',
        );
    }
}
