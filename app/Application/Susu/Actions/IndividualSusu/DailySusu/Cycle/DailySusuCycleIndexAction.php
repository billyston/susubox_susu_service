<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Cycle;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle\DailySusuCycleIndexService;
use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCycleIndexAction
{
    use Relationships;

    private DailySusuCycleIndexService $dailySusuAccountCycleIndexService;

    /**
     * @param DailySusuCycleIndexService $dailySusuAccountCycleIndexService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        DailySusuCycleIndexService $dailySusuAccountCycleIndexService,
    ) {
        $this->dailySusuAccountCycleIndexService = $dailySusuAccountCycleIndexService;
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
        // Execute the DailySusuCycleIndexService and return the resource
        $accountCycles = $this->dailySusuAccountCycleIndexService->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $accountCycles->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: AccountCycleResource::collection(
                resource: $accountCycles
            ),
        );
    }
}
