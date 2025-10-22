<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\BizSusu;
use App\Domain\Susu\Services\BizSusu\BizSusuShowService;
use App\Interface\Http\Resources\V1\Susu\BizSusu\BizSusuResource;
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
