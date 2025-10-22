<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\FlexySusu;
use App\Domain\Susu\Services\FlexySusu\FlexySusuShowService;
use App\Interface\Http\Resources\V1\Susu\FlexySusu\FlexySusuResource;
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
