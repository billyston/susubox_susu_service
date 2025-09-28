<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\BizSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\BizSusu\BizSusuResource;
use Domain\Susu\Models\Account;
use Domain\Susu\Services\BizSusu\BizSusuGetService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuGetAction
{
    private BizSusuGetService $bizSusuGetService;

    public function __construct(
        BizSusuGetService $bizSusuGetService
    ) {
        $this->bizSusuGetService = $bizSusuGetService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        Account $account,
    ): JsonResponse {
        // Execute the BizSusuGetService and return the resource
        $biz_susu = $this->bizSusuGetService->execute(
            customer: $customer,
            account: $account
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
