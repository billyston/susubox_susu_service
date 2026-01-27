<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementIndexService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementIndexAction
{
    private DailySusuSettlementIndexService $dailySusuSettlementIndexService;

    /**
     * @param DailySusuSettlementIndexService $dailySusuSettlementIndexService
     */
    public function __construct(
        DailySusuSettlementIndexService $dailySusuSettlementIndexService,
    ) {
        $this->dailySusuSettlementIndexService = $dailySusuSettlementIndexService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the DailySusuSettlementIndexService and return the resource
        $settlements = $this->dailySusuSettlementIndexService->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: DailySusuSettlementResource::collection(
                resource: $settlements
            ),
        );
    }
}
