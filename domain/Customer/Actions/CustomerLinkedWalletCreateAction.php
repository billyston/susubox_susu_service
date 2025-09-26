<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use App\Common\Helpers\ApiResponseBuilder;
use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Customer\Services\CustomerLinkedWalletCreateService;
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
        $this->customerLinkedWalletCreateService->execute(
            customer: $customer,
            data: $data,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request successful.',
            description: 'Customer linked wallet created successfully.',
        );
    }
}
