<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\FlexySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\FlexySusu\FlexySusuResource;
use Domain\Susu\Models\Account;
use Domain\Susu\Services\FlexySusu\FlexySusuGetService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuGetAction
{
    private FlexySusuGetService $flexySusuGetService;

    public function __construct(
        FlexySusuGetService $flexySusuGetService
    ) {
        $this->flexySusuGetService = $flexySusuGetService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        Account $account,
    ): JsonResponse {
        // Execute the FlexySusuGetService and return the resource
        $flexy_susu = $this->flexySusuGetService->execute(
            customer: $customer,
            account: $account
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new FlexySusuResource(
                resource: $flexy_susu
            ),
        );
    }
}
