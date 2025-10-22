<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Shared\Helpers\Helpers;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Services\CustomerLinkedWalletService;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\FrequencyService;
use App\Domain\Shared\Services\SusuSchemeService;
use App\Domain\Susu\Services\BizSusu\BizSusuCreateService;
use App\Interface\Http\Requests\V1\Susu\BizSusu\BizSusuCreateRequest;
use App\Interface\Http\Resources\V1\Susu\BizSusu\BizSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuCreateAction
{
    private CustomerLinkedWalletService $customerLinkedWalletService;
    private SusuSchemeService $susuSchemeService;
    private FrequencyService $frequencyService;
    private BizSusuCreateService $bizSusuCreateService;

    public function __construct(
        CustomerLinkedWalletService $customerLinkedWalletService,
        SusuSchemeService $susuSchemeService,
        FrequencyService $frequencyService,
        BizSusuCreateService $bizSusuCreateService
    ) {
        $this->customerLinkedWalletService = $customerLinkedWalletService;
        $this->susuSchemeService = $susuSchemeService;
        $this->frequencyService = $frequencyService;
        $this->bizSusuCreateService = $bizSusuCreateService;
    }

    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws FrequencyNotFoundException
     */
    public function execute(
        Customer $customer,
        BizSusuCreateRequest $bizSusuCreateRequest
    ): JsonResponse {
        // Extract the main attributes from the request body
        $request_data = Helpers::extractDataAttributes(
            request_data: $bizSusuCreateRequest->all()
        );

        // Extract the linked_wallet from the request body
        $request_wallet = Helpers::extractIncludedAttributes(
            request_data: data_get($bizSusuCreateRequest->all(), 'data.relationships.linked_wallet')
        );

        // Execute the CustomerLinkedWalletService and return the resource
        $linked_wallet = $this->customerLinkedWalletService->execute(
            customer: $customer,
            wallet_resource_id: $request_wallet['resource_id'],
        );

        // Execute the SusuSchemeService and return the resource
        $susu_scheme = $this->susuSchemeService->execute(
            scheme_code: config(key: 'susubox.susu_schemes.biz_susu_code')
        );

        // Execute the FrequencyService and return the resource
        $frequency = $this->frequencyService->execute(
            frequency_code: $request_data['frequency']
        );

        // Execute the BizSusuCreateService and return the resource
        $biz_susu = $this->bizSusuCreateService->execute(
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
            description: 'Your biz susu account is created pending approval.',
            data: new BizSusuResource(
                resource: $biz_susu->refresh()
            ),
        );
    }
}
