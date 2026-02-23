<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementShowService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuSettlementResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementShowAction
{
    private DailySusuSettlementShowService $dailySusuSettlementShowService;

    /**
     * @param DailySusuSettlementShowService $dailySusuSettlementShowService
     */
    public function __construct(
        DailySusuSettlementShowService $dailySusuSettlementShowService
    ) {
        $this->dailySusuSettlementShowService = $dailySusuSettlementShowService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param Settlement $accountSettlement
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        Settlement $accountSettlement,
    ): JsonResponse {
        // Execute the DailySusuSettlementShowService and return the resource
        $accountSettlement = $this->dailySusuSettlementShowService->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountSettlement: $accountSettlement
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new DailySusuSettlementResource(
                resource: $accountSettlement
            ),
        );
    }
}
