<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu\AccountCycle;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountCycle\DailySusuAccountCycleShowService;
use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuAccountCycleShowAction
{
    private DailySusuAccountCycleShowService $dailySusuAccountCycleShowService;

    /**
     * @param DailySusuAccountCycleShowService $dailySusuAccountCycleShowService
     */
    public function __construct(
        DailySusuAccountCycleShowService $dailySusuAccountCycleShowService
    ) {
        $this->dailySusuAccountCycleShowService = $dailySusuAccountCycleShowService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountCycle $accountCycle
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        AccountCycle $accountCycle
    ): JsonResponse {
        // Execute the DailySusuAccountCycleShowService and return the resource
        $accountCycle = $this->dailySusuAccountCycleShowService->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountCycle: $accountCycle
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new AccountCycleResource(
                resource: $accountCycle
            ),
        );
    }
}
