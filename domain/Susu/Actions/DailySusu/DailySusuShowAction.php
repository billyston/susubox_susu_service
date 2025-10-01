<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\DailySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\DailySusu\DailySusuResource;
use Domain\Susu\Models\DailySusu;
use Domain\Susu\Services\DailySusu\DailySusuShowService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuShowAction
{
    private DailySusuShowService $dailySusuShowService;

    public function __construct(
        DailySusuShowService $dailySusuShowService
    ) {
        $this->dailySusuShowService = $dailySusuShowService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $daily_susu,
    ): JsonResponse {
        // Execute the DailySusuShowService and return the resource
        $account = $this->dailySusuShowService->execute(
            customer: $customer,
            daily_susu: $daily_susu
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new DailySusuResource(
                resource: $account
            ),
        );
    }
}
