<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Services\GoalGetterSusu\GoalGetterSusuShowService;
use App\Interface\Resources\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuShowAction
{
    private GoalGetterSusuShowService $goalGetterSusuShowService;

    /**
     * @param GoalGetterSusuShowService $goalGetterSusuShowService
     */
    public function __construct(
        GoalGetterSusuShowService $goalGetterSusuShowService
    ) {
        $this->goalGetterSusuShowService = $goalGetterSusuShowService;
    }

    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
    ): JsonResponse {
        // Execute the GoalGetterSusuShowService and return the resource
        $goalGetterSusu = $this->goalGetterSusuShowService->execute(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            data: new GoalGetterSusuResource(
                resource: $goalGetterSusu
            ),
        );
    }
}
