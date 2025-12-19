<?php

declare(strict_types=1);

namespace App\Application\Customer\Actions;

use App\Application\Customer\DTOs\CustomerCreateRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Services\CustomerCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Customer\CustomerResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CustomerCreateAction
{
    private CustomerCreateService $customerCreateService;

    /**
     * @param CustomerCreateService $customerCreateService
     */
    public function __construct(
        CustomerCreateService $customerCreateService
    ) {
        $this->customerCreateService = $customerCreateService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        // Build the CustomerCreateDTO
        $requestDTO = CustomerCreateRequestDTO::fromPayload(
            payload: $request->all()
        );

        // Execute the CustomerCreateService
        $customer = $this->customerCreateService->execute(
            requestDTO: $requestDTO,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request successful.',
            description: 'Customer created successfully.',
            data: new CustomerResource(
                resource: $customer
            ),
        );
    }
}
