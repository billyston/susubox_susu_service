<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use App\Common\Helpers\ApiResponseBuilder;
use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Services\CustomerCreateService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CustomerCreateAction
{
    private CustomerCreateService $customerCreateService;

    public function __construct(
        CustomerCreateService $customerCreateService
    ) {
        $this->customerCreateService = $customerCreateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        // Extract the recipients data from the $request
        $data = Helpers::extractDataAttributes(
            request_data: $request->all()
        );

        // Execute the CustomerCreateService
        $this->customerCreateService->execute(
            data: $data,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request successful.',
            description: 'Customer created successfully.',
        );
    }
}
