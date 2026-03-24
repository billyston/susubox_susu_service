<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Cycle;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle\DailySusuCycleShowService;
use App\Interface\Resources\V1\Account\AccountCycle\AccountCycleResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCycleShowAction
{
    use Relationships;

    private DailySusuCycleShowService $dailySusuAccountCycleShowService;

    /**
     * @param DailySusuCycleShowService $dailySusuAccountCycleShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        DailySusuCycleShowService $dailySusuAccountCycleShowService
    ) {
        $this->dailySusuAccountCycleShowService = $dailySusuAccountCycleShowService;
        $this->relationships = Helpers::includeResources();
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
        AccountCycle $accountCycle,
    ): JsonResponse {
        // Execute the DailySusuCycleShowService and return the resource
        $accountCycle = $this->dailySusuAccountCycleShowService->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            accountCycle: $accountCycle
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $accountCycle->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new AccountCycleResource(
                resource: $accountCycle,
            ),
        );
    }
}
