<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\FlexySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\FlexySusu\FlexySusuResource;
use Domain\Susu\Models\FlexySusu;
use Domain\Susu\Services\FlexySusu\FlexySusuShowService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuShowAction
{
    private FlexySusuShowService $flexySusuShowService;

    public function __construct(
        FlexySusuShowService $flexySusuShowService
    ) {
        $this->flexySusuShowService = $flexySusuShowService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexy_susu,
    ): JsonResponse {
        // Execute the FlexySusuShowService and return the resource
        $flexy_susu = $this->flexySusuShowService->execute(
            customer: $customer,
            account: $flexy_susu->account
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
