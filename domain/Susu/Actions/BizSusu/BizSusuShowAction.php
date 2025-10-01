<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\BizSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\BizSusu\BizSusuResource;
use Domain\Susu\Models\BizSusu;
use Domain\Susu\Services\BizSusu\BizSusuShowService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuShowAction
{
    private BizSusuShowService $bizSusuShowService;

    public function __construct(
        BizSusuShowService $bizSusuShowService
    ) {
        $this->bizSusuShowService = $bizSusuShowService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        BizSusu $biz_susu,
    ): JsonResponse {
        // Execute the BizSusuShowService and return the resource
        $biz_susu = $this->bizSusuShowService->execute(
            customer: $customer,
            biz_susu: $biz_susu
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new BizSusuResource(
                resource: $biz_susu
            ),
        );
    }
}
