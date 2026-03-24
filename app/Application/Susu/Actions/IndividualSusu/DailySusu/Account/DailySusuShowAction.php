<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Application\Shared\Helpers\Relationships;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Account\DailySusuShowService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuShowAction
{
    use Relationships;

    private DailySusuShowService $dailySusuShowService;

    /**
     * @param DailySusuShowService $dailySusuShowService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        DailySusuShowService $dailySusuShowService
    ) {
        $this->dailySusuShowService = $dailySusuShowService;
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
        // Execute the DailySusuShowService and return the resource
        $dailySusu = $this->dailySusuShowService->execute(
            customer: $customer,
            dailySusu: $dailySusu
        );

        // (Guard) Load related resources if exist
        if ($this->loadRelationships()) {
            $dailySusu->load($this->relationships);
        }

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new DailySusuResource(
                resource: $dailySusu,
            ),
        );
    }
}
