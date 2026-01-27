<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Account\DailySusuShowService;
use App\Interface\Resources\V1\Susu\IndividualSusu\DailySusu\DailySusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuShowAction
{
    private DailySusuShowService $dailySusuShowService;

    /**
     * @param DailySusuShowService $dailySusuShowService
     */
    public function __construct(
        DailySusuShowService $dailySusuShowService
    ) {
        $this->dailySusuShowService = $dailySusuShowService;
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

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new DailySusuResource(
                resource: $dailySusu
            ),
        );
    }
}
