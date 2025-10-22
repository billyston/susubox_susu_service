<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuShowService;
use App\Interface\Http\Resources\V1\Susu\GoalGetterSusu\GoalGetterSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuShowAction
{
    private GoalGetterSusuShowService $goalGetterSusuShowService;

    public function __construct(
        GoalGetterSusuShowService $goalGetterSusuShowService
    ) {
        $this->goalGetterSusuShowService = $goalGetterSusuShowService;
    }

    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusu $goal_getter_susu,
    ): JsonResponse {
        // Execute the GoalGetterSusuShowService and return the resource
        $goal_getter_susu = $this->goalGetterSusuShowService->execute(
            customer: $customer,
            account: $goal_getter_susu->account
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
