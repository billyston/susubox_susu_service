<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateRequest;
use Domain\Customer\Models\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuCreateAction
{
    public function __construct(
    ) {
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusuCreateRequest $goalGetterSusuCreateRequest
    ): JsonResponse {
        // Extract the main attributes
        $data = Helpers::extractDataAttributes(
            request_data: $goalGetterSusuCreateRequest->all()
        );

        logger($data);

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request successful.',
            description: 'Customer created successfully.',
        );
    }
}
