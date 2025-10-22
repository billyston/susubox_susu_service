<?php

declare(strict_types=1);

namespace App\Application\Customer\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerLinkedWalletCreateService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Resources\V1\Customer\CustomerLinkedWalletResource;
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
        // Extract the recipients data from the $request
        $data = Helpers::extractIncludedAttributes(
            request_data: data_get($request->all(), 'data.relationships.mobile_money_wallet')
        );

        // Execute the CustomerLinkedWalletCreateService
        $linked_wallet = $this->customerLinkedWalletCreateService->execute(
            customer: $customer,
            data: $data,
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
