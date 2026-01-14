<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu\AccountCycle;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountCycle\DailySusuAccountCycleIndexService;
use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleCollectionResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuAccountCycleIndexAction
{
    private DailySusuAccountCycleIndexService $dailySusuAccountCycleIndexService;

    /**
     * @param DailySusuAccountCycleIndexService $dailySusuAccountCycleIndexService
     */
    public function __construct(
        DailySusuAccountCycleIndexService $dailySusuAccountCycleIndexService,
    ) {
        $this->dailySusuAccountCycleIndexService = $dailySusuAccountCycleIndexService;
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
        // Execute the DailySusuAccountCycleIndexService and return the resource
        $accountCycles = $this->dailySusuAccountCycleIndexService->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountCycleCollectionResource::collection(
                resource: $accountCycles
            ),
        );
    }
}
