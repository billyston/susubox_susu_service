<?php

declare(strict_types=1);

namespace App\Application\Customer\Actions;

use App\Application\Customer\DTOs\CustomerWalletCreateRequestDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CustomerWalletCreateAction
{
    private CustomerWalletCreateService $customerWalletCreateService;

    public function __construct(
        CustomerWalletCreateService $customerWalletCreateService
    ) {
        $this->customerWalletCreateService = $customerWalletCreateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Request $request,
    ): JsonResponse {
        // Build the CustomerWalletDTO
        $requestDTO = CustomerWalletCreateRequestDTO::fromPayload(
            payload: $request->all()
        );

        // Execute the CustomerWalletCreateService
        $wallet = $this->customerWalletCreateService->execute(
            customer: $customer,
            requestDTO: $requestDTO,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request successful.',
            description: 'Customer wallet created successfully.',
            data: new CustomerWalletResource(
                resource: $wallet
            ),
        );
    }
}
