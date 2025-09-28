<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\DailySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Common\Helpers\Helpers;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\DailySusu\DailySusuCreateRequest;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Customer\Services\CustomerLinkedWalletService;
use Domain\Shared\Exceptions\FrequencyNotFoundException;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Services\Frequency\FrequencyService;
use Domain\Shared\Services\Scheme\SusuSchemeService;
use Domain\Susu\Data\DailySusu\DailySusuResource;
use Domain\Susu\Services\DailySusu\DailySusuCreateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCreateAction
{
    private CustomerLinkedWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;
    private DailySusuCreateService $dailySusuCreateService;

    public function __construct(
        CustomerLinkedWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FrequencyService $frequencyService,
        DailySusuCreateService $dailySusuCreateService
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->frequencyService = $frequencyService;
        $this->dailySusuCreateService = $dailySusuCreateService;
    }

    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws FrequencyNotFoundException
     */
    public function execute(
        Customer $customer,
        DailySusuCreateRequest $dailySusuCreateRequest
    ): JsonResponse {
        // Extract the main attributes from the request body
        $request_data = Helpers::extractDataAttributes(
            request_data: $dailySusuCreateRequest->all()
        );

        // Extract the linked_wallet from the request body
        $request_wallet = Helpers::extractIncludedAttributes(
            request_data: data_get($dailySusuCreateRequest->all(), 'data.relationships.linked_wallet')
        );

        // Execute the CustomerLinkedWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $request_wallet['resource_id'],
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.daily_susu_code')
        );

        // Execute the FrequencyService and return the resource
        $frequency = $this->frequencyService->execute(
            frequency_code: 'daily'
        );

        // Execute the DailySusuCreateService and return the resource
        $daily_susu = $this->dailySusuCreateService->execute(
            customer: $customer,
            susu_scheme: $susu_scheme,
            frequency: $frequency,
            linked_wallet: $linked_wallet,
            request_data: $request_data
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your daily susu account is created pending approval.',
            data: new DailySusuResource(
                resource: $daily_susu->refresh()
            ),
        );
    }
}
