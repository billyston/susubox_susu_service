<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\GoalGetterSusu\GoalGetterSusuResource;
use Domain\Susu\Services\Account\AccountBySchemeIndexService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuIndexAction
{
    private AccountBySchemeIndexService $accountBySchemeIndexService;
    private SusuSchemeService $susuSchemeService;

    public function __construct(
        AccountBySchemeIndexService $accountBySchemeIndexService,
        SusuSchemeService $susuSchemeService
    ) {
        $this->accountBySchemeIndexService = $accountBySchemeIndexService;
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

        // Execute the AccountBySchemeIndexService and return the resource
        $goal_getter_susus = $this->accountBySchemeIndexService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: GoalGetterSusuResource::collection(
                resource: $goal_getter_susus
            ),
        );
    }
}
