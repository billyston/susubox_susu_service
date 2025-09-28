<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\FlexySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\FlexySusu\FlexySusuCreateRequest;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Customer\Services\CustomerLinkedWalletService;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\FlexySusu\FlexySusuResource;
use Domain\Susu\Services\FlexySusu\FlexySusuCreateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuCreateAction
{
    private CustomerLinkedWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FlexySusuCreateService $flexySusuCreateService;

    public function __construct(
        CustomerLinkedWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FlexySusuCreateService $flexySusuCreateService
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->flexySusuCreateService = $flexySusuCreateService;
    }

    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     */
    public function execute(
        Customer $customer,
        FlexySusuCreateRequest $flexySusuCreateRequest
    ): JsonResponse {
        // Extract the main attributes from the request body
        $request_data = Helpers::extractDataAttributes(
            request_data: $flexySusuCreateRequest->all()
        );

        // Extract the linked_wallet from the request body
        $request_wallet = Helpers::extractIncludedAttributes(
            request_data: data_get($flexySusuCreateRequest->all(), 'data.relationships.linked_wallet')
        );

        // Execute the CustomerLinkedWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $request_wallet['resource_id'],
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.flexy_susu_code')
        );

        // Execute the FlexySusuCreateService and return the resource
        $flexy_susu = $this->flexySusuCreateService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme,
            linked_wallet: $linked_wallet,
            request_data: $request_data
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your flexy susu account is created pending approval.',
            data: new FlexySusuResource(
                resource: $flexy_susu->refresh()
            ),
        );
    }
}
