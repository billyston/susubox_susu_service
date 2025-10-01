<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\GoalGetterSusu\GoalGetterSusuCollectionResource;
use Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuIndexService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuIndexAction
{
    private GoalGetterSusuIndexService $goalGetterSusuIndexService;
    private SusuSchemeService $susuSchemeService;

    public function __construct(
        GoalGetterSusuIndexService $goalGetterSusuIndexService,
        SusuSchemeService $susuSchemeService
    ) {
        $this->goalGetterSusuIndexService = $goalGetterSusuIndexService;
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
            scheme_code: config(key: 'susubox.susu_schemes.goal_getter_susu_code')
        );

        // Execute the GoalGetterSusuIndexService and return the resource
        $goal_getter_susus = $this->goalGetterSusuIndexService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: GoalGetterSusuCollectionResource::collection(
                resource: $goal_getter_susus
            ),
        );
    }
}
