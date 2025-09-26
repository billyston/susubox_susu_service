<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\DailySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Susu\Data\DailySusu\DailySusuResource;
use Domain\Susu\Models\Account;
use Domain\Susu\Services\DailySusu\DailySusuGetService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuGetAction
{
    private DailySusuGetService $dailySusuGetService;

    public function __construct(
        DailySusuGetService $dailySusuGetService
    ) {
        $this->dailySusuGetService = $dailySusuGetService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Account $account,
    ): JsonResponse {
        // Execute the DailySusuGetService and return the resource
        $account = $this->dailySusuGetService->execute(
            customer: $customer,
            account: $account
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
