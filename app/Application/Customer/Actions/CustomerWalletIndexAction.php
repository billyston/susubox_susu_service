<?php

declare(strict_types=1);

namespace App\Application\Customer\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerWalletIndexService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Customer\CustomerWalletResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CustomerWalletIndexAction
{
    private CustomerWalletIndexService $customerLinkedWalletIndexService;

    /**
     * @param CustomerWalletIndexService $customerLinkedWalletIndexService
     */
    public function __construct(
        CustomerWalletIndexService $customerLinkedWalletIndexService
    ) {
        $this->customerLinkedWalletIndexService = $customerLinkedWalletIndexService;
    }

    /**
     * @param Customer $customer
     * @param Request $request
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Request $request,
    ): JsonResponse {
        $wallets = $this->customerLinkedWalletIndexService->execute(
            customer: $customer,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: CustomerWalletResource::collection(
                resource: $wallets,
            ),
        );
    }
}
