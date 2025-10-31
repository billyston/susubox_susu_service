<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\BizSusu\BizSusuIndexService;
use App\Interface\Resources\V1\Susu\BizSusu\BizSusuCollectionResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuIndexAction
{
    private BizSusuIndexService $bizSusuIndexService;
    private SusuSchemeService $susuSchemeService;

    public function __construct(
        BizSusuIndexService $bizSusuIndexService,
        SusuSchemeService $susuSchemeService
    ) {
        $this->bizSusuIndexService = $bizSusuIndexService;
        $this->susuSchemeService = $susuSchemeService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     * @throws SusuSchemeNotFoundException
     */
    public function execute(
        Customer $customer,
    ): JsonResponse {
        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.biz_susu_code')
        );

        // Execute the BizSusuIndexService and return the resource
        $biz_susus = $this->bizSusuIndexService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: BizSusuCollectionResource::collection(
                resource: $biz_susus
            ),
        );
    }
}
