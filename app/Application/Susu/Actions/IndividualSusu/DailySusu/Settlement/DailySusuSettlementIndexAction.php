<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Services\Settlement\SettlementIndexService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\SettlementResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuSettlementIndexAction
{
    use Relationships;

    private SettlementIndexService $settlementIndexService;

    /**
     * @param SettlementIndexService $settlementIndexService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        SettlementIndexService $settlementIndexService,
    ) {
        $this->settlementIndexService = $settlementIndexService;
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
        // Execute the SettlementIndexService and return the resource
        $settlements = $this->settlementIndexService->execute(
            customer: $customer,
            account: $dailySusu->account,
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $settlements->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: SettlementResource::collection(
                resource: $settlements
            ),
        );
    }
}
