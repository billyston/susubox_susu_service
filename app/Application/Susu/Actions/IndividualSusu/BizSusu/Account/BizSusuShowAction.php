<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\BizSusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Services\IndividualSusu\BizSusu\Account\BizSusuShowService;
use App\Interface\Resources\V1\Susu\IndividualSusu\BizSusu\BizSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuShowAction
{
    private BizSusuShowService $bizSusuShowService;

    /**
     * @param BizSusuShowService $bizSusuShowService
     */
    public function __construct(
        BizSusuShowService $bizSusuShowService
    ) {
        $this->bizSusuShowService = $bizSusuShowService;
    }

    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
    ): JsonResponse {
        // Execute the BizSusuShowService and return the resource
        $bizSusu = $this->bizSusuShowService->execute(
            customer: $customer,
            bizSusu: $bizSusu
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new BizSusuResource(
                resource: $bizSusu
            ),
        );
    }
}
