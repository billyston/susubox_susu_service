<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\FlexySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\FlexySusu\FlexySusuCollectionResource;
use Domain\Susu\Services\FlexySusu\FlexySusuIndexService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuIndexAction
{
    private FlexySusuIndexService $flexySusuIndexService;
    private SusuSchemeService $susuSchemeService;

    public function __construct(
        FlexySusuIndexService $flexySusuIndexService,
        SusuSchemeService $susuSchemeService
    ) {
        $this->flexySusuIndexService = $flexySusuIndexService;
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
            scheme_code: config(key: 'susubox.susu_schemes.flexy_susu_code')
        );

        // Execute the FlexySusuIndexService and return the resource
        $flexy_susus = $this->flexySusuIndexService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: FlexySusuCollectionResource::collection(
                resource: $flexy_susus
            ),
        );
    }
}
