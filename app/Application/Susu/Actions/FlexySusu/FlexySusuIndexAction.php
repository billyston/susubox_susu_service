<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\FlexySusu\FlexySusuIndexService;
use App\Interface\Http\Resources\V1\Susu\FlexySusu\FlexySusuCollectionResource;
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
