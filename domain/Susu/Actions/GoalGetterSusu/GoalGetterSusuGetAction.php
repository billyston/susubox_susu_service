<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Data\GoalGetterSusu\GoalGetterSusuResource;
use Domain\Susu\Models\Account;
use Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuGetService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuGetAction
{
    private GoalGetterSusuGetService $goalGetterSusuGetService;

    public function __construct(
        GoalGetterSusuGetService $goalGetterSusuGetService
    ) {
        $this->goalGetterSusuGetService = $goalGetterSusuGetService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        Account $account,
    ): JsonResponse {
        // Execute the GoalGetterSusuGetService and return the resource
        $goal_getter_susu = $this->goalGetterSusuGetService->execute(
            customer: $customer,
            account: $account
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new GoalGetterSusuResource(
                resource: $goal_getter_susu
            ),
        );
    }
}
