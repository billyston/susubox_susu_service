<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\FlexySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Services\IndividualSusu\FlexySusu\Account\FlexySusuShowService;
use App\Interface\Resources\V1\Susu\IndividualSusu\FlexySusu\FlexySusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuShowAction
{
    private FlexySusuShowService $flexySusuShowService;

    /**
     * @param FlexySusuShowService $flexySusuShowService
     */
    public function __construct(
        FlexySusuShowService $flexySusuShowService
    ) {
        $this->flexySusuShowService = $flexySusuShowService;
    }

    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
    ): JsonResponse {
        // Execute the FlexySusuShowService and return the resource
        $flexySusu = $this->flexySusuShowService->execute(
            customer: $customer,
            flexySusu: $flexySusu
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new FlexySusuResource(
                resource: $flexySusu
            ),
        );
    }
}
