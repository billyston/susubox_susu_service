<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\RecurringDeposit;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Account\Services\AccountRecurringDeposit\AccountRecurringDepositShowService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\RecurringDepositResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuRecurringDepositShowAction
{
    use Relationships;

    private AccountRecurringDepositShowService $accountRecurringDepositShowService;

    /**
     * @param AccountRecurringDepositShowService $accountRecurringDepositShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        AccountRecurringDepositShowService $accountRecurringDepositShowService
    ) {
        $this->accountRecurringDepositShowService = $accountRecurringDepositShowService;
        $this->relationships = Helpers::includeResources();
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
        // Execute the AccountCycleDefinitionShowService
        $recurringDeposit = $this->accountRecurringDepositShowService->execute(
            customer: $customer,
            account: $dailySusu->account,
            recurringDeposit: $dailySusu->recurringDeposit,
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $recurringDeposit->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new RecurringDepositResource(
                resource: $recurringDeposit,
            ),
        );
    }
}
