<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\DailySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\DailySusu\DailySusuCollectionResource;
use Domain\Susu\Services\DailySusu\DailySusuIndexService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuIndexAction
{
    private DailySusuIndexService $dailySusuIndexService;
    private SusuSchemeService $susuSchemeService;

    public function __construct(
        DailySusuIndexService $dailySusuIndexService,
        SusuSchemeService $susuSchemeService
    ) {
        $this->dailySusuIndexService = $dailySusuIndexService;
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
            scheme_code: config(key: 'susubox.susu_schemes.daily_susu_code')
        );

        // Execute the AccountBySchemeIndexService and return the resource
        $daily_susus = $this->dailySusuIndexService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: DailySusuCollectionResource::collection(
                resource: $daily_susus
            ),
        );
    }
}
