<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\CycleDefinition;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Account\Services\AccountCycleDefinition\AccountCycleDefinitionShowService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleDefinitionResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCycleDefinitionShowAction
{
    use Relationships;

    private AccountCycleDefinitionShowService $accountCycleDefinitionShowService;

    /**
     * @param AccountCycleDefinitionShowService $accountCycleDefinitionShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        AccountCycleDefinitionShowService $accountCycleDefinitionShowService
    ) {
        $this->accountCycleDefinitionShowService = $accountCycleDefinitionShowService;
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
        $accountCycleDefinition = $this->accountCycleDefinitionShowService->execute(
            customer: $customer,
            account: $dailySusu->account,
            accountCycleDefinition: $dailySusu->accountCycleDefinition
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $accountCycleDefinition->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new AccountCycleDefinitionResource(
                resource: $accountCycleDefinition,
            ),
        );
    }
}
