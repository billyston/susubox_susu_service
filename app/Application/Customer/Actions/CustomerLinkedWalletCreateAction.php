<?php

declare(strict_types=1);

namespace App\Application\Customer\Actions;

use App\Application\Customer\DTOs\CustomerLinkedWalletDTO;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerLinkedWalletCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Customer\CustomerLinkedWalletResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CustomerLinkedWalletCreateAction
{
    private CustomerLinkedWalletCreateService $customerLinkedWalletCreateService;

    public function __construct(
        CustomerLinkedWalletCreateService $customerLinkedWalletCreateService
    ) {
        $this->customerLinkedWalletCreateService = $customerLinkedWalletCreateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Request $request,
    ): JsonResponse {
        // Build the CustomerLinkedWalletDTO
        $dto = CustomerLinkedWalletDTO::fromArray(
            payload: $request->all()
        );

        // Execute the CustomerLinkedWalletCreateService
        $linked_wallet = $this->customerLinkedWalletCreateService->execute(
            customer: $customer,
            data: $dto,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request successful.',
            description: 'Customer linked wallet created successfully.',
            data: new CustomerLinkedWalletResource(
                resource: $linked_wallet
            ),
        );
    }
}
