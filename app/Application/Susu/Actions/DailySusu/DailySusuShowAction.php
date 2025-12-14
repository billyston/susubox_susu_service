<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\DailySusu\DailySusuShowService;
use App\Interface\Resources\V1\Susu\DailySusu\DailySusuResource;
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
        $daily_susu = $this->dailySusuShowService->execute(
            customer: $customer,
            daily_susu: $daily_susu
        );

        logger()->info([$daily_susu]);

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new DailySusuResource(
                resource: $daily_susu
            ),
        );
    }
}
