<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Services\Settlement\SettlementShowService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\SettlementResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementShowAction
{
    use Relationships;

    private SettlementShowService $settlementShowService;

    /**
     * @param SettlementShowService $settlementShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        SettlementShowService $settlementShowService
    ) {
        $this->settlementShowService = $settlementShowService;
        $this->relationships = Helpers::includeResources();
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param Settlement $settlement
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        Settlement $settlement,
    ): JsonResponse {
        // Execute the SettlementShowService and return the resource
        $settlement = $this->settlementShowService->execute(
            customer: $customer,
            account: $dailySusu->account,
            settlement: $settlement
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $settlement->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new SettlementResource(
                resource: $settlement,
            ),
        );
    }
}
