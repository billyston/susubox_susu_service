<?php

declare(strict_types=1);

namespace App\Application\Customer\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerLinkedWalletsService;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Resources\V1\Customer\CustomerLinkedWalletResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CustomerLinkedWalletsAction
{
    private CustomerLinkedWalletsService $customerLinkedWalletsService;

    public function __construct(
        CustomerLinkedWalletsService $customerLinkedWalletsService
    ) {
        $this->customerLinkedWalletsService = $customerLinkedWalletsService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        Request $request,
    ): JsonResponse {
        $linked_wallets = $this->customerLinkedWalletsService->execute(
            customer: $customer,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: CustomerLinkedWalletResource::collection(
                resource: $linked_wallets,
            ),
        );
    }
}
